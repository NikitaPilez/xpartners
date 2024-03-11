<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UpdateClientRequest extends AbstractJsonRequest
{
    #[Type('string')]
    #[NotBlank(message: 'Username is required field')]
    public ?string $username = null;

    #[Type('string')]
    #[NotBlank(message: 'Email is required field')]
    public ?string $email = null;

    #[Type('string')]
    #[NotBlank(message: 'Phone is required field')]
    public ?string $phone = null;
}