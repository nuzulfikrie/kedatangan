<?php

namespace Database\Seeders;

use App\Models\Unknowns;
use App\Models\Childs;
use Illuminate\Database\Seeder;

class UnknownsSeeder extends Seeder
{
    public function run()
    {
        // Attempt to create 50 random unknown statuses
        for ($i = 0; $i < 50; $i++) {
            Unknowns::factory()->create();
        }

        // Attempt to create 10 unknown statuses for today
        for ($i = 0; $i < 10; $i++) {
            $unknown = Unknowns::factory()->today()->make();
            if ($unknown) {
                $unknown->save();
            }
        }

        // Attempt to create an unknown status for each child
        Childs::all()->each(function ($child) {
            Unknowns::factory()->forChild($child)->create();
        });
    }
}
