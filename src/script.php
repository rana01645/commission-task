<?php

declare(strict_types=1);

namespace App\CommissionTask;

require __DIR__.'/../vendor/autoload.php';

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
use Dotenv\Dotenv;

// Read the operations from the CSV file
// Read the CSV file path from the command line argument
if ($argc < 2) {
    echo "Usage: php src/script.php <csv_file_path>\n";
    exit(1);
}

$csvFilePath = $argv[1];

// Read the operations from the CSV file
try {
    $csvReader = new CsvReader($csvFilePath);
    $operationsData = $csvReader->readFile();
} catch (\Exception $e) {
    throw new \RuntimeException('Error reading the CSV file: '.$e->getMessage());
}

// Load the .env file
$dotenv = Dotenv::createUnsafeImmutable(__DIR__.'/../');
$dotenv->load();
$currencyApiURL = (string) getenv('CURRENCY_CONVERTER_API');

// Convert the operations data to Operation objects
$operationFactory = new OperationFactory();
$operations = $operationFactory->createOperationsFromArray($operationsData);

// Initialize the calculators and other components

try {
    $currencyExchangeRates = new CurrencyExchangeRates($currencyApiURL);
} catch (\Exception $e) {
    throw new \RuntimeException('Error fetching the currency exchange rates: '.$e->getMessage());
}

$currencyConverter = new CurrencyConverter($currencyExchangeRates->getExchangeRates());

$weeklyWithdrawals = [];

$weeklyWithdrawalHandles = new WeeklyWithdrawalHandler($weeklyWithdrawals);

$depositCalculator = new DepositCalculator($currencyConverter);
$privateWithdrawCalculator = new PrivateClientWithdrawCalculator($currencyConverter, $weeklyWithdrawalHandles);
$businessWithdrawCalculator = new BusinessClientWithdrawCalculator($currencyConverter);
$commissionFeeFormatter = new CommissionFeeFormatter();

// Process each operation and calculate the commission fee
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

    // Format the commission fee for output
    $formattedCommissionFee = $commissionFeeFormatter->formatFee($roundedCommissionFee, $operation->getFractionCount());

    // Display the commission fee
    echo $formattedCommissionFee.PHP_EOL;
}
