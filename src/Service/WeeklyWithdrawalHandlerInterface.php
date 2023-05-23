<?php

declare(strict_types=1);

namespace App\CommissionTask\Service;

interface WeeklyWithdrawalHandlerInterface
{
    public function getCommissionableAmount(int $clientId, float $amount, string $date): float;
}
