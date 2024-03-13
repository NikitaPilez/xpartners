<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class MakePayRequest extends AbstractJsonRequest
{
    #[NotBlank(message: 'Sender id is required field')]
    #[Type('integer')]
    public int $senderId;

    #[NotBlank(message: 'Receiver id is required field')]
    #[Type('integer')]
    public int $receiverId;

    #[NotBlank(message: 'Value is required field')]
    #[Type('float')]
    public float $value;

    #[Type('string')]
    public ?string $comment = '';
}