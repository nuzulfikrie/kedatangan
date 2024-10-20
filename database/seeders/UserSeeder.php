<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create 1000 random records
        for ($i = 0; $i < 1000; $i++) {
            \App\Models\User::factory()->create();
        }
    }
}
