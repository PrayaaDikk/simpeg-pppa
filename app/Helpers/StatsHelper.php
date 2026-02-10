<?php

namespace App\Helpers;

class StatsHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function calculatePercentage($value, $total, $decimals = 1)
    {
        if ($total == 0) {
            return '0%';
        }

        return number_format(($value / $total) * 100, $decimals) . '%';
    }
}
