<?php

namespace Database\Factories;

use Faker\Generator;
use App\Fakers\MalaysianChildFaker;
use Faker\Provider\ms_MY\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Childs>
 */
class ChildsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected static string $race;
    protected static string $fatherName;
    public static function initialize(string $race, string $fatherName)
    {
        self::$race = $race;
        self::$fatherName = $fatherName;
    }
    public function definition(): array
    {
        $fakerGenerator = new Generator();
        $fakerGenerator->addProvider(new Person($fakerGenerator));
        $faker = new MalaysianChildFaker(self::$race, self::$fatherName);
        $faker::setGender();
        $name = $faker->generateChildName($fakerGenerator);

        return [
            // 1 to 100
            'school_id' => $this->faker->numberBetween(1, 100),
            'child_name' => $name,
            'dob' => $faker->generateChildDOB(),
            'child_gender' => MalaysianChildFaker::getGender(),
            'email' => MalaysianChildFaker::generateEmail($name),
            'picture_path' => MalaysianChildFaker::filePicturePath()

        ];
    }
}
