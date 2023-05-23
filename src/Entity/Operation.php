<?php

declare(strict_types=1);

namespace App\CommissionTask\Entity;

/**
 * Class Operation.
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

    const OPERATION_TYPE_DEPOSIT = 'deposit';
    const OPERATION_TYPE_WITHDRAW = 'withdraw';
    const USER_TYPE_PRIVATE = 'private';

    /**
     * Operation constructor.
     *
     * @param string $date               the date of the operation
     * @param int    $userIdentification the identifier of the user
     * @param string $userType           the type of the user
     * @param string $operationType      the type of the operation
     * @param string $amount             the amount of the operation
     * @param string $currency           the currency of the operation
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
     * @return string the date of the operation
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Get the identifier of the user.
     *
     * @return int the identifier of the user
     */
    public function getUserIdentificator(): int
    {
        return $this->userIdentificator;
    }

    /**
     * Get the type of the user.
     *
     * @return string the type of the user
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * Get the type of the operation.
     *
     * @return string the type of the operation
     */
    public function getOperationType(): string
    {
        return $this->operationType;
    }

    /**
     * Get the amount of the operation.
     *
     * @return string the amount of the operation
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * Get the currency of the operation.
     *
     * @return string the currency of the operation
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Get the count of decimal places in the amount.
     *
     * @return int the count of decimal places in the amount
     */
    public function getFractionCount(): int
    {
        $lastDotPosition = strrpos($this->amount, '.');
        if ($lastDotPosition !== false) {
            $fractionalLength = strlen(substr($this->amount, $lastDotPosition + 1));
        } else {
            $fractionalLength = 0;
        }

        return $fractionalLength;
    }
}
