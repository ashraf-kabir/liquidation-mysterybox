<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class View_helper
{
    public function time_format ($hour, $minute=0)
    {
        $final_minute = ($minute < 10) ? ('0' . $minute) : $minute;

        $final_time = ($hour < 13) ? ($hour . ':' . $final_minute .' AM') : ( ($hour % 12) . ':' . $final_minute .' PM');
        if ($hour == 12)
        {
            $final_time = 12 . ':' . $final_minute . ' PM';
        }

        if ($hour == 24)
        {
            $final_time = '12' . ':' . $final_minute .' AM';
        }

        return $final_time;
    }
}