<?php

declare(strict_types=1);

namespace App\DTO;

use App\Request\UpdateAccountRequest;

class UpdateAccountDTO
{
    public ?bool $isActive = null;

    public static function fromRequest(UpdateAccountRequest $request): UpdateAccountDTO
    {
        $updateAccountDTO = new UpdateAccountDTO();

        if (isset($request->isActive)) {
            $updateAccountDTO->isActive = $request->isActive;
        }

        return $updateAccountDTO;
    }
}