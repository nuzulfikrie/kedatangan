<?php

namespace App\\\Traits;

use Faker\Generator;

trait UtilityTrait
{
    /**
     * Generate a random Malaysian father phone number.
     *
     * @param \Faker\Generator $faker
     * @return string
     */
    public static function generatePhoneNumber(): string
    {
        $faker = \Faker\Factory::create('ms_MY');
        $phoneNumber = $faker->mobileNumber();
        return $phoneNumber;
    }

    /**
     * Generate a random Malaysian father email.
     *
     * @param \Faker\Generator $faker
     * @param string $Name
     * @return string
     */
    public static function generateEmail(string $Name): string
    {
        //use fathername
        //remove spaces, remove special characters lowercase all
        $faker = \Faker\Factory::create(
            'ms_MY'
        );

        $Name = str_replace(' ', '', $Name);
        $Name = preg_replace('/[^A-Za-z0-9\-]/', '', $Name);
        //add timestamp for randomness and then append to name

        $Name = strtolower($Name);
        //combine with random email domain
        $emailDomain = $faker->randomElement(['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'icloud.com']);
        $email = $Name . '@' . $emailDomain;
        return $email;
    }

    public static function generateAddress(Generator $faker)
    {
        return $faker->address();
    }

    public static function generatePhotoPath()
    {
        $faker = \Faker\Factory::create();
        return $faker->imageUrl();
    }
}
