<?php

namespace App\CommissionTask\Tests\Utils;

use App\CommissionTask\Utils\CommissionFeeFormatter;
use PHPUnit\Framework\TestCase;

class CommissionFeeFormatterTest extends TestCase
{
    private $formatter;

    protected function setUp(): void
    {
        $this->formatter = new CommissionFeeFormatter();
    }

     public function testRoundFee()
    {
        $fee = 0.69481973288041;

        // Test rounding to 1 decimal place
        $roundedFee = $this->formatter->roundFee($fee, 1);
        $this->assertEquals(0.7, $roundedFee);

        // Test rounding to 2 decimal places
        $roundedFee = $this->formatter->roundFee($fee, 2);
        $this->assertEquals(0.7, $roundedFee);

        // Test rounding to 3 decimal places
        $roundedFee = $this->formatter->roundFee($fee, 3);
        $this->assertEquals(0.695, $roundedFee);

        $fee = 0.3;

        $roundedFee = $this->formatter->roundFee($fee, 2);
        $this->assertEquals(0.3, $roundedFee);

        $roundedFee = $this->formatter->roundFee($fee, 2);
        $this->assertEquals(0.3, $roundedFee);
    }

    public function testFormatFee()
    {
        $fee = 0.7;

        // Test formatting with 0 decimal places
        $formattedFee = $this->formatter->formatFee($fee, 0);
        $this->assertEquals("1", $formattedFee);

        // Test formatting with 1 decimal place
        $formattedFee = $this->formatter->formatFee($fee, 1);
        $this->assertEquals("0.7", $formattedFee);

        // Test formatting with 2 decimal places
        $formattedFee = $this->formatter->formatFee($fee, 2);
        $this->assertEquals("0.70", $formattedFee);
    }
}

