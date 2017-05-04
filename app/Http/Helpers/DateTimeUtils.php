<?php

/**
 * Date Time Utilities
 *
 * Functions related to date and time
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */
namespace App\Http\Helpers;

trait DateTimeUtils
{

    /**
     * converts date/time to unix timestamp and checks equality
     *
     * @param $a
     * @param $b
     * @return bool
     */
    public function isEqualDatesOrTimes($a, $b)
    {
        return strtotime($a) == strtotime($b);
    }

}