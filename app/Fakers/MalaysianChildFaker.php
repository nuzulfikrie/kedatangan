<?php

namespace App\Fakers;

use Faker\Generator;
use Illuminate\Support\Arr;

class MalaysianChildFaker
{

    //class create a child faker - childname, child gender , email, picture path
    /**
     * CREATE TABLE `childs` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`school_id` BIGINT(20) UNSIGNED NOT NULL,
	`child_name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`child_gender` VARCHAR(15) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`picture_path` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `childs_email_unique` (`email`) USING BTREE,
	INDEX `childs_school_id` (`school_id`) USING BTREE,
	CONSTRAINT `childs_school_id` FOREIGN KEY (`school_id`) REFERENCES `schools_institutions` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;

     */
    protected static array $possibleGender = ['male', 'female'];
    protected static array $possibleRace = ['Malay', 'Chinese', 'Indian'];
    protected static string $gender;
    protected static string $race;

    protected static string $fatherName;

    public function __construct(string $race, string $fatherName)
    {
        self::$race = $race;
        self::$fatherName = $fatherName;
    }

    public static function setRace(string $race)
    {
        self::$race = $race;
    }

    public static function setFatherName(string $fatherName)
    {
        self::$fatherName = $fatherName;
    }



    //set faker locale to malaysia, generate name, phone number, email, address
    public static function initialize(Generator $faker): void
    {
        $faker->setLocale('ms_MY');
    }

    public function getChildRace(): string
    {
        return self::$race;
    }

    public static function setGender()
    {
        self::$gender = Arr::random(self::$possibleGender);
    }


    public static function getGender(): string
    {

        return self::$gender;
    }
    /**
     * Generate a random Malaysian child name.
     *
     * @param Faker\Generator $faker
     * @return string
     */
    public static function generateChildName(Generator $faker): string
    {
        if (self::$race == 'Malay' && self::$gender == 'male') {
            $firstName = $faker->firstNameMaleMalay();
        } else if (self::$race == 'Chinese'  && self::$gender == 'male') {
            $firstName = self::$fatherName . ' ' . $faker->firstNameMaleChinese();
        } else if (self::$race == 'Indian'  && self::$gender == 'male') {
            $firstName = $faker->firstNameMaleIndian();
        }
        if (self::$race == 'Malay' && self::$gender == 'female') {
            $firstName = $faker->firstNameFemaleMalay();
        } else if (self::$race == 'Chinese'  && self::$gender == 'female') {
            $firstName = self::$fatherName . ' ' . $faker->firstNameFemaleChinese();
        } else if (self::$race == 'Indian'  && self::$gender == 'female') {
            $firstName = $faker->firstNameFemaleIndian();
        }
        if (self::$race == 'Malay') {
            if (self::$gender == 'male') {
                $lastName = 'bin ' . self::$fatherName;
            } else {
                $lastName = 'binti ' . self::$fatherName;
            }
        } else if (self::$race == 'Chinese') {
            $lastName = $faker->lastNameChinese();
        } else if (self::$race == 'Indian') {
            if (self::$gender == 'male') {
                $lastName = 'a/l ' . self::$fatherName;
            } else {
                $lastName = 'a/p ' . self::$fatherName;
            }
        }
        if (empty($firstName)) {
            $firstName = $faker->firstName();
        }

        if (empty($lastName)) {
            $lastName = $faker->lastName();
        }
        $nameChild = $firstName . ' ' . $lastName;
        return $nameChild;
    }

    //generate dob, max age 7 from today
    public static function generateChildDob(): string
    {
        $faker = \Faker\Factory::create(
            'ms_MY'
        );
        $dob = $faker->dateTimeBetween('-7 years', 'now')->format('Y-m-d');
        return $dob;
    }

    //generate email
    public static function generateEmail(string  $name)
    {
        $faker = \Faker\Factory::create(
            'ms_MY'
        );

        $name = str_replace(' ', '', $name);
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name);
        $name = strtolower($name);
        //now
        $nowTimeStamp = time();

        //combine name and time

        $name = $name . substr(hash('md5', $nowTimeStamp), 0, 5);

        //combine with random email domain
        $emailDomain = $faker->randomElement(['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'icloud.com']);
        $email = $name . '@' . $emailDomain;
        return $email;
    }


    public static function filePicturePath()
    {
        $faker = \Faker\Factory::create();
        return $faker->imageUrl();
    }

    //school id
    public static function generateSchoolId(Generator $faker)
    {
        return $faker->numberBetween(1, 100);
    }
}
