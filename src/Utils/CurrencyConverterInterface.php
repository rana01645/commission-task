<?php

namespace App\CommissionTask\Utils;

/**
 * Interface CurrencyConverterInterface
 *
 * Defines the contract for a currency converter.
 */
interface CurrencyConverterInterface
{
    /**
     * Convert an amount from the base currency to the target currency.
     *
     * @param  float  $amount  The amount to convert.
     * @param  string  $baseCurrency  The base currency.
     * @param  string  $targetCurrency  The target currency.
     *
     * @return float The converted amount.
     */
    public function convert(float $amount, string $baseCurrency, string $targetCurrency): float;
}
