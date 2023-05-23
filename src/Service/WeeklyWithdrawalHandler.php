<?php

namespace App\CommissionTask\Service;

/**
 * Class WeeklyWithdrawalHandle
 *
 * Handle weekly withdrawal operations for clients, considering withdrawal limits and amounts.
 */
class WeeklyWithdrawalHandler implements WeeklyWithdrawalHandlerInterface
{
    private const FREE_WITHDRAWAL_AMOUNT = 1000.00;
    private const MAX_FREE_WITHDRAWALS_PER_WEEK = 3;

    private $weeklyWithdrawals;

    /**
     * WeeklyWithdrawalHandle constructor.
     *
     * @param  array  $weeklyWithdrawals  The weekly withdrawal data for each client.
     */
    public function __construct(array &$weeklyWithdrawals)
    {
        $this->weeklyWithdrawals = &$weeklyWithdrawals;
    }

    /**
     * Get the commissionable amount for a withdrawal operation.
     *
     * @param  int  $clientId  The client ID.
     * @param  float  $amount  The withdrawal amount.
     * @param  string  $date  The withdrawal date.
     *
     * @return float The commissionable amount.
     */
    public function getCommissionableAmount(int $clientId, float $amount, string $date): float
    {
        $currentWeekStart = date('Y-m-d', strtotime('monday this week', strtotime($date)));

        // Check if in the same week
        if ($currentWeekStart === $this->getClientCurrentWeekStart($clientId)) {
            // Check if the client has already made 3 withdrawals in the current week
            if ($this->isClientWithdrawalsLimitReached($clientId)) {
                return $amount;
            }
            // Check if the client has already withdrawn 1000.00 in the current week
            if ($this->getClientCurrentWeekWithdrawals($clientId) >= self::FREE_WITHDRAWAL_AMOUNT) {
                return $amount;
            }
            // Check if the client has already withdrawn 1000.00 in the current week
            if ($this->getClientCurrentWeekWithdrawals($clientId) + $amount > self::FREE_WITHDRAWAL_AMOUNT) {
                $commissionableAmount = $amount - (self::FREE_WITHDRAWAL_AMOUNT - $this->getClientCurrentWeekWithdrawals($clientId));
                $this->saveClientWithdrawalRecord($clientId, $amount);
                return $commissionableAmount;
            }
            $this->saveClientWithdrawalRecord($clientId, $amount);
            return 0;
        }

        $this->saveClientCurrentWeek($clientId, $date);
        $this->saveClientWithdrawalRecord($clientId, $amount);

        if ($amount > self::FREE_WITHDRAWAL_AMOUNT) {
            return $amount - self::FREE_WITHDRAWAL_AMOUNT;
        }

        return 0;
    }

    /**
     * Save the current week data for a client.
     *
     * @param  int  $clientId  The client ID.
     * @param  string  $withdrawalDate  The withdrawal date.
     *
     * @return void
     */
    private function saveClientCurrentWeek(int $clientId, string $withdrawalDate): void
    {
        // Current week start will be the Monday of the week of the withdrawal date
        $currentWeekStart = date('Y-m-d', strtotime('monday this week', strtotime($withdrawalDate)));
        // Current week end will be the Sunday of the week of the withdrawal date
        $currentWeekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($withdrawalDate)));

        $this->weeklyWithdrawals[$clientId]['currentWeekStart'] = $currentWeekStart;
        $this->weeklyWithdrawals[$clientId]['currentWeekEnd'] = $currentWeekEnd;
        $this->weeklyWithdrawals[$clientId]['currentWeekWithdrawalsCount'] = 0;
        $this->weeklyWithdrawals[$clientId]['currentWeekWithdrawalsAmount'] = 0;
    }

    /**
     * Save the withdrawal amount for a client.
     *
     * @param  int  $clientId  The client ID.
     * @param  float  $withdrawalAmount  The withdrawal amount.
     *
     * @return void
     */
    private function saveClientWithdrawalRecord(int $clientId, float $withdrawalAmount): void
    {
        if (!isset($this->weeklyWithdrawals[$clientId]['currentWeekWithdrawalsCount'])) {
            $this->weeklyWithdrawals[$clientId]['currentWeekWithdrawalsCount'] = 0;
        }
        $this->weeklyWithdrawals[$clientId]['currentWeekWithdrawalsCount']++;
        $this->weeklyWithdrawals[$clientId]['currentWeekWithdrawalsAmount'] += $withdrawalAmount;
    }

    /**
     * Check if the client has reached the withdrawals limit for the current week.
     *
     * @param  int  $clientId  The client ID.
     *
     * @return bool True if the limit is reached, false otherwise.
     */
    private function isClientWithdrawalsLimitReached(int $clientId): bool
    {
        return $this->weeklyWithdrawals[$clientId]['currentWeekWithdrawalsCount'] >= self::MAX_FREE_WITHDRAWALS_PER_WEEK;
    }

    /**
     * Get the current week start date for a client.
     *
     * @param  int  $clientId  The client ID.
     *
     * @return string|null The current week start date, or null if not available.
     */
    private function getClientCurrentWeekStart(int $clientId): ?string
    {
        if (isset($this->weeklyWithdrawals[$clientId]['currentWeekStart'])) {
            return $this->weeklyWithdrawals[$clientId]['currentWeekStart'];
        }

        return null;
    }

    /**
     * Get the total withdrawals amount for the current week of a client.
     *
     * @param  int  $clientId  The client ID.
     *
     * @return float The total withdrawals amount.
     */
    private function getClientCurrentWeekWithdrawals(int $clientId): float
    {
        if (isset($this->weeklyWithdrawals[$clientId]['currentWeekWithdrawalsAmount'])) {
            return $this->weeklyWithdrawals[$clientId]['currentWeekWithdrawalsAmount'];
        }

        return 0;
    }
}
