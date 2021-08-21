<?php

    namespace App\classes\utility;

    class Time
    {
//        перевод дней в секунды для unixtime
        public static function daysToSeconds(int $days) : int
        {
            $seconds = 3600;
            $hours = 24;
            return $seconds * $hours * $days;
        }

        public static function daysFromNow(int $days) : int
        {
            return time() + self::daysToSeconds($days);
        }

//        дней осталось до указанной даты
        public static function daysLeft(string $beginDate, string $endDate) : int
        {
            $day = 86400;
            $begin = strtotime($beginDate);
            $end = strtotime($endDate);

            if ($begin > $end) {
                return 0;
            }

            $diff = $end - $begin;
            return round($diff/$day);
        }

//        сколько дней прошло от указанной даты
        public static function daysGone(string $date,int $days) : int
        {
            return 1;
        }
    }

