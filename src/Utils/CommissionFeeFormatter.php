<?php

declare(strict_types=1);

namespace App\CommissionTask\Utils;

/**
 * Class CommissionFeeOutputFormatter.
 *
 * Utility class for formatting commission fees.
 */
class CommissionFeeFormatter
{
    /**
     * Round a fee to the specified number of decimal places.
     *
     * @param float $fee           the fee to round
     * @param int   $decimalPlaces the number of decimal places
     *
     * @return float the rounded fee
     */
    public function roundFee(float $fee, int $decimalPlaces): float
    {
        $multiplier = 10 ** $decimalPlaces;

        return ceil($fee * $multiplier) / $multiplier;
    }

    /**
     * Format a fee to a string representation with the specified number of decimal places.
     *
     * @param float $fee           the fee to format
     * @param int   $decimalPlaces the number of decimal places
     *
     * @return string the formatted fee as a string
     */
    public function formatFee(float $fee, int $decimalPlaces): string
    {
        return sprintf('%.'.$decimalPlaces.'f', $fee);
    }
}
