<?php

declare(strict_types=1);

namespace App\CommissionTask\Calculation;

use App\CommissionTask\Entity\Operation;
use App\CommissionTask\Utils\CurrencyConverter;
use App\CommissionTask\Utils\CurrencyConverterInterface;

/**
 * Class DepositCalculator.
 *
 * Calculates the commission fee for deposit operations.
 */
class DepositCalculator
{
    private const DEPOSIT_FEE_RATE = 0.0003; // 0.03%

    private $converter;

    /**
     * DepositCalculator constructor.
     *
     * @param CurrencyConverter $converter the currency converter used for currency conversion
     */
    public function __construct(CurrencyConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Calculates the commission fee for a deposit operation.
     *
     * @param Operation $operation the deposit operation
     *
     * @return float the commission fee amount
     */
    public function calculateCommissionFee(Operation $operation): float
    {
        $amount = $operation->getAmount();
        $currency = $operation->getCurrency();

        // Convert the deposit amount to EUR using the currency converter
        $convertedAmount = $this->converter->convert((float) $amount, $currency, 'EUR');

        // Calculate the commission fee based on the converted amount and the deposit fee rate
        return $convertedAmount * self::DEPOSIT_FEE_RATE;
    }
}
