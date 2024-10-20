<?php

namespace Database\Factories;

use App\Models\ChildParents;
use App\Models\Childs;
use App\Models\Parents;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmergencyContactsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $parentId = \App\Models\Parents::inRandomOrder()->first()->id;
        return [
            'parent_id' => $parentId,
            'child_id' => ChildParents::inRandomOrder()->where('parent_id', $parentId)->first(),
            'name' => $this->faker->name,
            'phone_number' => $this->faker->numerify('##########'), // Generates a 10-digit number
            'relationship' => $this->faker->randomElement(['Grandparent', 'Uncle', 'Aunt', 'Family Friend', 'Neighbor', 'Sibling']),
            'picture_path' => $this->faker->imageUrl(640, 480, 'people'), // Generates a fake image URL
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the emergency contact is for a specific child.
     *
     * @param Childs $child
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forChild(Childs $child)
    {
        return $this->state(function (array $attributes) use ($child) {
            $parent = $child->parents->first();
            return [
                'child_id' => $child->id,
                'parent_id' => $parent ? $parent->id : Parents::factory(),
            ];
        });
    }

    /**
     * Indicate that the emergency contact is for a specific parent.
     *
     * @param Parents $parent
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forParent(Parents $parent)
    {
        return $this->state(function (array $attributes) use ($parent) {
            $child = $parent->childs->first();
            return [
                'parent_id' => $parent->id,
                'child_id' => $child ? $child->id : Childs::factory(),
            ];
        });
    }
}
