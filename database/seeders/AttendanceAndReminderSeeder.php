<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Childs;
use Carbon\Carbon;

class AttendanceAndReminderSeeder extends Seeder
{
    public function run()
    {
        $generator = new AttendanceAndReminderGenerator();

        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        Childs::chunk(100, function ($children) use ($generator, $startDate, $endDate) {
            foreach ($children as $child) {
                for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                    if ($date->isWeekday()) {
                        $generator->generateForChild($child, $date);
                    }
                }
            }
        });
    }
}
