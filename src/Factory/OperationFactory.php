<?php

declare(strict_types=1);

namespace App\CommissionTask\Factory;

use App\CommissionTask\Entity\Operation;

/**
 * Class OperationFactory.
 *
 * Factory for creating Operation objects.
 */
class OperationFactory
{
    /**
     * Create an Operation object from an array of data.
     *
     * @param array $data the array of data containing the operation details
     *
     * @return Operation the created Operation object
     */
    public function createOperation(array $data): Operation
    {
        $date = $data[0];
        $userIdentificator = (int) $data[1];
        $userType = $data[2];
        $operationType = $data[3];
        $amount = (string) $data[4];
        $currency = $data[5];

        return new Operation($date, $userIdentificator, $userType, $operationType, $amount, $currency);
    }

    /**
     * Create an array of Operation objects from an array of data arrays.
     *
     * @param array $dataArray the array of data arrays containing the operation details
     *
     * @return Operation[] the array of created Operation objects
     */
    public function createOperationsFromArray(array $dataArray): array
    {
        $operations = [];
        foreach ($dataArray as $data) {
            $operation = $this->createOperation($data);
            $operations[] = $operation;
        }

        return $operations;
    }
}
