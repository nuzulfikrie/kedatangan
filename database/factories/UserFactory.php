<?php

namespace Database\Factories;

use App\Fakers\MalaysianFatherFaker;
use App\Fakers\MalaysianMotherFaker;
use App\Models\ChildParents;
use App\Models\Childs;
use App\Models\Parents;
use App\Models\Team;
use App\Models\User;
use Faker\Factory as Faker;
use Faker\Generator;
use Faker\Provider\ms_MY\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected array $possibleRace = [
        'Malay', 'Indian', 'Chinese', 'Others'
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create('ms_MY'); // Set the locale to Malaysia


        return [
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            //password is hashed
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => $faker->imageUrl(),
            'current_team_id' => null,
            //flag is admin
            'is_admin' => false,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(callable $callback = null): static
    {
        if (!Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name . '\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
        //this to create a user with a personal team
        //User::factory()->withPersonalTeam()->create();
    }

    /**
     * Indicate that the user is not admin. set role to parent
     *
     * @return static
     */
    public static function roleIsParent(): static
    {
        return static::state(function (array $attributes) {
            return [
                'is_admin' => false,
                //role
                'role' => 'parent',
            ];
        });

        //to create a parent, call this in seeder
        //``` php
        //User::factory()->roleIsParent()->create();
    }

    /**
     * Indicate that the user is admin. set role to admin
     *
     * @return static
     */
    public static function roleIsAdmin(): static
    {
        return static::state(function (array $attributes) {
            return [
                'is_admin' => true,
                //role
                'role' => 'admin',
            ];
        });
    }

    // role is teacher
    public static function roleIsTeacher(): static
    {
        return static::state(function (array $attributes) {
            return [
                'is_admin' => false,
                //role
                'role' => 'teacher',
            ];
        });
    }
    //role is school_admin
    public static function roleIsSchoolAdmin(): static
    {
        return static::state(function (array $attributes) {
            return [
                'is_admin' => false,
                //role
                'role' => 'school_admin',
            ];
        });
    }

    //create a malay father
    public static function userIsMalayFather()
    {

        $fakerGenerator = new Generator();
        $fakerGenerator->addProvider(new Person($fakerGenerator));

        $faker = new MalaysianFatherFaker('Malay');
        $name = $faker->generateFatherName($fakerGenerator);
        return [
            'name' => $name,
            'email' => MalaysianFatherFaker::generateFatherEmail($name),
            'email_verified_at' => now(),
            //password is hashed
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => MalaysianFatherFaker::generatePhotoPath(),
            'current_team_id' => null,
            //flag is admin
            'is_admin' => false,
        ];
    }
    public static function userIsMalayMother()
    {
        $fakerGenerator = new Generator();
        $fakerGenerator->addProvider(new Person($fakerGenerator));
        $faker = new MalaysianMotherFaker('Malay');
        $name = $faker->generateMotherName($fakerGenerator);
        return [
            'name' => $name,
            'email' => MalaysianMotherFaker::generateMotherEmail($name),
            'email_verified_at' => now(),
            //password is hashed
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => MalaysianMotherFaker::generatePhotoPath(),
            'current_team_id' => null,
            //flag is admin
            'is_admin' => false,
        ];
    }

    public static function withChilds(int $count, string $race, string $fatherName)
    {
        //generate records for child
        $childs = [];

        ChildsFactory::initialize($race, $fatherName);
        for ($i = 0; $i < $count; $i++) {
            $childs[] = Childs::factory()->make()->toArray();
        }

        return $childs;
    }

    public static function generateAFamily(string $race)
    {
        //generate a family

        //1 generate a father

        if ($race == 'Malay') {
            $father = UserFactory::userIsMalayFather();
        } else if ($race == 'Chinese') {
            $father = UserFactory::userIsChineseFather();
        } else if ($race == 'Indian') {
            $father = UserFactory::userIsIndianFather();
        }

        //save father
        $father = User::create($father);

        // save father in parents table
        $fakerGenerator = new Generator();
        $fakerGenerator->addProvider(new Person($fakerGenerator));

        $fatherParentRecord = Parents::create([
            'user_id' => $father->id,
            'parent_name' => $father->name,
            'phone_number' => MalaysianFatherFaker::generateFatherPhoneNumber($fakerGenerator),
            'email' => $father->email,
            'picture_path' => $father->profile_photo_path

        ]);

        //2 generate a mother
        if ($race == 'Malay') {
            $mother = UserFactory::userIsMalayMother();
        } else if ($race == 'Chinese') {
            $mother = UserFactory::userIsChineseMother();
        } else if ($race == 'Indian') {
            $mother = UserFactory::userIsIndianMother();
        }

        $mother = User::create($mother);

        // save mother in parents table
        $motherParentRecord = Parents::create([
            'user_id' => $mother->id,
            'parent_name' => $mother->name,
            'phone_number' => MalaysianMotherFaker::generateMotherPhoneNumber(),
            'email' => $mother->email,
            'picture_path' => $mother->profile_photo_path

        ]);
        //3 generate childs
        $childs = UserFactory::withChilds(2, $race, $father->name);

        //save childs
        foreach ($childs as $child) {
            $childs[] = Childs::create($child);
        }
        //4 generate childs parents
        foreach ($childs as $child) {
            //find useing child email
            $child = Childs::where('email', $child['email'])->first();
            ChildParents::create([
                'child_id' => $child->id,
                'parent_id' => $fatherParentRecord->id
            ]);

            ChildParents::create([
                'child_id' => $child['id'],
                'parent_id' => $motherParentRecord->id
            ]);
        }
    }

    //create a chinese father
    public static function userIsChineseFather()
    {
        $fakerGenerator = new Generator();
        $fakerGenerator->addProvider(new Person($fakerGenerator));
        $faker = new MalaysianFatherFaker('Chinese');
        $name = $faker->generateFatherName($fakerGenerator);
        return [
            'name' => $name,
            'email' => MalaysianFatherFaker::generateFatherEmail($name),
            'email_verified_at' => now(),
            //password is hashed
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => MalaysianFatherFaker::generatePhotoPath(),
            'current_team_id' => null,
            //flag is admin
            'is_admin' => false,
        ];
    }
    //create a chinese mother
    public static function userIsChineseMother()
    {
        $fakerGenerator = new Generator();
        $fakerGenerator->addProvider(new Person($fakerGenerator));
        $faker = new MalaysianMotherFaker('Chinese');
        $name = $faker->generateMotherName($fakerGenerator);
        return [
            'name' => $name,
            'email' => MalaysianMotherFaker::generateMotherEmail($name),
            'email_verified_at' => now(),
            //password is hashed
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => MalaysianMotherFaker::generatePhotoPath(),
            'current_team_id' => null,
            //flag is admin
            'is_admin' => false,
        ];
    }
    public static function userIsIndianFather()
    {
        $fakerGenerator = new Generator();
        $fakerGenerator->addProvider(new Person($fakerGenerator));
        $faker = new MalaysianFatherFaker('Indian');
        $name = $faker->generateFatherName($fakerGenerator);
        return [
            'name' => $name,
            'email' => MalaysianFatherFaker::generateFatherEmail($name),
            'email_verified_at' => now(),
            //password is hashed
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => MalaysianFatherFaker::generatePhotoPath(),
            'current_team_id' => null,
            //flag is admin
            'is_admin' => false,
        ];
    }
}