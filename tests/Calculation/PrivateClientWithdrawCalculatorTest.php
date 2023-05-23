<?php

namespace App\CommissionTask\Tests\Calculation;
use App\CommissionTask\Calculation\PrivateClientWithdrawCalculator;
use App\CommissionTask\Entity\Operation;
use App\CommissionTask\Service\WeeklyWithdrawalHandler;
use App\CommissionTask\Service\WeeklyWithdrawalHandlerInterface;
use App\CommissionTask\Utils\CurrencyConverterInterface;
use PHPUnit\Framework\TestCase;

class PrivateClientWithdrawCalculatorTest extends TestCase
{
    public function testCalculateCommissionFee_FreeWithdrawal()
    {
        // Create a mock CurrencyConverterInterface
        $currencyConverterMock = $this->getMockForAbstractClass(CurrencyConverterInterface::class);

        //get mock instance of WeeklyHandler
        $weeklyHandlerMock = $this->getMockForAbstractClass(WeeklyWithdrawalHandlerInterface::class);

        // Set up the mock behavior for currency converter
        //there will be 2 calls to convert method and first one will return 1000 and second one will return 0
        $currencyConverterMock->expects($this->exactly(2))
            ->method('convert')
            ->willReturnOnConsecutiveCalls(1000.00, 0.00); // Mock the converted amount


        $weeklyHandlerMock->expects($this->once())
            ->method('getCommissionAbleAmount')
            ->willReturn(0.00); // Mock the converted amount



        // Create an instance of PrivateClientWithdrawCalculator
        $calculator = new PrivateClientWithdrawCalculator($currencyConverterMock, $weeklyHandlerMock);

        // Create an operation object for a free withdrawal
        $operation = new Operation('2014-12-31', 4, 'private', 'withdraw', 500.00, 'EUR');

        // Calculate the commission fee
        $commissionFee = $calculator->calculateCommissionFee($operation);

        // Assert the commission fee calculation
        $this->assertEquals(0.00, $commissionFee); // No commission fee for free withdrawal < 1000
    }

    public function testCalculateCommissionFee_ExceededWithdrawal()
    {
        // Create a mock CurrencyConverterInterface
        $currencyConverterMock = $this->getMockForAbstractClass(CurrencyConverterInterface::class);
        $weeklyHandlerMock = $this->getMockForAbstractClass(WeeklyWithdrawalHandlerInterface::class);

        // Set up the mock behavior
        $currencyConverterMock->expects($this->exactly(2))
            ->method('convert')
            ->willReturnOnConsecutiveCalls(1500.00,500.00); // Mock the converted amount

        $weeklyHandlerMock->expects($this->once())
            ->method('getCommissionAbleAmount')
            ->willReturn(500.00); // Mock the converted amount

        // Create an instance of PrivateClientWithdrawCalculator
        $calculator = new PrivateClientWithdrawCalculator($currencyConverterMock, $weeklyHandlerMock);

        // Create an operation object for an exceeded withdrawal
        $operation = new Operation('2014-12-31', 4, 'private', 'withdraw', 1500.00, 'EUR');

        // Calculate the commission fee
        $commissionFee = $calculator->calculateCommissionFee($operation);

        // Assert the commission fee calculation
        $this->assertEquals(1.5, $commissionFee); // Adjust the expected commission fee based on logic
    }

}

