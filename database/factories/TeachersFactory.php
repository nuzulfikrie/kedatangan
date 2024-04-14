<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TeachersFactory extends Factory
{
    public static string $name;
    public static int $school_id;
    public static int $user_id;
    public static string $picture_path;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->school_id = $data['school_id'];
        $this->user_id = $data['user_id'];
        $this->picture_path = $data['picture_path'];
    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        //set faker locale to Malaysia
        $this->faker->locale('ms_MY');
        return [
            //

            'teacher_name' => $this->faker->name(),
            'teacher_specialization' => $this->faker->jobTitle(),
            'user_id' => $this->faker->numberBetween(1, 10),
            //school id is foreign key must be from school_institution table id 1 to 100
            'school_id' => $this->faker->numberBetween(1, 100),

            'picture_path' => $this->faker->imageUrl(),

        ];
    }
    //school id is foreign key must be from school_institution table
    //user id is foreign key must be from user table


}
