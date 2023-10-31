<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\SchoolsInstitutionsFactory;

class SchoolsInstitutions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //call factory and create 100 schools
        SchoolsInstitutionsFactory::times(100)->create();
    }
}
