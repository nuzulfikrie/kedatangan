<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Childs;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceRecords extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $start = Carbon::createFromDate(2024, 1, 1);
        $end = Carbon::createFromDate(2024, 12, 31);
        $targetSchoolId = 1711;

        $children = Childs::where('school_id', $targetSchoolId)->get();
        Carbon::createFromDate(2024, 1, 1);
        $end = Carbon::createFromDate(2024, 12, 31);

        // Holidays and school breaks
        $holidays = [
            // Kumpulan A
            ['start' => '2024-01-01', 'end' => '2024-01-01'], // New Year
            ['start' => '2024-01-22', 'end' => '2024-01-23'], // Chinese New Year A
            ['start' => '2024-03-13', 'end' => '2024-03-21'], // Term 1 Holidays A
            ['start' => '2024-05-20', 'end' => '2024-05-21'], // Hari Raya Aidilfitri A
            ['start' => '2024-05-22', 'end' => '2024-06-06'], // Mid Year Holidays A
            ['start' => '2024-07-24', 'end' => '2024-08-01'], // Term 2 Holidays A
            ['start' => '2024-11-15', 'end' => '2024-11-16'], // Deepavali A
            ['start' => '2024-11-20', 'end' => '2024-12-31'], // End of Year Holidays A

            // Kumpulan B with additional state-specific holidays
            ['start' => '2024-01-01', 'end' => '2024-01-01'], // New Year
            ['start' => '2024-01-23', 'end' => '2024-01-24'], // Chinese New Year B
            ['start' => '2024-03-14', 'end' => '2024-03-22'], // Term 1 Holidays B
            ['start' => '2024-05-21', 'end' => '2024-05-22'], // Hari Raya Aidilfitri B
            ['start' => '2024-05-23', 'end' => '2024-06-07'], // Mid Year Holidays B
            ['start' => '2024-07-25', 'end' => '2024-08-02'], // Term 2 Holidays B
            ['start' => '2024-11-13', 'end' => '2024-11-16'], // Deepavali B (excluding Sarawak)
            ['start' => '2024-11-21', 'end' => '2024-12-31'], // End of Year Holidays B
        ];

        $children = Childs::all();

        foreach ($children as $child) {
            $currentDate = $start->copy();

            while ($currentDate->lte($end)) {
                if ($currentDate->isWeekday() && !$this->isHoliday($currentDate, $holidays)) {
                    $status = ['present', 'absent', 'unknown'][array_rand(['present', 'absent', 'unknown'])];
                    if ($status === 'unknown') {
                        DB::table('unknowns')->insert([
                            'child_id' => $child->id,
                            'date' => $currentDate->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        DB::table('attendance')->insert([
                            'child_id' => $child->id,
                            'date' => $currentDate->toDateString(),
                            'status' => $status,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                $currentDate->addDay();
            }
        }
    }

    private function isHoliday(Carbon $date, array $holidays): bool
    {
        foreach ($holidays as $holiday) {
            if ($date->between(Carbon::parse($holiday['start']), Carbon::parse($holiday['end']))) {
                return true;
            }
        }
        return false;
    }
}
