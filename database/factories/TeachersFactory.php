<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TeachersFactory extends Factory
{
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
            'school_id' => $this->faker->numberBetween(1, 10),
            'picture_path' => $this->faker->imageUrl(),

        ];
    }
}
