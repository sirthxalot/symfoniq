<?php

/*
 * This file is part of the Symfoniq web application.
 *
 * (c) Alexander Bösch <sta.contribution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Tests\Integration\Command;

use App\Bridge\Twig\Mime\ArchivableEmail;
use App\Command\MailerTestMailCommand;
use App\Entity\EmailArchive;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Contracts\Translation\TranslatorInterface;

class MailerTestTest extends KernelTestCase
{
    private Application $app;
    private CommandTester $console;
    private TranslatorInterface $translator;

    #[\Override]
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->app = new Application($kernel);

        $command = $this->app->find('mailer:test-mail');
        $this->console = new CommandTester($command);

        $this->translator = self::getContainer()->get(TranslatorInterface::class);

        parent::setUp();
    }

    public function testArchivesEmailWithBase64AndRemovesBrowserLink(): void
    {
        $this->console->execute([
            'to' => 'recipient@example.com',
        ]);

        $email = $this->getMailerMessage();

        // find database entry from email archive
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        /** @var EmailArchive|null $archive */
        $archive = $em->getRepository(EmailArchive::class)->findOneBy([], ['id' => 'DESC']);

        $savedHtml = $archive->getHtmlContent();

        $this->console->assertCommandIsSuccessful();

        $this->assertEmailCount(1);

        // check if the browser link is set in the message
        $text = $this->translator->trans('mail.base.browser_link.link', [], 'messages');
        $this->assertEmailHtmlBodyContains($email, $text);

        // check if the email has been stored within the database
        $this->assertNotNull($archive, 'The message has not been stored withing the database.');
        $this->assertNotEmpty($archive->getToken());

        // check if the html converts cid to base64 images
        $this->assertStringNotContainsString('src="cid:', $savedHtml);
        $this->assertStringContainsString('data:image/', $savedHtml);

        // check if the browser link has been removed in the web preview
        $needle = $this->translator->trans('mail.base.browser_link.link', [], 'messages');
        $this->assertStringNotContainsString($needle, $savedHtml);
        $this->assertStringNotContainsString('data-browser-link', $savedHtml);
    }

    public function testAskForPermissionIfEnvIsProduction(): void
    {
        $env = 'prod';

        $this->console->execute([
            'to' => 'recipient@example.com',
            '--env' => $env,
        ]);

        $output = $this->console->getDisplay();

        // check if the environment information will be displayed
        $needle = sprintf(MailerTestMailCommand::MESSAGE_INFO_ENV, $env);
        $this->assertStringContainsString($needle, $output);

        // check if the question will be displayed
        $needle = MailerTestMailCommand::QUESTION_ENV_CONTINUE;
        $this->assertStringContainsString($needle, $output);
    }

    public function testContinueIfEnvIsProductionAndPermissionGiven(): void
    {
        $env = 'prod';

        $this->console->setInputs(['y']);

        $this->console->execute([
            'to' => 'recipient@example.com',
            '--env' => $env,
        ]);

        $output = $this->console->getDisplay();

        $needle = MailerTestMailCommand::MESSAGE_INFO_TEST_ENV_CONTINUE;
        $this->assertStringContainsString($needle, $output);

        $this->assertEmailCount(1);
    }

    public function testExceptionIfToArgumentIsMissing(): void
    {
        $this->expectException(RuntimeException::class);

        $this->expectExceptionMessage('Not enough arguments (missing: "to").');

        $this->console->execute([]);
    }

    public function testFailureIfEnvIsProductionAndPermissionDenied(): void
    {
        $env = 'prod';

        $this->console->setInputs(['n']);

        $this->console->execute([
            'to' => 'recipient@example.com',
            '--env' => $env,
        ]);

        $output = $this->console->getDisplay();

        $this->console->assertCommandFailed();

        $expectedMessage = MailerTestMailCommand::MESSAGE_FAILURE;
        $this->assertStringContainsString($expectedMessage, $output);
    }

    public function testItUsesEnvForFrom(): void
    {
        $this->console->execute([
            'to' => 'recipient@example.com',
        ]);

        $email = $this->getMailerMessage();

        $expectedFrom = $_ENV['MAILER_FROM_NAME'].' <'.$_ENV['MAILER_FROM_MAIL'].'>';
        $this->assertEmailHeaderSame($email, 'From', $expectedFrom);
    }

    public function testMessageCanBeTranslated(): void
    {
        $locale = 'de';

        $this->console->execute([
            'to' => 'recipient@example.com',
            'locale' => $locale,
        ]);

        $email = $this->getMailerMessage();

        $expectedValue = $this->translator->trans('mail.test_mail.subject', [], 'messages', $locale);
        $this->assertEmailSubjectContains($email, $expectedValue);

        $text = $this->translator->trans('mail.test_mail.welcome', [], 'messages', $locale);
        $this->assertEmailHtmlBodyContains($email, $text);
    }

    public function testSendsEmail(): void
    {
        $to = 'recipient@example.com';

        $this->console->execute([
            'to' => $to,
        ]);

        $email = $this->getMailerMessage();

        $this->console->assertCommandIsSuccessful();

        $this->assertEmailCount(1);

        $this->assertInstanceOf(ArchivableEmail::class, $email);

        $this->assertEmailHeaderSame($email, 'To', $to);

        $this->assertEmailAttachmentCount($email, 1);

        $expectedValue = $this->translator->trans('mail.test_mail.subject', [], 'messages', 'en');
        $this->assertEmailSubjectContains($email, $expectedValue);

        $text = $this->translator->trans('mail.test_mail.welcome', [], 'messages', 'en');
        $this->assertEmailHtmlBodyContains($email, $text);

        $this->assertEmailHtmlBodyContains($email, '<!-- template: emails/inky/test-mail.html.twig -->');
    }

    public function testToArgumentEmailValidation(): void
    {
        $this->console->execute([
            'to' => 'recipient',
        ]);

        $output = $this->console->getDisplay();

        $expectedMessage = $this->translator->trans(
            'This value is not a valid email address.',
            [],
            'validators'
        );
        $this->console->assertCommandFailed($expectedMessage);

        $this->assertStringContainsString($expectedMessage, $output);
    }
}
