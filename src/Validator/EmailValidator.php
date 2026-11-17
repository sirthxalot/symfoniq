<?php

/*
 * This file is part of the Symfoniq web application.
 *
 * (c) Alexander Bösch <sta.contribution@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmailValidator
{
    /**
     * Array that list all validation error messages.
     *
     * @var string[]
     */
    private array $errors = [];

    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    /**
     * List all error messages.
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Validate EMail address.
     */
    public function isValid(string $email): bool
    {
        $this->errors = [];

        $blankConstraint = new NotBlank();
        $mailConstraint = new EmailConstraint(null, null, EmailConstraint::VALIDATION_MODE_HTML5);

        $violations = $this->validator->validate($email, [$blankConstraint, $mailConstraint]);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $this->errors[] = $violation->getMessage();
            }

            return false;
        }

        return true;
    }
}
