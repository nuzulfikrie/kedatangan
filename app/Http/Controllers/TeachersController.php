<?php

namespace App\Http\Controllers;

use App\Models\Teachers;
use App\Models\SchoolsInstitutions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teachers::with('school', 'user')->paginate(10);
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = SchoolsInstitutions::all();
        return view('teachers.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'school_id' => 'required|exists:schools_institutions,id',
            'teacher_specialization' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'teacher',
            ]);

            Teachers::create([
                'user_id' => $user->id,
                'school_id' => $request->school_id,
                'teacher_name' => $request->name,
                'teacher_specialization' => $request->teacher_specialization,
                'phone_number' => $request->phone_number,
                'picture_path' => $request->picture_path ?? null,
            ]);
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teachers $teacher)
    {
        $teacher->load('school', 'user');
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teachers $teacher)
    {
        $schools = SchoolsInstitutions::all();
        return view('teachers.edit', compact('teacher', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teachers $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'school_id' => 'required|exists:schools_institutions,id',
            'teacher_specialization' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
        ]);

        DB::transaction(function () use ($request, $teacher) {
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $teacher->update([
                'school_id' => $request->school_id,
                'teacher_name' => $request->name,
                'teacher_specialization' => $request->teacher_specialization,
                'phone_number' => $request->phone_number,
                'picture_path' => $request->picture_path ?? $teacher->picture_path,
            ]);
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teachers $teacher)
    {
        DB::transaction(function () use ($teacher) {
            $teacher->user->delete();
            $teacher->delete();
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
