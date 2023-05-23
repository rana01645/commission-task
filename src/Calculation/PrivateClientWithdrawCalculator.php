<?php

declare(strict_types=1);

namespace App\CommissionTask\Calculation;

use App\CommissionTask\Entity\Operation;
use App\CommissionTask\Service\WeeklyWithdrawalHandlerInterface;
use App\CommissionTask\Utils\CurrencyConverterInterface;

/**
 * Class PrivateClientWithdrawCalculator.
 *
 * Calculates the commission fee for private client withdraw operations.
 */
class PrivateClientWithdrawCalculator
{
    private const WITHDRAWAL_FEE_RATE = 0.003; // 0.3%

    private $currencyConverter;
    private $weeklyWithdrawalHandler;

    /**
     * PrivateClientWithdrawCalculator constructor.
     *
     * @param CurrencyConverterInterface       $currencyConverter       the currency converter used for currency conversion
     * @param WeeklyWithdrawalHandlerInterface $weeklyWithdrawalHandler the service for handling weekly withdrawals
     */
    public function __construct(
        CurrencyConverterInterface $currencyConverter,
        WeeklyWithdrawalHandlerInterface $weeklyWithdrawalHandler
    ) {
        $this->currencyConverter = $currencyConverter;
        $this->weeklyWithdrawalHandler = $weeklyWithdrawalHandler;
    }

    /**
     * Calculates the commission fee for a private client withdraw operation.
     *
     * @param Operation $operation the withdraw operation
     *
     * @return float the commission fee amount
     */
    public function calculateCommissionFee(Operation $operation): float
    {
        $amount = $operation->getAmount();
        $currency = $operation->getCurrency();

        // Convert the withdraw amount to EUR using the currency converter
        $convertedAmount = $this->currencyConverter->convert((float) $amount, $currency, 'EUR');

        // Get the commission-able amount based on the weekly withdrawals for the user
        $commissionAbleAmount = $this->weeklyWithdrawalHandler->getCommissionAbleAmount(
            $operation->getUserIdentificator(),
            $convertedAmount,
            $operation->getDate()
        );
        // Convert the commission fee back to the original currency
        $commissionFee = $this->currencyConverter->convert((float) $commissionAbleAmount, 'EUR', $currency);

        // Calculate the commission fee based on the commission-able amount and the withdrawal fee rate
        return $commissionFee * self::WITHDRAWAL_FEE_RATE;
    }
}
