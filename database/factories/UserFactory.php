<?php

namespace Database\Factories;

use App\Models\Parents;
use App\Models\Schoolsadmin;
use App\Models\Teachers;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Fakers\Traits\UtilityTrait;
use Exception;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    use UtilityTrait;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    //
    public array $possibleRace = ['Malay', 'Chinese', 'India', 'Others']; // race
    public function definition()
    {
        $this->faker = Faker::create('ms_MY');

        $role = $this->faker->randomElement(['school_admin', 'teacher', 'father', 'super_admin']);

        $name  =  $this->faker->name;
        return [
            'name' => $name,
            'email' => self::generateEmail($name),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Static password
            //if super admin or school admin
            'is_admin' => ($role == 'school_admin' || $role === 'super_admin') ? 1 : 0,
            'role' => $role,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($user) {
            if ($user->role === 'father' || $user->role === 'mother') {
                // Create corresponding parent record
                //use faker malaysia
                Parents::create([
                    'parent_name' => $user->name,
                    //limit length to 15 for phone
                    'phone_number' => self::generatePhoneNumber(),
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'picture_path' => $this->faker->imageUrl(640, 480, 'people'),
                    'race' => $this->faker->randomElement($this->possibleRace),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } //after create father, create a mother


            //cover for teacher
            else if ($user->role === 'teacher') {
                Teachers::create([
                    'teacher_name' => $user->name,
                    'teacher_specialization' => $this->faker->randomElement(['Mathematics', 'Science', 'History']),
                    'user_id' => $user->id,
                    'school_id' => \App\Models\Schoolsinstitutions::inRandomOrder()->first()->id, // Get a valid school_id
                    'picture_path' => $this->faker->imageUrl(640, 480, 'people'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } // for school admin  - school admin id is user id
            else if ($user->role === 'school_admin') {
                //get any school id
                Schoolsadmin::create([
                    'school_admin_id' => $user->id,
                    'school_id' => \App\Models\Schoolsinstitutions::inRandomOrder()->first()->id, // Get a valid school_id
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public static function roleIsAdmin()
    {
        return ['school_admin', 'super_admin'];
    }

    /**
     * Create a teacher record.
     */
    public function createTeacher($user = null)
    {
        $user = $user ?? $this->create(['role' => 'teacher']);

        Teachers::create([
            'teacher_name' => $user->name,
            'teacher_specialization' => $this->faker->randomElement(['Mathematics', 'Science', 'History']),
            'user_id' => $user->id,
            'school_id' => \App\Models\Schoolsinstitutions::inRandomOrder()->first()->id,
            'picture_path' => $this->faker->imageUrl(640, 480, 'people'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $user;
    }

    /**
     * Create a school admin record.
     */
    public function createSchoolAdmin($user = null)
    {
        $user = $user ?? $this->create(['role' => 'school_admin']);

        Schoolsadmin::create([
            'school_admin_id' => $user->id,
            'school_id' => \App\Models\Schoolsinstitutions::inRandomOrder()->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $user;
    }

    public function createSchoolAdminWithSchoolId($schoolId)
    {
        $user = $user ?? $this->create(['role' => 'school_admin']);

        Schoolsadmin::create([
            'school_admin_id' => $user->id,
            'school_id' => $schoolId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $user;
    }
}
