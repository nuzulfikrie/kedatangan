<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RemindersTemplate;
use App\Models\User;
use App\Models\SchoolsInstitutions;

class RemindersTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = ['en', 'ms', 'zh', 'ta'];
        $channels = ['slack', 'telegram', 'whatsapp', 'email'];

        // Get all schools
        $schools = SchoolsInstitutions::all();

        foreach ($schools as $school) {
            // Find an admin for this school
            $admin = User::where('role', 'school_admin')
                ->whereHas('schoolsAdmin', function ($query) use ($school) {
                    $query->where('school_id', $school->id);
                })
                ->first();

            if (!$admin) {
                $this->command->warn("No admin found for school: {$school->name}. Skipping...");
                continue;
            }

            // Create reminder templates for each language and channel combination
            foreach ($languages as $language) {
                foreach ($channels as $channel) {
                    RemindersTemplate::factory()->create([
                        'language' => $language,
                        'channel' => $channel,
                        'admin_id' => $admin->id,
                        'school_id' => $school->id,
                        'reminder' => $this->getReminderText($language, $channel),
                    ]);
                }
            }

            // Create some additional random templates for this school
            RemindersTemplate::factory()->count(2)->create([
                'admin_id' => $admin->id,
                'school_id' => $school->id,
            ]);

            // Create some inactive templates for this school
            RemindersTemplate::factory()->count(1)->inactive()->create([
                'admin_id' => $admin->id,
                'school_id' => $school->id,
            ]);

            $this->command->info("Created reminder templates for school: {$school->name}");
        }
    }

    /**
     * Get reminder text based on language and channel.
     *
     * @param string $language
     * @param string $channel
     * @return string
     */
    private function getReminderText($language, $channel)
    {
        $reminderTexts = [
            'en' => [
                'slack' => 'Reminder: Please mark your child\'s attendance via Slack.',
                'telegram' => 'Don\'t forget to update your child\'s attendance status on Telegram.',
                'whatsapp' => 'Quick reminder: Update your child\'s attendance on WhatsApp.',
                'email' => 'Important: Please confirm your child\'s attendance by replying to this email.',
            ],
            'ms' => [
                'slack' => 'Peringatan: Sila tandakan kehadiran anak anda melalui Slack.',
                'telegram' => 'Jangan lupa untuk mengemaskini status kehadiran anak anda di Telegram.',
                'whatsapp' => 'Peringatan ringkas: Kemas kini kehadiran anak anda di WhatsApp.',
                'email' => 'Penting: Sila sahkan kehadiran anak anda dengan membalas e-mel ini.',
            ],
            'zh' => [
                'slack' => '提醒：请通过Slack标记您孩子的出勤情况。',
                'telegram' => '别忘了在Telegram上更新您孩子的出勤状态。',
                'whatsapp' => '快速提醒：在WhatsApp上更新您孩子的出勤情况。',
                'email' => '重要：请回复此电子邮件确认您孩子的出勤情况。',
            ],
            'ta' => [
                'slack' => 'நினைவூட்டல்: Slack மூலம் உங்கள் குழந்தையின் வருகையைக் குறிக்கவும்.',
                'telegram' => 'உங்கள் குழந்தையின் வருகை நிலையை Telegram இல் புதுப்பிக்க மறக்காதீர்கள்.',
                'whatsapp' => 'விரைவு நினைவூட்டல்: WhatsApp இல் உங்கள் குழந்தையின் வருகையைப் புதுப்பிக்கவும்.',
                'email' => 'முக்கியம்: இந்த மின்னஞ்சலுக்கு பதிலளித்து உங்கள் குழந்தையின் வருகையை உறுதிப்படுத்தவும்.',
            ],
        ];

        return $reminderTexts[$language][$channel] ?? 'Please update your child\'s attendance.';
    }
}
