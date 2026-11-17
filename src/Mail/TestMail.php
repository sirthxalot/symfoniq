<?php

/*
 * This file is part of the Symfoniq web application.
 *
 * (c) Alexander Bösch <sta.contribution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Mail;

use App\Bridge\Twig\Mime\ArchivableEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Contracts\Translation\TranslatorInterface;

class TestMail
{
    public function __construct(
        private MailerInterface $mailer,
        private TranslatorInterface $translator,
    ) {
    }

    protected function getLogoDataPart(): DataPart
    {
        return new DataPart(fopen('assets/img/symfoniq-logo.png', 'r'), 'logo', 'image/png');
    }

    protected function getMessage(string $locale = 'en'): array
    {
        $text = [];

        $message = $_ENV['APP_NAME'] ?? 'Symfoniq';

        $text['app_name'] = $message;

        $message = $this->translator->trans('mail.test_mail.welcome', [], 'messages', $locale);
        $message .= '!';

        $text['welcome'] = $message;

        $message = $this->translator->trans('mail.test_mail.lead', [], 'messages', $locale);

        $text['lead'] = $message;

        $message = $this->translator->trans('mail.test_mail.paragraph', [], 'messages', $locale);

        $text['paragraph'] = $message;

        $message = $this->translator->trans('mail.test_mail.primary', [], 'messages', $locale);

        $text['primary'] = $message;

        $message = $this->translator->trans('mail.test_mail.social.lead', [], 'messages', $locale);

        $text['social']['lead'] = $message;

        $message = $this->translator->trans('mail.test_mail.social.facebook.link', [], 'messages', $locale);

        $text['social']['facebook']['link'] = $message;

        $message = $this->translator->trans('mail.test_mail.social.facebook.text', [], 'messages', $locale);

        $text['social']['facebook']['text'] = $message;

        $message = $this->translator->trans('mail.test_mail.social.twitter.link', [], 'messages', $locale);

        $text['social']['twitter']['link'] = $message;

        $message = $this->translator->trans('mail.test_mail.social.twitter.text', [], 'messages', $locale);

        $text['social']['twitter']['text'] = $message;

        $message = $this->translator->trans('mail.test_mail.social.google.link', [], 'messages', $locale);

        $text['social']['google']['link'] = $message;

        $message = $this->translator->trans('mail.test_mail.social.google.text', [], 'messages', $locale);

        $text['social']['google']['text'] = $message;

        $message = $this->translator->trans('mail.test_mail.contact.lead', [], 'messages', $locale);
        $text['contact']['lead'] = $message;

        $message = $this->translator->trans('mail.test_mail.contact.phone', [], 'messages', $locale);
        $text['contact']['phone'] = $message;

        $message = $this->translator->trans('mail.test_mail.contact.mail.text', [], 'messages', $locale);
        $text['contact']['mail']['text'] = $message;

        $message = $this->translator->trans('mail.test_mail.contact.mail.link', [], 'messages', $locale);
        $text['contact']['mail']['link'] = $message;

        $message = $this->translator->trans('mail.test_mail.contact.mail.address', [], 'messages', $locale);
        $text['contact']['mail']['address'] = $message;

        return $text;
    }

    public function getSubject(string $locale = 'en'): string
    {
        return $this->translator->trans('mail.test_mail.subject', [], 'messages', $locale);
    }

    public function send(string $recipient, string $locale = 'en'): void
    {
        $message = $this->getMessage($locale);
        $logo = $this->getLogoDataPart();

        $email = (new ArchivableEmail())
            ->to($recipient)
            ->subject($this->getSubject($locale))
            ->locale($locale)
            ->htmlTemplate('emails/inky/test-mail.html.twig')
            ->textTemplate('emails/inky/test-mail.txt.twig')
            ->context(context: [
                'message' => $message,
            ])
            ->addPart($logo->asInline());

        $this->mailer->send($email);
    }
}
