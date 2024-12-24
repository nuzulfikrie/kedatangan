<?php

namespace App\Enums;

enum UserEnum: string
{
    case SUPER_ADMIN  = 'super_admin';
    case TEACHER = 'teacher';
    case SCHOOL_ADMIN = 'school_admin';
    case PARENT = 'parent';
    case CHILD = 'child';
    case GUARDIAN = 'guardian';

    public static function getUserTypes(): array
    {
        return [
            self::SUPER_ADMIN,
            self::TEACHER,
            self::SCHOOL_ADMIN,
            self::PARENT,
            self::CHILD,
            self::GUARDIAN,
        ];
    }

    public static function getSuperAdmin(): string
    {
        return self::SUPER_ADMIN->value;
    }

    public static function getTeacher(): string
    {
        return self::TEACHER->value;
    }

    public static function getSchoolAdmin(): string
    {
        return self::SCHOOL_ADMIN->value;
    }

    public static function getParent(): string
    {
        return self::PARENT->value;
    }

    public static function getChild(): string
    {
        return self::CHILD->value;
    }

    public static function getGuardian(): string
    {
        return self::GUARDIAN->value;
    }
}
