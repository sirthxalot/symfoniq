<?php

/*
 * This file is part of the Symfoniq web application.
 *
 * (c) Alexander Bösch <sta.contribution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Mail\TestMail;
use App\Validator\EmailValidator;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[AsCommand(name: 'mailer:test-mail')]
class MailerTestMailCommand extends Command
{
    private InputInterface $input;
    private OutputInterface $output;

    public const string MESSAGE_FAILURE = 'The command has been aborted.';
    public const string MESSAGE_INFO_ENV = 'The command is running within the %s environment.';
    public const string MESSAGE_INFO_TEST_ENV_CONTINUE = 'Continue with given environment.';
    public const string MESSAGE_SUCCESS = 'You have a new command! Now make it your own! Pass --help to see your options.';
    public const string MESSAGE_WARN_ENV = 'You should run the command only within the test or dev environment.';
    public const string QUESTION_ENV_CONTINUE = 'Are you sure to continue? (y/n)';

    public function __construct(
        private EmailValidator $emailValidator,
        private TestMail $testMail,
    ) {
        parent::__construct();
    }

    public function __invoke(
        InputInterface $input,
        OutputInterface $output,
        SymfonyStyle $io,
        #[Argument(description: 'The recipient of the message')] string $to,
        #[Argument(description: 'Set the locale for the message')] string $locale = 'en',
    ): int {
        $this->input = $input;
        $this->output = $output;

        $message = sprintf(self::MESSAGE_INFO_ENV, $this->getEnv());
        $io->info($message);

        if (!$this->emailValidator->isValid($to)) {
            $message = $this->emailValidator->getErrors();
            $io->error($message);

            return Command::FAILURE;
        }

        $continue = $this->askForPermissionIfEnvIsProduction($io);

        if (false === $continue) {
            $io->error(self::MESSAGE_FAILURE);

            return Command::FAILURE;
        }

        try {
            $this->testMail->send($to, $locale);
            $io->success(self::MESSAGE_SUCCESS);

            return Command::SUCCESS;
        } catch (TransportExceptionInterface $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Test Mailer transports by sending an email')
            ->setHelp(
                'The <info>mailer:test</info> command tests a Mailer transport by sending a simple email message:'.PHP_EOL.PHP_EOL.
                '<info>php bin/console mailer:test-mail to@example.com</info>'
            );
    }

    protected function askForPermissionIfEnvIsProduction(SymfonyStyle $io): bool
    {
        if ('prod' === $this->getEnv()) {
            $io->warning(self::MESSAGE_WARN_ENV);

            $helper = new QuestionHelper();
            $question = new ConfirmationQuestion(self::QUESTION_ENV_CONTINUE, false);

            if (!$helper->ask($this->input, $this->output, $question)) {
                return false;
            }
        }

        $io->info(self::MESSAGE_INFO_TEST_ENV_CONTINUE);

        return true;
    }

    protected function getEnv(): string
    {
        return $this->input->getOption('env')
                    ?? $_ENV['APP_ENV']
                    ?? getenv('APP_ENV')
                    ?? 'prod';
    }
}
