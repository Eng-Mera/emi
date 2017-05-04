<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 7/31/16
 * Time: 9:22 PM
 */

namespace App\Http\Helpers;

use App\OpeningDay;

trait OpeningDayTrait
{

    public static function convertToString($openDays)
    {

        $data = (array)$openDays->toArray()['data'];

        $result = [];

        $daysSorted = OpeningDay::getDays();

        foreach ($data as $row) {
            $result[$row['from']][$row['to']][] = $row['day_name'];
        }

        $final = [];

        foreach ((array)$result as $start_date => $details) {
            foreach ($details as $end_date => $days) {
                $arr4 = [];

                foreach ((array)$daysSorted as $v) {
                    if (in_array($v, $days)) {
                        $arr4[] = date('D', strtotime($v));
                    }
                }

                $from = date('h:ia', strtotime($start_date));
                $to = date('h:ia', strtotime($end_date));

                if ($arr4 && count($arr4) >= 1) {
                    if ($arr4[0] != $arr4[count($arr4) - 1]) {
                        $final[] = $arr4[0] . ':' . $arr4[count($arr4) - 1] . ' ' . $from . ' - ' . $to;
                    } else {
                        $final[] = $arr4[0] . ' ' . $from . ' - ' . $to;
                    }
                }
            }
        }

        return $final;
    }

}