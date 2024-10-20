<?php

namespace Database\Factories;

use App\Fakers\MalaysianChildFaker;
use App\Models\ChildParents;
use App\Models\Schoolsinstitutions;
use App\Models\User;
use Faker\Provider\ms_MY\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Childs>
 */
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Childs>
 */
class ChildsFactory extends Factory
{
    public function definition(): array
    {
        $fakerGenerator = $this->faker;
        $fakerGenerator->addProvider(new Person($fakerGenerator));

        $childName = $this->faker->firstName();

        return [
            'school_id' => Schoolsinstitutions::inRandomOrder()->first()->id,
            'child_name' => $childName,
            'dob' => $this->faker->date(),
            'child_gender' => $this->faker->randomElement(['Male', 'Female']),
            'email' => $this->faker->unique()->safeEmail(),
            'picture_path' => $this->faker->imageUrl(640, 480, 'people'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($child, $attributes) {
            if (isset($attributes['parent_id'])) {
                ChildParents::create([
                    'child_id' => $child->id,
                    'parent_id' => $attributes['parent_id'],
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
