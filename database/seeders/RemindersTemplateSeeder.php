<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RemindersTemplate;
use App\Models\Schoolsadmin;
use App\Models\SchoolsInstitutions;
use App\Models\User;
use Database\Factories\UserFactory;

class RemindersTemplateSeeder extends Seeder
{
    public function run()
    {
        $languages = ['en', 'ms', 'zh', 'ta'];
        $channels = ['slack', 'telegram', 'whatsapp', 'email'];

        // Get all schools
        $schools = SchoolsInstitutions::all();

        foreach ($schools as $school) {
            // Find an admin for this school
            $admin = Schoolsadmin::where('school_id', $school->id)->first();

            if (!$admin || !User::find($admin->school_admin_id)) {
                //select any user role teacher and make it as admin
                UserFactory::new()->createSchoolAdminWithSchoolId($school->id);
                $admin = Schoolsadmin::where('school_id', $school->id)->first();

                foreach ($languages as $language) {
                    foreach ($channels as $channel) {
                        RemindersTemplate::factory()->create([
                            'language' => $language,
                            'channel' => $channel,
                            'admin_id' => $admin->school_admin_id,
                            'school_id' => $school->id,
                            'reminder' => $this->getReminderText($language, $channel),
                        ]);
                    }
                }
            }

            // Create reminder templates for each language and channel combination
            foreach ($languages as $language) {
                foreach ($channels as $channel) {
                    RemindersTemplate::factory()->create([
                        'language' => $language,
                        'channel' => $channel,
                        'admin_id' => $admin->school_admin_id,
                        'school_id' => $school->id,
                        'reminder' => $this->getReminderText($language, $channel),
                    ]);
                }
            }

            // Additional random and inactive templates
            RemindersTemplate::factory()->count(2)->create([
                'admin_id' => $admin->school_admin_id,
                'school_id' => $school->id,
            ]);

            RemindersTemplate::factory()->count(1)->inactive()->create([
                'admin_id' => $admin->school_admin_id,
                'school_id' => $school->id,
            ]);

            $this->command->info("Created reminder templates for school: {$school->name}");
        }
    }

    private function getReminderText($language, $channel)
    {
        $reminderTexts = [
            'en' => [
                'slack' => 'Reminder: Please mark your child\'s attendance via Slack.',
                'telegram' => 'Don\'t forget to update your child\'s attendance status on Telegram.',
                'whatsapp' => 'Quick reminder: Update your child\'s attendance on WhatsApp.',
                'email' => 'Important: Please confirm your child\'s attendance by replying to this email.',
            ],
            // Other languages...
        ];

        return $reminderTexts[$language][$channel] ?? 'Please update your child\'s attendance.';
    }
}
