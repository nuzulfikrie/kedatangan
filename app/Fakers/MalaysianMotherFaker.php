<?php

namespace App\Fakers;

use Faker\Generator;
use Illuminate\Support\Arr;

class MalaysianMotherFaker
{

  protected static string $race;

  public function __construct(string $race)
  {
    self::$race = $race;
  }
  //set faker locale to malaysia, generate name, phone number, email, address
  public static function initialize(Generator $faker): void
  {
    $faker->setLocale('ms_MY');
  }

  public function getMotherRace(): string
  {
    return self::$race;
  }

  /**
   * Generate a random Malaysian Mother name.
   *
   * @param Faker\Generator $faker
   * @return string
   */
  public static function generateMotherName(Generator $faker): string
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
    $motherName = $firstName . ' ' . $lastName;
    return $motherName;
  }

  /**
   * Generate a random Malaysian Mother phone number.
   *
   * @param Faker\Generator $faker
   * @return string
   */
  public static function generateMotherPhoneNumber(): string
  {
    $faker = \Faker\Factory::create('ms_MY');
    $phoneNumber = $faker->mobileNumber();
    return $phoneNumber;
  }

  /**
   * Generate a random Malaysian Mother email.
   *
   * @param Faker\Generator $faker
   * @param string $MotherName
   * @return string
   */
  public static function generateMotherEmail(string $MotherName): string
  {
    $faker = \Faker\Factory::create(
      'ms_MY'
    );

    $MotherName = str_replace(' ', '', $MotherName);
    $MotherName = preg_replace('/[^A-Za-z0-9\-]/', '', $MotherName);
    $MotherName = strtolower($MotherName);
    //combine with random email domain
    $emailDomain = $faker->randomElement(['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'icloud.com']);
    $email = $MotherName . '@' . $emailDomain;
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
