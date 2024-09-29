<?php

namespace Database\Seeders;

use App\Models\Parents;
use App\Models\User;
use App\Models\Childs;
use App\Models\ChildParents;
use App\Models\EmergencyContacts;
use Illuminate\Database\Seeder;

class ParentSeeder extends Seeder
{
    public function run()
    {
        // Get users with 'parent' role
        $parentUsers = User::where('role', 'parent')->get();

        foreach ($parentUsers as $user) {
            $parent = Parents::factory()->create([
                'user_id' => $user->id,
            ]);

            // Create 1-3 children for each parent
            $childrenCount = rand(1, 3);
            for ($i = 0; $i < $childrenCount; $i++) {
                $child = Childs::factory()->create();

                // Create the child-parent relationship
                ChildParents::create([
                    'child_id' => $child->id,
                    'parent_id' => $parent->id,
                    'active' => true,
                ]);

                // Create a unique emergency contact for each child
                EmergencyContacts::factory()->forChild($child)->create();
            }
        }

        // Create additional parents if needed
        $additionalParentsCount = 10;
        Parents::factory()->count($additionalParentsCount)->create()->each(function ($parent) {
            // Create 1-3 children for each additional parent
            $childrenCount = rand(1, 3);
            for ($i = 0; $i < $childrenCount; $i++) {
                $child = Childs::factory()->create();

                // Create the child-parent relationship
                ChildParents::create([
                    'child_id' => $child->id,
                    'parent_id' => $parent->id,
                    'active' => true,
                ]);

                // Create a unique emergency contact for each child
                EmergencyContact::factory()->forChild($child)->create();
            }
        });
    }
}
