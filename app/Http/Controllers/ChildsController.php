<?php

namespace App\Http\Controllers;

use App\Models\Childs;
use App\Models\SchoolsInstitutions;
use App\Models\Parents;
use App\Models\ChildParents;
use App\Models\EmergencyContacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChildsController extends Controller
{
    public function index()
    {
        $children = Childs::with('school')->paginate(15);
        return view('childs.index', compact('children'));
    }

    public function create()
    {
        $schools = SchoolsInstitutions::all();
        $parents = Parents::all();
        return view('childs.create', compact('schools', 'parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'child_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'child_gender' => 'required|in:male,female',
            'email' => 'required|email|unique:childs,email',
            'picture_path' => 'nullable|string',
            'school_id' => 'required|exists:schools_institutions,id',
            'parent_ids' => 'required|array',
            'parent_ids.*' => 'exists:parents,id',
        ]);

        DB::transaction(function () use ($request) {
            $child = Childs::createRecord($request->all());

            foreach ($request->parent_ids as $parentId) {
                ChildParents::create([
                    'child_id' => $child->id,
                    'parent_id' => $parentId,
                ]);
            }

            // Handle emergency contacts if provided
            if ($request->has('emergency_contacts')) {
                foreach ($request->emergency_contacts as $contact) {
                    EmergencyContacts::create([
                        'child_id' => $child->id,
                        'parent_id' => $contact['parent_id'],
                        'name' => $contact['name'],
                        'phone_number' => $contact['phone_number'],
                        'relationship' => $contact['relationship'],
                    ]);
                }
            }
        });

        return redirect()->route('childs.index')->with('success', 'Child record created successfully.');
    }

    public function show(Childs $child)
    {
        $child->load('school', 'childParents.parent', 'emergencyContacts');
        return view('childs.show', compact('child'));
    }

    public function edit(Childs $child)
    {
        $schools = SchoolsInstitutions::all();
        $parents = Parents::all();
        $child->load('childParents.parent', 'emergencyContacts');
        return view('childs.edit', compact('child', 'schools', 'parents'));
    }

    public function update(Request $request, Childs $child)
    {
        $request->validate([
            'child_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'child_gender' => 'required|in:male,female',
            'email' => 'required|email|unique:childs,email,' . $child->id,
            'picture_path' => 'nullable|string',
            'school_id' => 'required|exists:schools_institutions,id',
            'parent_ids' => 'required|array',
            'parent_ids.*' => 'exists:parents,id',
        ]);

        DB::transaction(function () use ($request, $child) {
            Childs::updateRecord($child->id, $request->all());

            // Update child-parent relationships
            $child->childParents()->delete();
            foreach ($request->parent_ids as $parentId) {
                ChildParents::create([
                    'child_id' => $child->id,
                    'parent_id' => $parentId,
                ]);
            }

            // Update emergency contacts
            $child->emergencyContacts()->delete();
            if ($request->has('emergency_contacts')) {
                foreach ($request->emergency_contacts as $contact) {
                    EmergencyContacts::create([
                        'child_id' => $child->id,
                        'parent_id' => $contact['parent_id'],
                        'name' => $contact['name'],
                        'phone_number' => $contact['phone_number'],
                        'relationship' => $contact['relationship'],
                    ]);
                }
            }
        });

        return redirect()->route('childs.index')->with('success', 'Child record updated successfully.');
    }

    public function destroy(Childs $child)
    {
        $result = Childs::deleteChild($child->id);

        if ($result['code'] === 200) {
            return redirect()->route('childs.index')->with('success', $result['message']);
        } else {
            return redirect()->route('childs.index')->with('error', $result['message']);
        }
    }

    public function attendanceHistory(Childs $child)
    {
        $attendances = $child->attendance()->orderBy('date', 'desc')->paginate(15);
        $nonAttendances = $child->nonattendace()->orderBy('date', 'desc')->paginate(15);
        $unknowns = $child->unknowns()->orderBy('date', 'desc')->paginate(15);

        return view('childs.attendance_history', compact('child', 'attendances', 'nonAttendances', 'unknowns'));
    }
}
