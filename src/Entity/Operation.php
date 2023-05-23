<?php

namespace App\CommissionTask\Entity;

/**
 * Class Operation
 *
 * Represents a financial operation.
 */
class Operation
{
    private $date;
    private $userIdentificator;
    private $userType;
    private $operationType;
    private $amount;
    private $currency;

    /**
     * Operation constructor.
     *
     * @param  string  $date  The date of the operation.
     * @param  int  $userIdentification  The identifier of the user.
     * @param  string  $userType  The type of the user.
     * @param  string  $operationType  The type of the operation.
     * @param  string  $amount  The amount of the operation.
     * @param  string  $currency  The currency of the operation.
     */
    public function __construct(
        string $date,
        int $userIdentification,
        string $userType,
        string $operationType,
        string $amount,
        string $currency
    ) {
        $this->date = $date;
        $this->userIdentificator = $userIdentification;
        $this->userType = $userType;
        $this->operationType = $operationType;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Get the date of the operation.
     *
     * @return string The date of the operation.
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Get the identifier of the user.
     *
     * @return int The identifier of the user.
     */
    public function getUserIdentificator(): int
    {
        return $this->userIdentificator;
    }

    /**
     * Get the type of the user.
     *
     * @return string The type of the user.
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * Get the type of the operation.
     *
     * @return string The type of the operation.
     */
    public function getOperationType(): string
    {
        return $this->operationType;
    }

    /**
     * Get the amount of the operation.
     *
     * @return float The amount of the operation.
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Get the currency of the operation.
     *
     * @return string The currency of the operation.
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Get the count of decimal places in the amount.
     *
     * @return int The count of decimal places in the amount.
     */
    public function getFractionCount(): int
    {
        return strlen(substr(strrchr($this->amount, "."), 1));
    }
}
