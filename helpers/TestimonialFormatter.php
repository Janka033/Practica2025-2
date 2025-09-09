<?php

class TimeHelper
{
    // Convierte un string UTC (YYYY-mm-dd HH:ii:ss) a DateTime en TZ destino
    public static function toUserDateTime(string $utcString, string $userTz = 'America/Bogota'): DateTime
    {
        $dt = new DateTime($utcString, new DateTimeZone('UTC'));
        $dt->setTimezone(new DateTimeZone($userTz));
        return $dt;
    }

    public static function relative(DateTime $dateTime, ?DateTime $now = null): string
    {
        $now = $now ?: new DateTime('now', $dateTime->getTimezone());
        $diff = $now->getTimestamp() - $dateTime->getTimestamp();
        if ($diff < 0) return 'en el futuro';
        $mins = intdiv($diff, 60);
        if ($mins < 1) return 'justo ahora';
        if ($mins < 60) return "hace $mins min".($mins===1?'':'s');
        $hrs = intdiv($mins, 60);
        if ($hrs < 24) return "hace $hrs hora".($hrs===1?'':'s');
        $days = intdiv($hrs, 24);
        if ($days < 30) return "hace $days día".($days===1?'':'s');
        $months = intdiv($days, 30);
        if ($months < 12) return "hace $months mes".($months===1?'':'es');
        $years = intdiv($months, 12);
        return "hace $years año".($years===1?'':'s');
    }
}