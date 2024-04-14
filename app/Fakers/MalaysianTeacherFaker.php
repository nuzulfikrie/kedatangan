<?php

namespace App\Fakers;

use App\Fakers\Traits\UtilityTrait;
use Faker\Generator;

class MalaysianTeacherFaker
{
    use UtilityTrait;
    protected static string $race;

    protected static string $gender;

    protected array $possibleRace = ['Malay', 'Chinese', 'Indian'];

    protected array $possibleGender = ['Male', 'Female'];


    public function __construct(string $race, string $gender)
    {
        self::$race = $race;
        self::$gender = $gender;
    }
    //set faker locale to malaysia, generate name, phone number, email, address
    public static function initialize(Generator $faker): void
    {
        $faker->setLocale('ms_MY');
    }

    public function getTeacherRace(): string
    {
        return self::$race;
    }

    public function getTeacherGender(): string
    {
        return self::$gender;
    }

    public static function generateName(Generator $faker): string
    {
        if (self::$gender == 'Male' && self::$race == 'Malay') {
            $firstName = $faker->firstNameMaleMalay();
            $lastName = $faker->lastNameMalay();
        } else if (self::$gender == 'Male' && self::$race == 'Chinese') {
            $firstName = $faker->firstNameMaleChinese();
            $lastName = $faker->lastNameChinese();
        } else if (self::$gender == 'Male' && self::$race == 'Indian') {
            $firstName = $faker->firstNameMaleIndian();
            $lastName = $faker->lastNameIndian();
        } else if (self::$gender == 'Female' && self::$race == 'Malay') {
            $firstName = $faker->firstNameFemaleMalay();
            $lastName = $faker->lastNameMalay();
        } else if (self::$gender == 'Female' && self::$race == 'Chinese') {
            $firstName = $faker->firstNameFemaleChinese();
            $lastName = $faker->lastNameChinese();
        } else if (self::$gender == 'Female' && self::$race == 'Indian') {
            $firstName = $faker->firstNameFemaleIndian();
            $lastName = $faker->lastNameIndian();
        }

        $teacherName = $firstName . ' ' . $lastName;
        return $teacherName;
    }

    public static function generateMaleName(Generator $faker): string
    {
        if (self::$race == 'Malay') {
            $firstName = $faker->firstNameMaleMalay();
        } else if (self::$race == 'Chinese') {
            $firstName = $faker->firstNameMaleChinese();
        } else if (self::$race == 'Indian') {
            $firstName = $faker->firstNameMaleIndian();
        }

        if (self::$race == 'Malay') {
            $lastName = $faker->lastNameMalay();
        } else if (self::$race == 'Chinese') {
            $lastName = $faker->lastNameChinese();
        } else if (self::$race == 'Indian') {
            $lastName = $faker->lastNameIndian();
        }
        $teacherName = $firstName . ' ' . $lastName;
        return $teacherName;
    }

    public static function generateFemaleName(Generator $faker): string
    {
        if (self::$race == 'Malay') {
            $firstName = $faker->firstNameFemaleMalay();
        } else if (self::$race == 'Chinese') {
            $firstName = $faker->firstNameFemaleChinese();
        } else if (self::$race == 'Indian') {
            $firstName = $faker->firstNameFemaleIndian();
        }

        if (self::$race == 'Malay') {
            $lastName = $faker->lastNameMalay();
        } else if (self::$race == 'Chinese') {
            $lastName = $faker->lastNameChinese();
        } else if (self::$race == 'Indian') {
            $lastName = $faker->lastNameIndian();
        }
        $teacherName = $firstName . ' ' . $lastName;
        return $teacherName;
    }

    /**
     * Generate a random Malaysian teacher phone number.
     *
     * @param \Faker\Generator $faker
     * @return string
     */
    public static function generateTeacherPhoneNumber(): string
    {
        return self::generatePhoneNumber();
    }

    /**
     * Generate a random Malaysian father email.
     *
     * @param \Faker\Generator $faker
     * @param string $teacherName
     * @return string
     */
    public static function generateTeacherEmail(string $teacherName): string
    {
        $email = self::generateEmail($teacherName);
        return $email;
    }

    public static function generateAddress(Generator $faker)
    {
        return self::generateAddress($faker);
    }
}
