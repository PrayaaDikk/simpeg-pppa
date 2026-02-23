<?php

namespace App\Helpers;

class Helper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function formatedDate($dateTime)
    {
        return $dateTime->isoFormat('D MMM Y');
    }
}
