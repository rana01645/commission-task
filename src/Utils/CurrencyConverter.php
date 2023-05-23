<?php

namespace App\CommissionTask\Utils;

use App\CommissionTask\Repository\CurrencyExchangeRates;

/**
 * Class CurrencyConverter
 *
 * Converts currency amounts based on exchange rates.
 */
class CurrencyConverter implements CurrencyConverterInterface
{
    private $exchangeRates;

    /**
     * CurrencyConverter constructor.
     *
     * @param  array  $currencyExchangeRates  array The exchange rates.
     */
    public function __construct(array $currencyExchangeRates)
    {
        $this->exchangeRates = $currencyExchangeRates;
    }

    /**
     * Convert an amount from the base currency to the target currency.
     *
     * @param  float  $amount  The amount to convert.
     * @param  string  $baseCurrency  The base currency.
     * @param  string  $targetCurrency  The target currency.
     *
     * @return float The converted amount.
     *
     * @throws \RuntimeException If the exchange rate for the specified currency is not available.
     */
    public function convert(float $amount, string $baseCurrency, string $targetCurrency): float
    {
        if ($baseCurrency === $targetCurrency) {
            return $amount; // No conversion needed if the target currency is the same as the base currency
        }

        if (!isset($this->exchangeRates[$targetCurrency])) {
            throw new \RuntimeException('Exchange rate not available for the specified currency');
        }

        $rate = $this->exchangeRates[$targetCurrency] / $this->exchangeRates[$baseCurrency];
        return $amount * $rate;
    }
}
