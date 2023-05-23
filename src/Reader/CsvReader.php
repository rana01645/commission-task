<?php

declare(strict_types=1);

namespace App\CommissionTask\Reader;

use Exception;

/**
 * Class CsvReader.
 *
 * Read data from a CSV file.
 */
class CsvReader
{
    private $filePath;

    /**
     * CsvReader constructor.
     *
     * @param string $filePath the path to the CSV file
     *
     * @throws Exception if the file is not readable or not a CSV file
     */
    public function __construct(string $filePath)
    {
        $this->validateFile($filePath);
        $this->filePath = $filePath;
    }

    /**
     * Read the CSV file and return its data as an array.
     *
     * @return array the data read from the CSV file
     *
     * @throws Exception if unable to open the CSV file
     */
    public function readFile(): array
    {
        $data = [];

        if (($handle = fopen($this->filePath, 'rb')) !== false) {
            while (($line = fgetcsv($handle)) !== false) {
                $data[] = $line;
            }

            fclose($handle);
        } else {
            throw new \RuntimeException('Unable to open CSV file: '.$this->filePath);
        }

        return $data;
    }

    /**
     * Validate if the file is a readable CSV file.
     *
     * @param string $filePath the path to the file
     *
     * @throws Exception if the file is not readable or not a CSV file
     */
    private function validateFile(string $filePath): void
    {
        if (!is_file($filePath) || !is_readable($filePath) || pathinfo($filePath, PATHINFO_EXTENSION) !== 'csv') {
            throw new \RuntimeException('Invalid CSV file: '.$filePath);
        }
    }
}
