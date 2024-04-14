<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NotAttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * 
     * CREATE TABLE `nonattendance` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `child_id` bigint(20) unsigned NOT NULL, `date` date NOT NULL, `reason` varchar(255) NOT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, PRIMARY KEY (`id`), KEY `nonattendance_child_id` (`child_id`), CONSTRAINT `nonattendance_child_id` FOREIGN KEY (`child_id`) REFERENCES `childs` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //

            'reason' => $this->faker->sentence,
        ];
    }
}
