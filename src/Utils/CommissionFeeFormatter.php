<?php

namespace App\CommissionTask\Utils;

/**
 * Class CommissionFeeOutputFormatter
 *
 * Utility class for formatting commission fees.
 */
class CommissionFeeFormatter
{
    /**
     * Round a fee to the specified number of decimal places.
     *
     * @param  float  $fee  The fee to round.
     * @param  int  $decimalPlaces  The number of decimal places.
     *
     * @return float The rounded fee.
     */
    public function roundFee(float $fee, int $decimalPlaces): float
    {
        $multiplier = 10 ** $decimalPlaces;
        return ceil($fee * $multiplier) / $multiplier;
    }

    /**
     * Format a fee to a string representation with the specified number of decimal places.
     *
     * @param  float  $fee  The fee to format.
     * @param  int  $decimalPlaces  The number of decimal places.
     *
     * @return string The formatted fee as a string.
     */
    public function formatFee(float $fee, int $decimalPlaces): string
    {
        return sprintf("%.".$decimalPlaces."f", $fee);
    }
}
