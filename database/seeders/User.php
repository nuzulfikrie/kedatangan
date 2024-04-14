<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Database\Factories\UserFactory;
use Database\Factories\TeachersFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User extends Seeder
{
    protected array $possibleRace = [
        'Malay', 'Indian', 'Chinese', 'Others'
    ];


    protected array $possibleGender = ['Male', 'Female'];

    protected array $possibleBoolState = [true, false];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed a malay family 100 times 
        for ($i = 0; $i < 100; $i++) {
            $this->seedAMalayFamily();
        }

        // seed a chinese family 100 times
        for ($i = 0; $i < 100; $i++) {
            $this->seedAChineseFamily();
        }

        // seed an indian family 100 times
        for ($i = 0; $i < 100; $i++) {
            $this->seedAnIndianFamily();
        }

        // seed a teacher 500 times
        for ($i = 0; $i < 500; $i++) {
            $this->seedTeacher();
        }
    }

    //teacher seeder
    protected function seedTeacher()
    {
        //use possible race by picking one value from the array
        $race = $this->possibleRace[array_rand($this->possibleRace)];

        //use possible gender by picking one value from the array
        $gender = $this->possibleGender[array_rand($this->possibleGender)];

        $state = $this->possibleBoolState[array_rand($this->possibleBoolState)];

        //use the factory to generate a teacher
        UserFactory::seedATeacher($race, $gender, $state);
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

    protected function seedAChineseFamily()
    {
        UserFactory::generateAFamily('Chinese');
    }

    protected function seedAnIndianFamily()
    {
        UserFactory::generateAFamily('Indian');
    }
}
