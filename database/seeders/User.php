<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User extends Seeder
{
    protected array $possibleRace = [
        'Malay', 'Indian', 'Chinese', 'Others'
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $this->seedAMalayFamily();
    }

    //teacher seeder
    protected function seedTeacher()
    {
    }
    //school admin seeder
    protected function seedSchoolAdmin()
    {
    }

    //teacher seeder
    protected function seedParent()
    {
        //in stages. first create father,
        // use callback, get father race,
        // generate mother use father race
        // generate child  use father race
        User::factory()
            ->count(1)
            ->roleIsParent()
            ->create();
    }

    protected function seedAMalayFamily()
    {
        UserFactory::generateAFamily('Malay');
    }
}
