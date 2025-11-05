<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Childs;
use App\Models\Schoolsinstitutions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attendance::with('child');

        // Filter by date if provided
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filter by child if provided
        if ($request->has('child_id')) {
            $query->where('child_id', $request->child_id);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(15);
        $childs = Childs::all();

        return view('attendance.index', compact('attendances', 'childs'));
    }

    /**
     * Show the form for creating a new resource (bulk attendance marking).
     */
    public function create(Request $request)
    {
        $schools = Schoolsinstitutions::with('childs')->get();
        $date = $request->input('date', Carbon::today()->toDateString());

        // Get children who already have attendance marked for the selected date
        $markedAttendance = Attendance::whereDate('date', $date)
            ->pluck('child_id')
            ->toArray();

        return view('attendance.create', compact('schools', 'date', 'markedAttendance'));
    }

    /**
     * Store a newly created resource in storage (bulk attendance).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.child_id' => 'required|exists:childs,id',
            'attendance.*.status' => 'required|string|in:present,absent,late,excused',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['attendance'] as $record) {
                // Check if attendance already exists for this child on this date
                Attendance::updateOrCreate(
                    [
                        'child_id' => $record['child_id'],
                        'date' => $validated['date'],
                    ],
                    [
                        'status' => $record['status'],
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('attendance.index')
                ->with('success', 'Attendance marked successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to mark attendance: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        $attendance->load('child');

        return view('attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        return view('attendance.edit', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:present,absent,late,excused',
            'date' => 'required|date',
        ]);

        try {
            $attendance->update($validated);

            return redirect()
                ->route('attendance.index')
                ->with('success', 'Attendance updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update attendance: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        try {
            $attendance->delete();

            return redirect()
                ->route('attendance.index')
                ->with('success', 'Attendance deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete attendance: ' . $e->getMessage());
        }
    }

    /**
     * Show attendance report/statistics.
     */
    public function report(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $schoolId = $request->input('school_id');

        $query = Attendance::with('child')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($schoolId) {
            $query->whereHas('child', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });
        }

        $attendances = $query->get();

        // Calculate statistics
        $statistics = [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'excused' => $attendances->where('status', 'excused')->count(),
        ];

        $schools = Schoolsinstitutions::all();

        return view('attendance.report', compact('attendances', 'statistics', 'schools', 'startDate', 'endDate'));
    }

    /**
     * Mark attendance for today - quick access.
     */
    public function markToday()
    {
        $date = Carbon::today()->toDateString();
        $schools = Schoolsinstitutions::with('childs')->get();

        // Get children who already have attendance marked for today
        $markedAttendance = Attendance::whereDate('date', $date)
            ->pluck('child_id')
            ->toArray();

        return view('attendance.mark-today', compact('schools', 'date', 'markedAttendance'));
    }
}
