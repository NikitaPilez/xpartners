<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAccountRequest extends AbstractJsonRequest
{
    public const AVAILABLE_CURRENCIES = ['USD', 'EUR', 'RUB'];

    #[NotBlank(message: 'Currency is required field')]
    #[Assert\Choice(choices: self::AVAILABLE_CURRENCIES, message: 'Not found currencies')]
    #[Type('string')]
    public string $currency;

    #[NotBlank(message: 'Client id is required field')]
    #[Type('integer')]
    public int $clientId;
}