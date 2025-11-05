<?php

namespace App\Http\Controllers;

use App\Models\Childs;
use App\Models\Parents;
use App\Models\Schoolsinstitutions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ChildsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $childs = Childs::with(['schoolsinstitution', 'parents'])
            ->paginate(15);

        return view('childs.index', compact('childs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = Schoolsinstitutions::all();
        $parents = Parents::all();

        return view('childs.create', compact('schools', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools_institutions,id',
            'child_name' => 'required|string|max:255',
            'child_gender' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:childs,email',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parents' => 'nullable|array',
            'parents.*' => 'exists:parents,id',
        ]);

        DB::beginTransaction();
        try {
            // Handle picture upload
            if ($request->hasFile('picture')) {
                $picturePath = $request->file('picture')->store('childs', 'public');
                $validated['picture_path'] = $picturePath;
            } else {
                $validated['picture_path'] = 'default.png';
            }

            $child = Childs::create([
                'school_id' => $validated['school_id'],
                'child_name' => $validated['child_name'],
                'child_gender' => $validated['child_gender'],
                'email' => $validated['email'],
                'picture_path' => $validated['picture_path'],
            ]);

            // Attach parents if provided
            if (isset($validated['parents'])) {
                $child->parents()->attach($validated['parents'], ['active' => true]);
            }

            DB::commit();

            return redirect()
                ->route('childs.index')
                ->with('success', 'Child created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create child: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Childs $child)
    {
        $child->load([
            'schoolsinstitution',
            'parents',
            'attendance',
            'nonattendance',
            'classes',
            'reminders',
            'emergencyContacts'
        ]);

        return view('childs.show', compact('child'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Childs $child)
    {
        $schools = Schoolsinstitutions::all();
        $parents = Parents::all();
        $child->load('parents');

        return view('childs.edit', compact('child', 'schools', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Childs $child)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools_institutions,id',
            'child_name' => 'required|string|max:255',
            'child_gender' => 'required|string|max:15',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('childs', 'email')->ignore($child->id),
            ],
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parents' => 'nullable|array',
            'parents.*' => 'exists:parents,id',
        ]);

        DB::beginTransaction();
        try {
            // Handle picture upload
            if ($request->hasFile('picture')) {
                // Delete old picture if exists
                if ($child->picture_path && $child->picture_path !== 'default.png') {
                    Storage::disk('public')->delete($child->picture_path);
                }
                $picturePath = $request->file('picture')->store('childs', 'public');
                $validated['picture_path'] = $picturePath;
            }

            $child->update([
                'school_id' => $validated['school_id'],
                'child_name' => $validated['child_name'],
                'child_gender' => $validated['child_gender'],
                'email' => $validated['email'],
                'picture_path' => $validated['picture_path'] ?? $child->picture_path,
            ]);

            // Sync parents if provided
            if (isset($validated['parents'])) {
                $syncData = [];
                foreach ($validated['parents'] as $parentId) {
                    $syncData[$parentId] = ['active' => true];
                }
                $child->parents()->sync($syncData);
            }

            DB::commit();

            return redirect()
                ->route('childs.index')
                ->with('success', 'Child updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update child: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Childs $child)
    {
        DB::beginTransaction();
        try {
            // Delete picture if exists
            if ($child->picture_path && $child->picture_path !== 'default.png') {
                Storage::disk('public')->delete($child->picture_path);
            }

            // Detach all parent relationships
            $child->parents()->detach();

            $child->delete();

            DB::commit();

            return redirect()
                ->route('childs.index')
                ->with('success', 'Child deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to delete child: ' . $e->getMessage());
        }
    }
}
