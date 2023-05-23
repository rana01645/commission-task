<?php

namespace App\CommissionTask\Tests\Calculation;

use App\CommissionTask\Calculation\DepositCalculator;
use App\CommissionTask\Entity\Operation;
use PHPUnit\Framework\TestCase;
use App\CommissionTask\Utils\CurrencyConverterInterface;

class DepositCalculatorTest extends TestCase
{
    public function testCalculateCommissionFee()
    {
        // Create a mock CurrencyConverterInterface
        $currencyConverterMock = $this->createMock(CurrencyConverterInterface::class);

        // Set up the mock behavior
        $currencyConverterMock->expects($this->once())
            ->method('convert')
            ->willReturn(1000.00); // Mock the converted amount

        // Create an instance of DepositCalculator
        $calculator = new DepositCalculator($currencyConverterMock);

        // Create an operation object
        $operation = new Operation('2014-12-31', 4, 'private', 'deposit', 1000.00, 'EUR');

        // Calculate the commission fee
        $commissionFee = $calculator->calculateCommissionFee($operation);

        // Assert the commission fee calculation
        $this->assertEquals(0.30, $commissionFee); // Adjust the expected commission fee based on logic
    }
}

