<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateClientRequest extends AbstractJsonRequest
{
    #[NotBlank(message: 'Username is required field')]
    #[Type('string')]
    public string $username;

    #[NotBlank(message: 'Email is required field')]
    #[Type('string')]
    public string $email;

    #[NotBlank(message: 'Phone is required field')]
    #[Type('string')]
    public string $phone;
}