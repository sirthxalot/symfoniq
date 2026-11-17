<?php

/*
 * This file is part of the Symfoniq web application.
 *
 * (c) Alexander Bösch <sta.contribution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Bridge\Twig\Mime\ArchivableEmail;
use App\Entity\EmailArchive;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\BodyRendererInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsEventListener(event: MessageEvent::class)]
class EmailArchiveListener
{
    public function __construct(
        private BodyRendererInterface $bodyRenderer,
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(MessageEvent $event): void
    {
        $email = $event->getMessage();

        if (!$email instanceof ArchivableEmail) {
            return;
        }

        // generate token and browser url
        $token = bin2hex(random_bytes(32));

        $browserUrl = $this->urlGenerator->generate(
            'email_show_in_browser',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        // add url to email with twig context
        $context = $email->getContext();
        $context['browser_view_url'] = $browserUrl;
        $email->context($context);

        // render twig template to html
        $this->bodyRenderer->render($email);

        // read generated HTML
        $htmlBody = (string) $email->getHtmlBody();

        if ('' !== $htmlBody) {
            // convert cid to base64
            $archiveHtml = $this->replaceCidWithBase64($email, $htmlBody);

            // remove browser link
            $archiveHtml = preg_replace('/<table[^>]*data-browser-link[^>]*>.*?<\/table>/is', '', $archiveHtml) ?? $archiveHtml;

            // store generated HTML in database
            $archive = new EmailArchive($token, $archiveHtml);
            $this->entityManager->persist($archive);
            $this->entityManager->flush();
        }
    }

    private function replaceCidWithBase64(ArchivableEmail $email, string $html): string
    {
        // collect all inline attachments
        $inlineAttachments = [];
        foreach ($email->getAttachments() as $attachment) {
            if ($attachment instanceof DataPart && 'inline' === $attachment->getDisposition()) {
                $inlineAttachments[] = $attachment;
            }
        }

        if (empty($inlineAttachments)) {
            return $html;
        }

        // find src="cid:..." in HTML and replace it with base64
        return preg_replace_callback('/src=["\']cid:([^"\']+)["\']/i', function ($matches) use ($inlineAttachments) {
            $cidInHtml = $matches[1]; // z. B. "logo.png@symfony" oder "12345@symfony"

            foreach ($inlineAttachments as $attachment) {
                $name = $attachment->getName() ?? '';
                $contentId = $attachment->hasContentId() ? $attachment->getContentId() : '';

                // check if contentid matches or filename is used
                if (
                    ('' !== $contentId && $cidInHtml === $contentId)
                    || ('' !== $name && str_starts_with($cidInHtml, $name))
                    || 1 === count($inlineAttachments) // Falls es nur 1 Bild gibt, passt es immer!
                ) {
                    $mimeType = $attachment->getMediaType().'/'.$attachment->getMediaSubtype();
                    $base64 = base64_encode($attachment->getBody());

                    return sprintf('src="data:%s;base64,%s"', $mimeType, $base64);
                }
            }

            // fallback: if nothing matches we use the orginal tag
            return $matches[0];
        }, $html) ?? $html;
    }
}
