<?php

declare(strict_types=1);

namespace App\Helpers;

class Exchange
{
    CONST EUR_TO_USD = '1.09';
    CONST EUR_TO_EUR = '1';
    CONST EUR_TO_RUB = '100.37';

    CONST USD_TO_USD = '1';
    CONST USD_TO_EUR = '0.91';
    CONST USD_TO_RUB = '91.74';

    CONST RUB_TO_USD = '0.011';
    CONST RUB_TO_EUR = '0.01';
    CONST RUB_TO_RUB = '1';

    public static function run(string $senderCurrency, string $receiverCurrency, float $value): float
    {
        return $value * constant("self::{$senderCurrency}_TO_{$receiverCurrency}");
    }
}