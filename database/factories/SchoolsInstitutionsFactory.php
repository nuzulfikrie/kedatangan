<?php

namespace Database\Factories;

use Faker\Generator;
use Faker\Provider\ms_MY\Address;
use App\Fakers\MalaysianTadikaFaker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolsInstitutions>
 */
class SchoolsInstitutionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //set faker locale to malaysia
        //faler MalaysianTadikaFaker
        $fakerGenerator = new Generator();
        $fakerGenerator->addProvider(new Address($fakerGenerator));
        $faker = new MalaysianTadikaFaker();
        $name = $faker->generateTadikaName($fakerGenerator);
        return [
            'name' => $name,
            'address' => $faker->generateTadikaAddress($fakerGenerator),
            'phone_number' => $faker->generateTadikaPhoneNumber($fakerGenerator),
            'school_email' => $faker->generateTadikaEmail($fakerGenerator, $name),
            'school_website' => $faker->generateWebsiteDomainForTadikaUsingName($name),
        ];
    }
}
