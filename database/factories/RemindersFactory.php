<?php

namespace Database\Factories;

use App\Models\Childs;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'child_id' => Childs::factory(),
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'reminder' => $this->faker->sentence(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the reminder is for a specific child.
     */
    public function forChild(Childs $child)
    {
        return $this->state(function (array $attributes) use ($child) {
            return [
                'child_id' => $child->id,
            ];
        });
    }

    /**
     * Indicate that the reminder is for today.
     */
    public function forToday()
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => now()->format('Y-m-d'),
            ];
        });
    }

    /**
     * Indicate that the reminder is for a future date.
     */
    public function forFuture()
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => $this->faker->dateTimeBetween('tomorrow', '+1 month')->format('Y-m-d'),
            ];
        });
    }

    /**
     * Indicate that the reminder is for a past date.
     */
    public function forPast()
    {
        return $this->state(function (array $attributes) {
            return [
                'date' => $this->faker->dateTimeBetween('-1 month', 'yesterday')->format('Y-m-d'),
            ];
        });
    }

    /**
     * Set a specific reminder message.
     */
    public function withMessage(string $message)
    {
        return $this->state(function (array $attributes) use ($message) {
            return [
                'reminder' => $message,
            ];
        });
    }
}
