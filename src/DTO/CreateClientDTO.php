<?php

namespace App\DTO;

use App\Request\CreateClientRequest;

class CreateClientDTO
{
    public function __construct(public string $username, public string $email, public string $phone)
    {
    }

    public static function fromRequest(CreateClientRequest $request): CreateClientDTO
    {
        return new CreateClientDTO($request->username, $request->email, $request->phone);
    }
}