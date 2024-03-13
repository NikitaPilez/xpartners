<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints\Type;

class UpdateAccountRequest extends AbstractJsonRequest
{
    #[Type('boolean')]
    public ?bool $isActive = null;
}