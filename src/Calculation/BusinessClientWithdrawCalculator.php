<?php

namespace App\CommissionTask\Calculation;

use App\CommissionTask\Entity\Operation;
use App\CommissionTask\Utils\CurrencyConverterInterface;

/**
 * Class BusinessClientWithdrawCalculator
 *
 * Calculates the commission fee for business client withdrawals.
 */
class BusinessClientWithdrawCalculator
{
    private const WITHDRAWAL_FEE_RATE = 0.005; // 0.5%

    private $currencyConverter;

    /**
     * BusinessClientWithdrawCalculator constructor.
     *
     * @param CurrencyConverterInterface $currencyConverter The currency converter used for currency conversion.
     */
    public function __construct(CurrencyConverterInterface $currencyConverter)
    {
        $this->currencyConverter = $currencyConverter;
    }

    /**
     * Calculates the commission fee for a business client withdrawal operation.
     *
     * @param Operation $operation The withdrawal operation.
     * @return float The commission fee amount.
     */
    public function calculateCommissionFee(Operation $operation): float
    {
        $amount = $operation->getAmount();
        $currency = $operation->getCurrency();

        // Convert the withdrawal amount to EUR using the currency converter
        $convertedAmount = $this->currencyConverter->convert($amount, $currency, 'EUR');

        // Calculate the commission fee based on the converted amount and the withdrawal fee rate
        return $convertedAmount * self::WITHDRAWAL_FEE_RATE;
    }
}
