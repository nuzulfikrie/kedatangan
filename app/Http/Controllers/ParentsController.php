<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ParentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parents = Parents::with('user')
            ->paginate(15);

        return view('parents.index', compact('parents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::whereDoesntHave('teacher')
            ->whereDoesntHave('parent')
            ->get();

        return view('parents.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:parents,user_id',
            'parent_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:parents,email',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Handle picture upload
            if ($request->hasFile('picture')) {
                $picturePath = $request->file('picture')->store('parents', 'public');
                $validated['picture_path'] = $picturePath;
            } else {
                $validated['picture_path'] = 'default.png';
            }

            $parent = Parents::create($validated);

            // Update user role
            $user = User::find($validated['user_id']);
            $user->role = 'parent';
            $user->save();

            DB::commit();

            return redirect()
                ->route('parents.index')
                ->with('success', 'Parent created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create parent: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Parents $parent)
    {
        $parent->load(['user', 'childs', 'emergencyContacts']);

        return view('parents.show', compact('parent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parents $parent)
    {
        $users = User::whereDoesntHave('teacher')
            ->whereDoesntHave('parent')
            ->orWhere('id', $parent->user_id)
            ->get();

        return view('parents.edit', compact('parent', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parents $parent)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('parents', 'user_id')->ignore($parent->id),
            ],
            'parent_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('parents', 'email')->ignore($parent->id),
            ],
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Handle picture upload
            if ($request->hasFile('picture')) {
                // Delete old picture if exists
                if ($parent->picture_path && $parent->picture_path !== 'default.png') {
                    Storage::disk('public')->delete($parent->picture_path);
                }
                $picturePath = $request->file('picture')->store('parents', 'public');
                $validated['picture_path'] = $picturePath;
            }

            $parent->update($validated);

            // Update user role if user_id changed
            if ($parent->user_id !== $parent->getOriginal('user_id')) {
                $newUser = User::find($validated['user_id']);
                $newUser->role = 'parent';
                $newUser->save();
            }

            DB::commit();

            return redirect()
                ->route('parents.index')
                ->with('success', 'Parent updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update parent: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parents $parent)
    {
        DB::beginTransaction();
        try {
            // Delete picture if exists
            if ($parent->picture_path && $parent->picture_path !== 'default.png') {
                Storage::disk('public')->delete($parent->picture_path);
            }

            // Reset user role
            $user = User::find($parent->user_id);
            if ($user) {
                $user->role = 'user';
                $user->save();
            }

            $parent->delete();

            DB::commit();

            return redirect()
                ->route('parents.index')
                ->with('success', 'Parent deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to delete parent: ' . $e->getMessage());
        }
    }
}
