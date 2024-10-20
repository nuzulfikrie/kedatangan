<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\File;

class ParentsPairSeeder extends Seeder
{

    protected $filePath;

    public function __construct()
    {
        $this->filePath = storage_path('app/parent_pairs.txt');
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the file is cleared before seeding
        File::put($this->filePath, '');

        for ($i = 0; $i < 10; $i++) { // Create 10 pairs
            // Create father
            $father = User::factory()->create(['role' => 'father']);

            // Create mother
            $mother = User::factory()->create(['role' => 'mother']);

            // Record the pair in the text file
            $this->recordParentPair($father->id, $mother->id);
        }
    }

    /**
     * Record a pair of father and mother IDs in the text file.
     */
    protected function recordParentPair(int $fatherId, int $motherId): void
    {
        $pair = "{$fatherId},{$motherId}\n";
        File::append($this->filePath, $pair);
    }
}
