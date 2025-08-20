<?php

use Illuminate\Support\Str;

if (!function_exists('datetime_validate')) {

    function datetime_validate($date, $format = ('Y-m-d h:i:s'))

    {

        return date($format, strtotime($date));
    }
}

if (!function_exists('date_validate')) {

    function date_validate($date, $format = ('Y-m-d'))

    {

        return date($format, strtotime($date));
    }
}

if (!function_exists('dateFormate')) {

    function dateFormate($date, $format = ('jS F Y'))

    {
        return date($format, strtotime($date));
    }
}

if (!function_exists('makeUniqueNumericCode')) {
    function makeUniqueNumericCode($length)
    {
        $con = '';
        $number = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
        for ($i = 0; $i < $length; $i++) {
            $rand_value = rand(0, 9);
            $rand_number = $number[$rand_value];
            $con .= $rand_number;
        }
        return $con;
    }
}
