<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Nonattendance;
use App\Models\Unknowns;
use App\Models\Childs;
use App\Models\Reminders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $date = request('date', Carbon::today()->toDateString());
        $attendances = Attendance::with('child')->whereDate('date', $date)->get();
        $nonAttendances = Nonattendance::with('child')->whereDate('date', $date)->get();
        $unknowns = Unknowns::with('child')->whereDate('date', $date)->get();

        return view('attendance.index', compact('attendances', 'nonAttendances', 'unknowns', 'date'));
    }

    public function create()
    {
        $children = Childs::all();
        return view('attendance.create', compact('children'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'child_id' => 'required|exists:childs,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,unknown',
            'reason' => 'required_if:status,absent|nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $childId = $request->child_id;
            $date = $request->date;

            // Delete any existing records for this child and date
            Attendance::where('child_id', $childId)->whereDate('date', $date)->delete();
            Nonattendance::where('child_id', $childId)->whereDate('date', $date)->delete();
            Unknowns::where('child_id', $childId)->whereDate('date', $date)->delete();

            switch ($request->status) {
                case 'present':
                    Attendance::create([
                        'child_id' => $childId,
                        'date' => $date,
                        'status' => 'Present',
                    ]);
                    break;
                case 'absent':
                    Nonattendance::create([
                        'child_id' => $childId,
                        'date' => $date,
                        'reason' => $request->reason,
                    ]);
                    break;
                case 'unknown':
                    Unknowns::create([
                        'child_id' => $childId,
                        'date' => $date,
                    ]);
                    // Create a reminder for unknown status
                    Reminders::create([
                        'child_id' => $childId,
                        'date' => $date,
                        'reminder' => 'Attendance status is unknown. Please update.',
                    ]);
                    break;
            }
        });

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
    }

    public function edit($childId, $date)
    {
        $child = Childs::findOrFail($childId);
        $attendance = Attendance::where('child_id', $childId)->whereDate('date', $date)->first();
        $nonAttendance = Nonattendance::where('child_id', $childId)->whereDate('date', $date)->first();
        $unknown = Unknowns::where('child_id', $childId)->whereDate('date', $date)->first();

        $status = $attendance ? 'present' : ($nonAttendance ? 'absent' : 'unknown');
        $reason = $nonAttendance ? $nonAttendance->reason : null;

        return view('attendance.edit', compact('child', 'date', 'status', 'reason'));
    }

    public function update(Request $request, $childId, $date)
    {
        $request->validate([
            'status' => 'required|in:present,absent,unknown',
            'reason' => 'required_if:status,absent|nullable|string',
        ]);

        DB::transaction(function () use ($request, $childId, $date) {
            // Delete any existing records for this child and date
            Attendance::where('child_id', $childId)->whereDate('date', $date)->delete();
            Nonattendance::where('child_id', $childId)->whereDate('date', $date)->delete();
            Unknowns::where('child_id', $childId)->whereDate('date', $date)->delete();

            switch ($request->status) {
                case 'present':
                    Attendance::create([
                        'child_id' => $childId,
                        'date' => $date,
                        'status' => 'Present',
                    ]);
                    break;
                case 'absent':
                    Nonattendance::create([
                        'child_id' => $childId,
                        'date' => $date,
                        'reason' => $request->reason,
                    ]);
                    break;
                case 'unknown':
                    Unknowns::create([
                        'child_id' => $childId,
                        'date' => $date,
                    ]);
                    // Create a reminder for unknown status
                    Reminders::create([
                        'child_id' => $childId,
                        'date' => $date,
                        'reminder' => 'Attendance status is unknown. Please update.',
                    ]);
                    break;
            }
        });

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
    }

    public function destroy($childId, $date)
    {
        DB::transaction(function () use ($childId, $date) {
            Attendance::where('child_id', $childId)->whereDate('date', $date)->delete();
            Nonattendance::where('child_id', $childId)->whereDate('date', $date)->delete();
            Unknowns::where('child_id', $childId)->whereDate('date', $date)->delete();
        });

        return redirect()->route('attendance.index')->with('success', 'Attendance record deleted successfully.');
    }

    public function report()
    {
        $startDate = request('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = request('end_date', Carbon::now()->endOfMonth()->toDateString());

        $attendanceReport = Childs::with([
            'attendance' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            },
            'nonattendace' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            },
            'unknowns' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        ])->get()->map(function ($child) use ($startDate, $endDate) {
            $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
            $presentDays = $child->attendance->count();
            $absentDays = $child->nonattendace->count();
            $unknownDays = $child->unknowns->count();

            return [
                'child_name' => $child->child_name,
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'unknown_days' => $unknownDays,
                'attendance_rate' => ($totalDays > 0) ? ($presentDays / $totalDays) * 100 : 0,
            ];
        });

        return view('attendance.report', compact('attendanceReport', 'startDate', 'endDate'));
    }
}
