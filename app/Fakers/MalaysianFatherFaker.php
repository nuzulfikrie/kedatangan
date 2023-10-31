<?php

namespace App\Fakers;

use Faker\Generator;
use Illuminate\Support\Arr;

class MalaysianFatherFaker
{

  protected static string $race;

  protected array $possibleRace = ['Malay', 'Chinese', 'Indian'];

  //constructor with race flag nullable
  public function __construct(string $race)
  {
    self::$race = $race;
  }
  //set faker locale to malaysia, generate name, phone number, email, address
  public static function initialize(Generator $faker): void
  {
    $faker->setLocale('ms_MY');
  }

  public function getFatherRace(): string
  {
    return self::$race;
  }

  /**
   * Generate a random Malaysian father name.
   *
   * @param Faker\Generator $faker
   * @return string
   */
  public static function generateFatherName(Generator $faker): string
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
    $fatherName = $firstName . ' ' . $lastName;
    return $fatherName;
  }

  /**
   * Generate a random Malaysian father phone number.
   *
   * @param Faker\Generator $faker
   * @return string
   */
  public static function generateFatherPhoneNumber(): string
  {
    $faker = \Faker\Factory::create('ms_MY');
    $phoneNumber = $faker->mobileNumber();
    return $phoneNumber;
  }

  /**
   * Generate a random Malaysian father email.
   *
   * @param Faker\Generator $faker
   * @param string $fatherName
   * @return string
   */
  public static function generateFatherEmail(string $fatherName): string
  {
    //use fathername
    //remove spaces, remove special characters lowercase all
    $faker = \Faker\Factory::create(
      'ms_MY'
    );

    $fatherName = str_replace(' ', '', $fatherName);
    $fatherName = preg_replace('/[^A-Za-z0-9\-]/', '', $fatherName);
    $fatherName = strtolower($fatherName);
    //combine with random email domain
    $emailDomain = $faker->randomElement(['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'icloud.com']);
    $email = $fatherName . '@' . $emailDomain;
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
