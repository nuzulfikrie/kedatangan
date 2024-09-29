<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\User;
use App\Models\Childs;
use App\Models\ChildParents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ParentsController extends Controller
{
    public function index()
    {
        $parents = Parents::with('user', 'childs')->paginate(15);
        return view('parents.index', compact('parents'));
    }

    public function create()
    {
        return view('parents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:15',
            'picture_path' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->parent_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'parent',
            ]);

            Parents::create([
                'user_id' => $user->id,
                'parent_name' => $request->parent_name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'picture_path' => $request->picture_path,
            ]);
        });

        return redirect()->route('parents.index')->with('success', 'Parent created successfully.');
    }

    public function show(Parents $parent)
    {
        $parent->load('user', 'childs.school');
        return view('parents.show', compact('parent'));
    }

    public function edit(Parents $parent)
    {
        return view('parents.edit', compact('parent'));
    }

    public function update(Request $request, Parents $parent)
    {
        $request->validate([
            'parent_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $parent->user_id,
            'phone_number' => 'required|string|max:15',
            'picture_path' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $parent) {
            $parent->user->update([
                'name' => $request->parent_name,
                'email' => $request->email,
            ]);

            $parent->update([
                'parent_name' => $request->parent_name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'picture_path' => $request->picture_path,
            ]);
        });

        return redirect()->route('parents.index')->with('success', 'Parent updated successfully.');
    }

    public function destroy(Parents $parent)
    {
        $result = Parents::deleteParent($parent->id);

        if ($result['code'] === 200) {
            return redirect()->route('parents.index')->with('success', $result['message']);
        } else {
            return redirect()->route('parents.index')->with('error', $result['message']);
        }
    }

    public function addChild(Request $request, Parents $parent)
    {
        $request->validate([
            'child_id' => 'required|exists:childs,id',
        ]);

        ChildParents::create([
            'child_id' => $request->child_id,
            'parent_id' => $parent->id,
        ]);

        return redirect()->route('parents.show', $parent)->with('success', 'Child added successfully.');
    }

    public function removeChild(Parents $parent, Childs $child)
    {
        ChildParents::where('parent_id', $parent->id)
            ->where('child_id', $child->id)
            ->delete();

        return redirect()->route('parents.show', $parent)->with('success', 'Child removed successfully.');
    }

    public function childrenAttendance(Parents $parent)
    {
        $children = $parent->childs()->with(['attendance' => function ($query) {
            $query->orderBy('date', 'desc')->limit(10);
        }, 'nonattendace' => function ($query) {
            $query->orderBy('date', 'desc')->limit(10);
        }, 'unknowns' => function ($query) {
            $query->orderBy('date', 'desc')->limit(10);
        }])->get();

        return view('parents.children_attendance', compact('parent', 'children'));
    }
}
