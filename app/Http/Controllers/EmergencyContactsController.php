<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContacts;
use App\Models\Childs;
use Illuminate\Http\Request;

class EmergencyContactsController extends Controller
{
    public function index()
    {
        $emergencyContacts = EmergencyContacts::with('child', 'parent')->paginate(15);
        return view('emergency_contacts.index', compact('emergencyContacts'));
    }

    public function create()
    {
        $children = Childs::all();
        return view('emergency_contacts.create', compact('children'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'child_id' => 'required|exists:childs,id',
            'parent_id' => 'required|exists:parents,id',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'relationship' => 'required|string|max:50',
            'picture_path' => 'nullable|string',
            'email' => 'required|email|unique:emergency_contacts,email',
            'address' => 'required|string|max:500',
        ]);

        EmergencyContacts::create($validatedData);

        return redirect()->route('emergency_contacts.index')->with('success', 'Emergency contact created successfully.');
    }

    public function show(EmergencyContacts $emergencyContact)
    {
        return view('emergency_contacts.show', compact('emergencyContact'));
    }

    public function edit(EmergencyContacts $emergencyContact)
    {
        $children = Childs::all();
        return view('emergency_contacts.edit', compact('emergencyContact', 'children'));
    }

    public function update(Request $request, EmergencyContacts $emergencyContact)
    {
        $validatedData = $request->validate([
            'child_id' => 'required|exists:childs,id',
            'parent_id' => 'required|exists:parents,id',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'relationship' => 'required|string|max:50',
            'picture_path' => 'nullable|string',
            'email' => 'required|email|unique:emergency_contacts,email,' . $emergencyContact->id,
            'address' => 'required|string|max:500',
        ]);

        $emergencyContact->update($validatedData);

        return redirect()->route('emergency_contacts.index')->with('success', 'Emergency contact updated successfully.');
    }

    public function destroy(EmergencyContacts $emergencyContact)
    {
        $emergencyContact->delete();
        return redirect()->route('emergency_contacts.index')->with('success', 'Emergency contact deleted successfully.');
    }
}
