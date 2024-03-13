<?php

declare(strict_types=1);

namespace App\Helpers;

class GenerateAccountNumber
{
    public static function generate(int $length = 10): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $accountNumber = '';

        for ($i = 0; $i < $length; $i++) {
            $accountNumber .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $accountNumber;
    }
}