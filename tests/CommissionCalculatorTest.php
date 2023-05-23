<?php

namespace App\CommissionTask\Tests;

use App\CommissionTask\Calculation\BusinessClientWithdrawCalculator;
use App\CommissionTask\Calculation\DepositCalculator;
use App\CommissionTask\Calculation\PrivateClientWithdrawCalculator;
use App\CommissionTask\Factory\OperationFactory;
use App\CommissionTask\Reader\CsvReader;
use App\CommissionTask\Repository\CurrencyExchangeRates;
use App\CommissionTask\Service\WeeklyWithdrawalHandler;
use App\CommissionTask\Utils\CommissionFeeFormatter;
use App\CommissionTask\Utils\CurrencyConverter;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    public function testCalculateCommissionFee()
    {
        $csvFilePath = __DIR__ . '/assets/input.csv';

        // Read the operations from the CSV file
        $csvReader = new CsvReader($csvFilePath);

        //mock the operationsData
        $operationsData = $csvReader->readFile();

        // Convert the operations data to Operation objects
        $operationFactory = new OperationFactory();
        $operations = $operationFactory->createOperationsFromArray($operationsData);

        // Initialize the calculators and other components

        //mock the currencyExchangeRates
        $currencyExchangeRates = $this->createMock(CurrencyExchangeRates::class);
        $currencyExchangeRates->method('getExchangeRates')->willReturn([
            'USD' => 1.1497,
            'JPY' => 129.53,
            'EUR' => 1,
        ]);

        $currencyConverter = new CurrencyConverter($currencyExchangeRates->getExchangeRates());

        $weeklyWithdrawals = [];

        $weeklyWithdrawalHandler = new WeeklyWithdrawalHandler($weeklyWithdrawals);

        $depositCalculator = new DepositCalculator($currencyConverter);
        $privateWithdrawCalculator = new PrivateClientWithdrawCalculator($currencyConverter, $weeklyWithdrawalHandler);
        $businessWithdrawCalculator = new BusinessClientWithdrawCalculator($currencyConverter);
        $commissionFeeFormatter = new CommissionFeeFormatter();

        // Process each operation and calculate the commission fee
        $calculatedFees = [];
        foreach ($operations as $operation) {
            $commissionFee = 0.00;

            if ($operation->getOperationType() === 'deposit') {
                $commissionFee = $depositCalculator->calculateCommissionFee($operation);
            } elseif ($operation->getUserType() === 'private') {
                $commissionFee = $privateWithdrawCalculator->calculateCommissionFee($operation);
            } elseif ($operation->getUserType() === 'business') {
                $commissionFee = $businessWithdrawCalculator->calculateCommissionFee($operation);
            }

            // Round the commission fee
            $roundedCommissionFee = $commissionFeeFormatter->roundFee($commissionFee, $operation->getFractionCount());

            //format the commission fee
            $formattedCommissionFee = $commissionFeeFormatter->formatFee($roundedCommissionFee, $operation->getFractionCount());

            // Store the calculated fee
            $calculatedFees[] = $formattedCommissionFee;
        }

        // Define the expected output fees
        $expectedFees = [
            '0.60', '3.00', '0.00', '0.06', '1.50', '0', '0.70', '0.30', '0.30', '3.00', '0.00', '0.00', '8612'
        ];



        // Assert that the calculated fees match the expected fees
        $this->assertEquals($expectedFees, $calculatedFees);
    }
}
