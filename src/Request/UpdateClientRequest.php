<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints\Type;

class UpdateClientRequest extends AbstractJsonRequest
{
    #[Type('string')]
    public ?string $username = null;

    #[Type('string')]
    public ?string $email = null;

    #[Type('string')]
    public ?string $phone = null;
}