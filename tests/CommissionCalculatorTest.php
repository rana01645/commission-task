<?php

namespace App\CommissionTask\Tests;

use App\CommissionTask\Calculation\BusinessClientWithdrawCalculator;
use App\CommissionTask\Calculation\DepositCalculator;
use App\CommissionTask\Calculation\PrivateClientWithdrawCalculator;
use App\CommissionTask\Entity\Operation;
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

        //mock the csv reader
        $csvReader = $this->createMock(CsvReader::class);

        //mock the data
        $csvReader->method('readFile')->willReturn([
            ['2014-12-31', 4, 'private', 'withdraw', '1200.00', 'EUR'],
            ['2015-01-01', 4, 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-01-05', 4, 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-01-05', 1, 'private', 'deposit', '200.00', 'EUR'],
            ['2016-01-06', 2, 'business', 'withdraw', '300.00', 'EUR'],
            ['2016-01-06', 1, 'private', 'withdraw', '30000', 'JPY'],
            ['2016-01-07', 1, 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-01-07', 1, 'private', 'withdraw', '100.00', 'USD'],
            ['2016-01-10', 1, 'private', 'withdraw', '100.00', 'EUR'],
            ['2016-01-10', 2, 'business', 'deposit', '10000.00', 'EUR'],
            ['2016-01-10', 3, 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-02-15', 1, 'private', 'withdraw', '300.00', 'EUR'],
            ['2016-02-19', 5, 'private', 'withdraw', '3000000', 'JPY'],

        ]);

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
            if ($operation->getOperationType() === Operation::OPERATION_TYPE_DEPOSIT) {
                $commissionFee = $depositCalculator->calculateCommissionFee($operation);
            } else {
                if ($operation->getUserType() === Operation::USER_TYPE_PRIVATE) {
                    $commissionFee = $privateWithdrawCalculator->calculateCommissionFee($operation);
                } else {
                    $commissionFee = $businessWithdrawCalculator->calculateCommissionFee($operation);
                }
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
