<?php

namespace App\CommissionTask\Repository;

use Exception;

/**
 * Class CurrencyExchangeRates
 *
 * Fetch and provide currency exchange rates from a JSON file or API endpoint.
 */
class CurrencyExchangeRates
{
    private $exchangeRates;

    /**
     * CurrencyExchangeRates constructor.
     *
     * @param  string  $fileUrl  The URL or path to the JSON file containing exchange rates.
     *
     * @throws Exception If failed to fetch exchange rates data or JSON decoding error.
     */
    public function __construct(string $fileUrl)
    {
        $exchangeRatesData = file_get_contents($fileUrl);
        $exchangeRates = json_decode($exchangeRatesData, true);

        if ($exchangeRates === null) {
            throw new \RuntimeException('Failed to fetch exchange rates data');
        }

        $this->exchangeRates = $exchangeRates['rates'];
    }

    /**
     * Get the exchange rates.
     *
     * @return array The exchange rates.
     */
    public function getExchangeRates(): array
    {
        return $this->exchangeRates;
    }
}
