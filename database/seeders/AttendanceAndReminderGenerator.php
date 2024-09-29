<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Nonattendance;
use App\Models\Unknowns;
use App\Models\Reminders;
use App\Models\ReminderSendStatus;
use App\Models\RemindersTemplate;
use App\Models\Childs;
use App\Models\Schoolsinstitutions;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AttendanceAndReminderGenerator
{
    protected $reminderTemplates = [];

    public function __construct()
    {
        $this->seedReminderTemplates();
    }

    protected function seedReminderTemplates()
    {
        $channels = ['slack', 'telegram', 'whatsapp', 'email'];
        $languages = ['en', 'ms', 'zh', 'ta'];

        foreach ($channels as $channel) {
            foreach ($languages as $language) {
                $this->reminderTemplates[] = RemindersTemplate::factory()
                    ->create([
                        'channel' => $channel,
                        'language' => $language,
                    ]);
            }
        }
    }

    public function generateForChild(Childs $child, $date)
    {
        $status = $this->getRandomStatus();

        DB::transaction(function () use ($child, $date, $status) {
            switch ($status) {
                case 'present':
                    $this->createPresentRecord($child, $date);
                    break;
                case 'absent':
                    $this->createAbsentRecord($child, $date);
                    break;
                case 'unknown':
                    $this->createUnknownRecord($child, $date);
                    $this->createReminderAndStatus($child, $date);
                    break;
            }
        });
    }

    protected function getRandomStatus()
    {
        return $this->getRandomWeightedElement([
            'present' => 70,
            'absent' => 20,
            'unknown' => 10,
        ]);
    }

    protected function getRandomWeightedElement(array $weightedValues)
    {
        $rand = mt_rand(1, (int) array_sum($weightedValues));

        foreach ($weightedValues as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
    }

    protected function createPresentRecord(Childs $child, $date)
    {
        Attendance::factory()->create([
            'child_id' => $child->id,
            'date' => $date,
            'status' => 'Present',
        ]);
    }

    protected function createAbsentRecord(Childs $child, $date)
    {
        Nonattendance::factory()->create([
            'child_id' => $child->id,
            'date' => $date,
            'reason' => 'Not specified',
        ]);
    }

    protected function createUnknownRecord(Childs $child, $date)
    {
        Unknowns::factory()->create([
            'child_id' => $child->id,
            'date' => $date,
        ]);
    }

    protected function createReminderAndStatus(Childs $child, $date)
    {
        $template = $this->getRandomReminderTemplate();

        $reminder = Reminders::factory()->create([
            'child_id' => $child->id,
            'date' => $date,
            'reminder' => $template->reminder,
        ]);

        $user = User::where('role', 'parent')
            ->whereHas('parents.childs', function ($query) use ($child) {
                $query->where('id', $child->id);
            })
            ->inRandomOrder()
            ->first();

        ReminderSendStatus::factory()->create([
            'reminder_id' => $reminder->id,
            'user_id' => $user->id,
            'school_id' => $child->school_id,
            'status' => $this->getRandomSendStatus(),
        ]);
    }

    protected function getRandomReminderTemplate()
    {
        return $this->reminderTemplates[array_rand($this->reminderTemplates)];
    }

    protected function getRandomSendStatus()
    {
        return $this->getRandomWeightedElement([
            0 => 10, // Pending
            1 => 70, // Sent
            2 => 10, // Failed
            3 => 10, // Delivered
        ]);
    }
}
