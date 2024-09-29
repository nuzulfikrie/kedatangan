<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Database\Factories\UserFactory;
use Database\Factories\TeachersFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class User extends Seeder
{
    protected array $possibleRace = ['Malay', 'Indian', 'Chinese', 'Others'];
    protected array $possibleGender = ['Male', 'Female'];
    protected array $possibleBoolState = [true, false];

    public function run(): void
    {
        $familyCount = env('SEED_FAMILY_COUNT', 100);
        $teacherCount = env('SEED_TEACHER_COUNT', 500);

        $this->command->info('Starting to seed users...');

        $this->seedFamilies('Malay', $familyCount);
        $this->seedFamilies('Chinese', $familyCount);
        $this->seedFamilies('Indian', $familyCount);

        $this->seedTeachers($teacherCount);

        $this->command->info('User seeding completed!');
    }

    protected function seedFamilies(string $race, int $count)
    {
        $this->command->info("Seeding $count $race families...");
        for ($i = 0; $i < $count; $i++) {
            DB::transaction(function () use ($race) {
                UserFactory::generateAFamily($race);
            });
            if (($i + 1) % 10 == 0) {
                $this->command->info("Seeded " . ($i + 1) . " $race families");
            }
        }
    }

    protected function seedTeachers(int $count)
    {
        $this->command->info("Seeding $count teachers...");
        for ($i = 0; $i < $count; $i++) {
            try {
                $race = $this->possibleRace[array_rand($this->possibleRace)];
                $gender = $this->possibleGender[array_rand($this->possibleGender)];
                $isAdmin = ($i === 0) ? true : $this->possibleBoolState[array_rand($this->possibleBoolState)];

                UserFactory::seedATeacher($race, $gender, $isAdmin);

                if ($i === 0) {
                    $this->command->info("Seeded first teacher as admin");
                }

                if (($i + 1) % 50 == 0) {
                    $this->command->info("Seeded " . ($i + 1) . " teachers");
                }
            } catch (\Exception $e) {
                $this->command->error("Error seeding teacher: " . $e->getMessage());
            }
        }
    }
}
