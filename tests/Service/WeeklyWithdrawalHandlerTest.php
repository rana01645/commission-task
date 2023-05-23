<?php

namespace App\CommissionTask\Tests\Service;

use App\CommissionTask\Service\WeeklyWithdrawalHandler;
use PHPUnit\Framework\TestCase;

class WeeklyWithdrawalHandlerTest extends TestCase
{
    public function testGetCommissionableAmount_WithinLimit()
    {
        // Create an instance of WeeklyWithdrawalHandler
        $weeklyWithdrawals = [];
        $handler = new WeeklyWithdrawalHandler($weeklyWithdrawals);

        // Define the test data
        $clientId = 1;
        $amount = 500.00;
        $date = '2023-05-19';

        // Calculate the commissionable amount
        $commissionAmount = $handler->getCommissionableAmount($clientId, $amount, $date);

        // Assert the commissionable amount is the same as the withdrawal amount
        $this->assertEquals(0, $commissionAmount);
    }

    public function testGetCommissionableAmount_ExceedLimit()
    {
        // Create an instance of WeeklyWithdrawalHandler
        $weeklyWithdrawals = [];
        $handler = new WeeklyWithdrawalHandler($weeklyWithdrawals);

        // Define the test data
        $clientId = 1;
        $amount = 1500.00;
        $date = '2023-05-19';

        // Calculate the commissionable amount
        $commissionableAmount = $handler->getCommissionableAmount($clientId, $amount, $date);

        // Assert the commissionable amount is the exceeded amount (1500 - 1000)
        $this->assertEquals(500.00, $commissionableAmount);
    }

    public function testWeeklyLimitOnCommision()
    {
// Create an instance of WeeklyWithdrawalHandler
        $weeklyWithdrawals = [];
        $handler = new WeeklyWithdrawalHandler($weeklyWithdrawals);

        // Define the test data
        $clientId = 1;
        $amount = 100.00;
        $date = '2023-05-19';

        // Calculate the commissionable amount
        $commissionableAmount = $handler->getCommissionableAmount($clientId, $amount, $date);

        // Assert the commissionable amount is the exceeded amount (1500 - 1000)
        $this->assertEquals(0, $commissionableAmount);


        $clientId = 1;
        $amount = 100.00;
        $date = '2023-05-19';

        // Calculate the commissionable amount
        $commissionableAmount = $handler->getCommissionableAmount($clientId, $amount, $date);

        // Assert the commissionable amount is the exceeded amount (1500 - 1000)
        $this->assertEquals(0, $commissionableAmount);


        $clientId = 1;
        $amount = 100.00;
        $date = '2023-05-19';

        // Calculate the commissionable amount
        $commissionableAmount = $handler->getCommissionableAmount($clientId, $amount, $date);

        // Assert the commissionable amount is the exceeded amount (1500 - 1000)
        $this->assertEquals(0, $commissionableAmount);

        //4th transaction in same week should be charged the full amount
        $clientId = 1;
        $amount = 100.00;
        $date = '2023-05-19';
        $commissionableAmount = $handler->getCommissionableAmount($clientId, $amount, $date);

        $this->assertEquals(100, $commissionableAmount);

    }

}

