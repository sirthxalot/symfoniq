<?php

/*
 * This file is part of the Symfoniq web application.
 *
 * (c) Alexander Bösch <sta.contribution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class EmailArchive
{
    #[ORM\Id] #[ORM\GeneratedValue] #[ORM\Column] private ?int $id = null;
    #[ORM\Column(length: 64, unique: true)]
    private string $token;

    #[ORM\Column(type: Types::TEXT)]
    private string $htmlContent;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $token, string $htmlContent)
    {
        $this->token = $token;
        $this->htmlContent = $htmlContent;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
