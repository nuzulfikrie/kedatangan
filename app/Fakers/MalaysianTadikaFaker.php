<?php

namespace App\Fakers;

use Faker\Generator;
use Illuminate\Support\Arr;

class MalaysianTadikaFaker
{

  public static function initialize(Generator $faker): void
  {
    $faker->setLocale('ms_MY');
  }
  /**
   * Generate a random Malaysian tadika or kindergarten name.
   *
   * @param \Faker\Generator $faker
   * @return string
   */
  public static function generateTadikaName(Generator $faker): string
  {
    $tadikaTypes = [
      'Tadika',
      'Taman Asuhan Kanak-Kanak (TASKA)',
      'Prasekolah',
      'PASTI (Pertubuhan Anak-anak Selamat Tinggal Islam)',
      'Tabika',
      'Taski',
      'TADIKA KEMAS',
      'TADIKA PERPADUAN',
      'Islamic Kindergarten',
      'Kindergarten',
      'Nursery',
      'Childcare',
      'Daycare',
    ];

    $tadikaType = Arr::random($tadikaTypes);
    $tadikaName =  $faker->township();

    return $tadikaType . ' ' . $tadikaName;
  }

  /**
   * Generate a random Malaysian tadika or kindergarten address.
   *
   * @param \Faker\Generator $faker
   * @return string
   */
  public static function generateTadikaAddress(Generator $faker): string
  {
    $states = [
      'Johor',
      'Kedah',
      'Kelantan',
      'Kuala Lumpur',
      'Labuan',
      'Melaka',
      'Negeri Sembilan',
      'Pahang',
      'Perak',
      'Perlis',
      'Putrajaya',
      'Sabah',
      'Sarawak',
      'Selangor',
      'Terengganu',
    ];

    $state = Arr::random($states);
    $city = $faker->city();
    $street = $faker->streetAddress();
    $postcode = $faker->postcode();

    return $street . ', ' . $city . ', ' . $state . ' ' . $postcode;
  }

  /**
   * Generate a random Malaysian tadika or kindergarten phone number.
   *
   * @param \Faker\Generator $faker
   * @return string
   */
  public static function generateTadikaPhoneNumber(Generator $faker): string
  {
    $phonePrefixes = [
      '03',
      '04',
      '05',
      '06',
      '07',
      '08',
      '09',
    ];

    $phonePrefix = $faker->randomElement($phonePrefixes);
    $phoneNumber = $faker->numerify('#######');

    return $phonePrefix . $phoneNumber;
  }

  public static function generateWebsiteDomainForTadikaUsingName(string $tadikaName)
  {

    $validTld = [
      'com',
      'org',
      'edu',
      'gov',
      'net',
      'biz',
      'info',
      'my',
      'asia',
    ];

    //use tadika name//
    $tadikaName = str_replace(' ', '', $tadikaName);
    $tadikaName = strtolower($tadikaName);

    $tadikaName = strtolower($tadikaName) . '.' . Arr::random($validTld);

    //remove all element invalid in a url 
    $tadikaName = preg_replace('/[^a-zA-Z0-9\-\.]/', '', $tadikaName);
    //return tadika name
    return $tadikaName;
  }
  /**
   * Generate a random Malaysian tadika or kindergarten email.
   *
   * @param \Faker\Generator $faker
   * @return string
   */
  public static function generateTadikaEmail(Generator $faker, string $name): string
  {
    $emailProviders = [
      'gmail.com',
      'yahoo.com',
      'hotmail.com',
      'outlook.com',
      'icloud.com',
    ];

    //if name is too long, generate mnemonic name
    if (strlen($name) > 20) {
      $name = self::pluckAndCapitalize($name);
    }

    $emailProvider = $faker->randomElement($emailProviders);
    $emailName = 'admin' . $name;

    $emailName = strtolower($emailName) . '@' . $emailProvider;

    //remove all element invalid in an email string
    $emailName = preg_replace('/[^a-zA-Z0-9\.\-_]/', '', $emailName);
    return $emailName;
  }

  protected static function pluckAndCapitalize($str)
  {
    $words = explode(' ', $str); // Split the string into words
    $acronyms = array_map(function ($word) {
      return strtoupper($word[0]); // Get the first character of each word and capitalize it
    }, $words);
    return implode('', $acronyms); // Combine the acronyms into a single string
  }
}
