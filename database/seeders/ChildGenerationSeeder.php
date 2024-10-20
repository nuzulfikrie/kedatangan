<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Childs;
use App\Models\ChildParents;
use App\Models\Parents;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;
use App\Fakers\MalaysianChildFaker;
use Faker\Generator;

class ChildGenerationSeeder extends Seeder
{

    protected $filePath;
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create('ms_MY');
        $this->filePath = storage_path('app/parent_pairs.txt');
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read the file content
        $pairs = File::lines($this->filePath);
        foreach ($pairs as $pair) {
            $pair = trim($pair);
            dump($pair);

            if (empty($pair) || strpos($pair, ',') === false) {
                // You can either skip this line or throw an exception
                continue; // or throw new \Exception("Invalid pair: $pair");
            }

            [$fatherId, $motherId] = explode(',', $pair);

            // Create 5 children for each parent pair
            for ($i = 0; $i < 5; $i++) {

                $child = $this->createChild((int) $fatherId);

                // Associate child with father and mother in child_parents table
                $this->linkChildWithParent($child->id, (int) $fatherId);
                $this->linkChildWithParent($child->id, (int) $motherId);
            }
        }
    }

    protected function createChild(int $fatherId)
    {
        $father = Parents::where('user_id', $fatherId)->with(['user'])->first();

        $fatherName =  $father->user->name;
        $race = $father->race;

        $childFaker = new MalaysianChildFaker($race, $fatherName);
        $childFaker::setGender();
        $generator = new Generator();
        $generator->addProvider(new \Faker\Provider\ms_MY\Person($generator));

        $name = $childFaker::generateChildName($generator);
        $child = Childs::factory()->create([
            'school_id' => \App\Models\Schoolsinstitutions::inRandomOrder()
                ->first()->id, // Get a valid school_id
            'child_name' => $name,
            'dob' => $this->faker->dateTimeBetween('-6 years', '-3 years'),
            'child_gender' => $childFaker::getGender(),
            'email' => $childFaker::generateEmail($name),
            'picture_path' => $this->faker->imageUrl(640, 480, 'people'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return $child;
    }

    /**
     * Link a child with a parent in the `child_parents` table.
     */
    protected function linkChildWithParent(int $childId, int $parentId): void
    {
        $parentData = Parents::where('user_id', $parentId)->first();
        ChildParents::create([
            'child_id' => $childId,
            'parent_id' => $parentData->id,
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
