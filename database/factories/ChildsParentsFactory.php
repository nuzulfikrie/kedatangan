<?php

namespace Database\Factories;

use App\Models\Childs;
use App\Models\Parents;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ChildsParentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     **/
    public function definition(): array
    {
        return [
            // child id must be from child table
            'child_id' => Childs::factory(),
            'parent_id' => Parents::factory(),
            'active' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),

        ];
    }
}
