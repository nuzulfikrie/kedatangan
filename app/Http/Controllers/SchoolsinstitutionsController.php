<?php

namespace App\Http\Controllers;

use App\Models\Schoolsinstitutions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolsinstitutionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = Schoolsinstitutions::withCount(['teachers', 'childs', 'classes'])
            ->paginate(15);

        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:15',
            'school_email' => 'required|email|max:255|unique:schools_institutions,school_email',
            'school_website' => 'required|url|max:255',
        ]);

        try {
            $school = Schoolsinstitutions::create($validated);

            return redirect()
                ->route('schools.index')
                ->with('success', 'School created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create school: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schoolsinstitutions $school)
    {
        $school->loadCount(['teachers', 'childs', 'classes']);
        $school->load(['teachers', 'childs']);

        return view('schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schoolsinstitutions $school)
    {
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schoolsinstitutions $school)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:15',
            'school_email' => 'required|email|max:255|unique:schools_institutions,school_email,' . $school->id,
            'school_website' => 'required|url|max:255',
        ]);

        try {
            $school->update($validated);

            return redirect()
                ->route('schools.index')
                ->with('success', 'School updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update school: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schoolsinstitutions $school)
    {
        DB::beginTransaction();
        try {
            // Check if school has related records
            if ($school->teachers()->count() > 0 || $school->childs()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot delete school with existing teachers or students.');
            }

            $school->delete();

            DB::commit();

            return redirect()
                ->route('schools.index')
                ->with('success', 'School deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to delete school: ' . $e->getMessage());
        }
    }

    /**
     * Display dashboard with statistics for a specific school.
     */
    public function dashboard(Schoolsinstitutions $school)
    {
        $school->loadCount(['teachers', 'childs', 'classes']);

        // Get attendance statistics for today
        $today = now()->toDateString();
        $attendanceToday = DB::table('attendance')
            ->join('childs', 'attendance.child_id', '=', 'childs.id')
            ->where('childs.school_id', $school->id)
            ->whereDate('attendance.date', $today)
            ->select('attendance.status', DB::raw('count(*) as count'))
            ->groupBy('attendance.status')
            ->pluck('count', 'status')
            ->toArray();

        return view('schools.dashboard', compact('school', 'attendanceToday'));
    }
}
