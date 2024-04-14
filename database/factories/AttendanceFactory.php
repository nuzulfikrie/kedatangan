<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * CREATE TABLE `attendance` (
     * `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `child_id` bigint(20) unsigned NOT NULL, `date` date NOT NULL, `status` varchar(50) NOT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, PRIMARY KEY (`id`), KEY `attendance_child_id` (`child_id`), CONSTRAINT `attendance_child_id` FOREIGN KEY (`child_id`) REFERENCES `childs` (`id`)
     * ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci
     * 
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'status' => $this->faker->randomElement(['Present', 'Absent', 'Late']),
        ];
    }
}
