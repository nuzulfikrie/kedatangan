<?php

namespace App\Fakers;

use Faker\Generator;


class MalaysiaSchoolsFaker
{
  /**
   * Generate a random Malaysian school name.
   *
   * @param Faker\Generator $faker
   * @return string
   */
  public static function generateSchoolName(Generator $faker): string
  {
    $schoolTypes = [
      'Sekolah Menengah Kebangsaan (SMK)',
      'Sekolah Menengah Agama Negeri (SMAN)',
      'Sekolah Menengah Sains Taraf Premier (SMSTP)',
      'Sekolah Menengah Jenis Kebangsaan (SMJK)',
      'Sekolah Menengah Swasta (SMS)',
    ];

    $schoolType = $faker->randomElement($schoolTypes);
    $schoolName = $faker->words(3, true);

    return $schoolType . ' ' . implode(' ', $schoolName);
  }

  /**
   * Generate a random Malaysian school address.
   *
   * @param Faker\Generator $faker
   * @return string
   */
  public static function generateSchoolAddress(Generator $faker): string
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

    $state = $faker->randomElement($states);
    $city = $faker->city();
    $street = $faker->streetAddress();
    $postcode = $faker->postcode();

    return $street . ', ' . $city . ', ' . $state . ' ' . $postcode;
  }
}
