<?php

declare(strict_types=1);

namespace App\CommissionTask\Utils;

/**
 * Interface CurrencyConverterInterface.
 *
 * Defines the contract for a currency converter.
 */
interface CurrencyConverterInterface
{
    /**
     * Convert an amount from the base currency to the target currency.
     *
     * @param float  $amount         the amount to convert
     * @param string $baseCurrency   the base currency
     * @param string $targetCurrency the target currency
     *
     * @return float the converted amount
     */
    public function convert(float $amount, string $baseCurrency, string $targetCurrency): float;
}
