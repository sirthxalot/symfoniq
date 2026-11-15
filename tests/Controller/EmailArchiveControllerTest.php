<?php

/*
 * This file is part of the Symfoniq web application.
 *
 * (c) Alexander Bösch <sta.contribution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailArchiveControllerTest extends WebTestCase
{
    private Application $app;
    private CommandTester $console;
    private KernelBrowser $client;
    private TranslatorInterface $translator;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->app = new Application($this->client->getKernel());

        $command = $this->app->find('mailer:test-mail');
        $this->console = new CommandTester($command);

        $this->translator = static::getContainer()->get(TranslatorInterface::class);
    }

    public function testBrowserLinkFromSentEmailLoadsCorrectly(): void
    {
        $this->console->execute(['to' => 'recipient@example.com']);

        $email = $this->getMailerMessage();

        $sentHtml = (string) $email->getHtmlBody();

        $this->console->assertCommandIsSuccessful();

        $this->assertEmailCount(1);

        // check if the browser link is set within the message
        $assert = $this->translator->trans('mail.base.browser_link.link', [], 'messages');
        preg_match('/<a[^>]+href="([^"]+)"[^>]*>'.$assert.'<\/a>/i', $sentHtml, $matches);
        $this->assertArrayHasKey(1, $matches, 'Browser link is missing in the email.');

        $browserUrl = html_entity_decode($matches[1]);

        // get browser link
        $this->client->request('GET', $browserUrl);

        // check if the browser previes loads successfully
        $this->assertResponseIsSuccessful();

        $responseContent = (string) $this->client->getResponse()->getContent();

        $needle = $this->translator->trans('mail.test_mail.welcome', [], 'messages');
        $this->assertStringContainsString($needle, $responseContent);

        $needle = $this->translator->trans('mail.base.browser_link.link', [], 'messages');
        $this->assertStringNotContainsString($needle, $responseContent);

        $this->assertStringNotContainsString('src="cid:', $responseContent);
    }

    public function testInvalidTokenReturnsNotFoundOrForbidden(): void
    {
        // get invalid token
        $this->client->request('GET', '/email/show/ungueltiger-token-123'); // Passe den Pfad an deine Route an

        $this->assertTrue(
            in_array($this->client->getResponse()->getStatusCode(), [403, 404], true),
            'Invalid token should respond with 403 or 404.'
        );
    }
}
