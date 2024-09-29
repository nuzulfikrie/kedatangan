<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Childs;
use App\Models\Nonattendance;
use App\Models\Unknowns;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nonattendance>
 */
class NonattendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $child = Childs::inRandomOrder()->first() ?? Childs::factory()->create();
        $date = $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');

        // Ensure no attendance, nonattendance, or unknown record exists for this child and date
        while ($this->recordExists($child->id, $date)) {
            $date = $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');
        }

        return [
            'child_id' => $child->id,
            'date' => $date,
            'reason' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    protected function recordExists($childId, $date): bool
    {
        return Attendance::where('child_id', $childId)
            ->where('date', $date)
            ->exists()
            || Nonattendance::where('child_id', $childId)
            ->where('date', $date)
            ->exists()
            || Unknowns::where('child_id', $childId)
            ->where('date', $date)
            ->exists();
    }

    public function today()
    {
        return $this->state(function (array $attributes) {
            $child = Childs::inRandomOrder()->first() ?? Childs::factory()->create();
            $date = now()->format('Y-m-d');

            // If a record exists for today, return null (no record will be created)
            if ($this->recordExists($child->id, $date)) {
                return null;
            }

            return [
                'child_id' => $child->id,
                'date' => $date,
            ];
        });
    }

    public function forChild(Childs $child)
    {
        return $this->state(function (array $attributes) use ($child) {
            $date = $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');

            // Ensure no attendance, nonattendance, or unknown record exists for this child and date
            while ($this->recordExists($child->id, $date)) {
                $date = $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');
            }

            return [
                'child_id' => $child->id,
                'date' => $date,
            ];
        });
    }
}
