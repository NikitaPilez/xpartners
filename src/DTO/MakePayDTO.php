<?php

declare(strict_types=1);

namespace App\DTO;

use App\Request\MakePayRequest;

class MakePayDTO
{
    public function __construct(public int $senderId, public int $receiverId, public float $value, public ?string $comment = null)
    {
    }

    public static function fromRequest(MakePayRequest $request): MakePayDTO
    {
        return new MakePayDTO($request->senderId, $request->receiverId, $request->value, $request->comment);
    }
}