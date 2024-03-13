<?php

declare(strict_types=1);

namespace App\DTO;

use App\Request\UpdateClientRequest;

class UpdateClientDTO
{
    public ?string $username = null;
    public ?string $email = null;
    public ?string $phone = null;

    public static function fromRequest(UpdateClientRequest $request): UpdateClientDTO
    {
        $updateClientDTO = new UpdateClientDTO();

        if (isset($request->username)) {
            $updateClientDTO->username = $request->username;
        }

        if (isset($request->email)) {
            $updateClientDTO->email = $request->email;
        }

        if (isset($request->phone)) {
            $updateClientDTO->phone = $request->phone;
        }

        return $updateClientDTO;
    }
}