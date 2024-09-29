<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\SchoolsInstitutions;
use Illuminate\Database\Eloquent\Factories\Factory;

class RemindersTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reminder' => $this->faker->sentence(10),
            'active' => $this->faker->boolean(80), // 80% chance of being active
            'admin_id' => User::factory()->roleIsAdmin(),
            'school_id' => SchoolsInstitutions::factory(),
            'language' => $this->faker->randomElement(['en', 'ms', 'zh', 'ta']), // English, Malay, Chinese, Tamil
            'channel' => $this->faker->randomElement(['slack', 'telegram', 'whatsapp', 'email']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the reminder template is active.
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => true,
            ];
        });
    }

    /**
     * Indicate that the reminder template is inactive.
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false,
            ];
        });
    }

    /**
     * Set the channel for the reminder template.
     */
    public function channel(string $channel)
    {
        return $this->state(function (array $attributes) use ($channel) {
            return [
                'channel' => $channel,
            ];
        });
    }

    /**
     * Set the language for the reminder template.
     */
    public function language(string $language)
    {
        return $this->state(function (array $attributes) use ($language) {
            return [
                'language' => $language,
            ];
        });
    }
}
