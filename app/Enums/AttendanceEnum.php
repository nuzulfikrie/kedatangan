<?php

namespace App\Enums;

enum AttendanceEnum: string
{
    case PRESENT = 'present';
    case ABSENT = 'absent';
    case LATE = 'late';
    case EARLY = 'early';
    case EXCUSED = 'excused';
    case UNEXCUSED = 'unexcused';
    case UNKNOWN = 'unknown';

    public static function getPresent(): string
    {
        return self::PRESENT->value;
    }

    public static function getAbsent(): string
    {
        return self::ABSENT->value;
    }

    public static function getLate(): string
    {
        return self::LATE->value;
    }

    public static function getEarly(): string
    {
        return self::EARLY->value;
    }

    public static function getExcused(): string
    {
        return self::EXCUSED->value;
    }

    public static function getUnexcused(): string
    {
        return self::UNEXCUSED->value;
    }

    public static function getUnknown(): string
    {
        return self::UNKNOWN->value;
    }


    public static function getAttendanceStatuses(): array
    {
        return [
            self::PRESENT,
            self::ABSENT,
            self::LATE,
            self::EARLY,
            self::EXCUSED,
            self::UNEXCUSED,
            self::UNKNOWN
        ];
    }
}
