<?php

/*
 * This file is part of the Symfoniq web application.
 *
 * (c) Alexander Bösch <sta.contribution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\EmailArchive;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailViewController extends AbstractController
{
    #[Route('/email/view/{token}', name: 'email_show_in_browser')]
    public function show(string $token, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $archive = $em->getRepository(EmailArchive::class)
                      ->findOneBy(['token' => $token]);

        if (!$archive) {
            $message = $translator->trans('mail.controller.view.message_missing', [], 'messages');
            throw $this->createNotFoundException($message);
        }

        return new Response($archive->getHtmlContent());
    }
}
