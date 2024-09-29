<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Childs;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        // Attempt to create 100 random attendance records
        for ($i = 0; $i < 100; $i++) {
            Attendance::factory()->create();
        }

        // Attempt to create 20 attendance records for today
        for ($i = 0; $i < 20; $i++) {
            $attendance = Attendance::factory()->today()->make();
            if ($attendance) {
                $attendance->save();
            }
        }

        // Attempt to create an attendance record for each child
        Childs::all()->each(function ($child) {
            Attendance::factory()->forChild($child)->create();
        });
    }
}
