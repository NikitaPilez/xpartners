<?php

declare(strict_types=1);

namespace App\DTO;

use App\Request\CreateAccountRequest;

class CreateAccountDTO
{
    public function __construct(public int $clientId, public string $currency)
    {
    }

    public static function fromRequest(CreateAccountRequest $request): CreateAccountDTO
    {
        return new CreateAccountDTO($request->clientId, $request->currency);
    }
}