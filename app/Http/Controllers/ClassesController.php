<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Childs;
use App\Models\Schoolsinstitutions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Classes::with(['schoolsinstitution', 'child']);

        // Filter by school if provided
        if ($request->has('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $classes = $query->paginate(15);
        $schools = Schoolsinstitutions::all();

        return view('classes.index', compact('classes', 'schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = Schoolsinstitutions::all();
        $childs = Childs::all();

        return view('classes.create', compact('schools', 'childs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools_institutions,id',
            'child_id' => 'required|exists:childs,id',
            'class_name' => 'required|string|max:255',
        ]);

        try {
            $class = Classes::create($validated);

            return redirect()
                ->route('classes.index')
                ->with('success', 'Class created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create class: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Classes $class)
    {
        $class->load(['schoolsinstitution', 'child']);

        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classes $class)
    {
        $schools = Schoolsinstitutions::all();
        $childs = Childs::all();

        return view('classes.edit', compact('class', 'schools', 'childs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools_institutions,id',
            'child_id' => 'required|exists:childs,id',
            'class_name' => 'required|string|max:255',
        ]);

        try {
            $class->update($validated);

            return redirect()
                ->route('classes.index')
                ->with('success', 'Class updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update class: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classes $class)
    {
        try {
            $class->delete();

            return redirect()
                ->route('classes.index')
                ->with('success', 'Class deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete class: ' . $e->getMessage());
        }
    }
}
