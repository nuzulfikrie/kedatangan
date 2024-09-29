<?php

namespace Database\Seeders;

use App\Models\Nonattendance;
use App\Models\Childs;
use Illuminate\Database\Seeder;

class NonattendanceSeeder extends Seeder
{
    public function run()
    {
        // Attempt to create 50 random nonattendance records
        for ($i = 0; $i < 50; $i++) {
            Nonattendance::factory()->create();
        }

        // Attempt to create 10 nonattendance records for today
        for ($i = 0; $i < 10; $i++) {
            $nonattendance = Nonattendance::factory()->today()->make();
            if ($nonattendance) {
                $nonattendance->save();
            }
        }

        // Attempt to create a nonattendance record for each child
        Childs::all()->each(function ($child) {
            Nonattendance::factory()->forChild($child)->create();
        });
    }
}
