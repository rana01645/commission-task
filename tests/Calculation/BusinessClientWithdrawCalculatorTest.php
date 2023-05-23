<?php

namespace App\CommissionTask\Tests\Calculation;

use App\CommissionTask\Calculation\BusinessClientWithdrawCalculator;
use App\CommissionTask\Entity\Operation;
use App\CommissionTask\Utils\CurrencyConverterInterface;
use PHPUnit\Framework\TestCase;

class BusinessClientWithdrawCalculatorTest extends TestCase
{
    public function testCalculateCommissionFee()
    {
        // Create a mock CurrencyConverterInterface
        $currencyConverterMock = $this->createMock(CurrencyConverterInterface::class);

        // Set up the mock behavior
        $currencyConverterMock->expects($this->once())
            ->method('convert')
            ->willReturn(1200.00); // Mock the converted amount

        // Create an instance of BusinessClientWithdrawCalculator
        $calculator = new BusinessClientWithdrawCalculator($currencyConverterMock);

        // Create an operation object
        $operation = new Operation('2014-12-31', 4, 'private', 'withdraw', 1200.00, 'EUR');

        // Calculate the commission fee
        $commissionFee = $calculator->calculateCommissionFee($operation);

        // Assert the commission fee calculation
        $this->assertEquals(6.00, $commissionFee); // Adjust the expected commission fee based on your logic
    }
}

