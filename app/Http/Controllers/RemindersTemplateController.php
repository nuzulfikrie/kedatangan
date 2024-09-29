<?php

namespace App\Http\Controllers;

use App\Models\RemindersTemplate;
use App\Models\SchoolsInstitutions;
use Illuminate\Http\Request;

class RemindersTemplateController extends Controller
{
    public function index()
    {
        $templates = RemindersTemplate::with('admin', 'school')->paginate(15);
        return view('reminders_template.index', compact('templates'));
    }

    public function create()
    {
        $schools = SchoolsInstitutions::all();
        return view('reminders_template.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'reminder' => 'required|string|max:255',
            'active' => 'boolean',
            'admin_id' => 'required|exists:users,id',
            'school_id' => 'required|exists:schools_institutions,id',
            'language' => 'required|string|max:10',
            'channel' => 'required|string|max:10',
        ]);

        RemindersTemplate::create($validatedData);

        return redirect()->route('reminders_template.index')->with('success', 'Reminder template created successfully.');
    }

    public function show(RemindersTemplate $remindersTemplate)
    {
        return view('reminders_template.show', compact('remindersTemplate'));
    }

    public function edit(RemindersTemplate $remindersTemplate)
    {
        $schools = SchoolsInstitutions::all();
        return view('reminders_template.edit', compact('remindersTemplate', 'schools'));
    }

    public function update(Request $request, RemindersTemplate $remindersTemplate)
    {
        $validatedData = $request->validate([
            'reminder' => 'required|string|max:255',
            'active' => 'boolean',
            'admin_id' => 'required|exists:users,id',
            'school_id' => 'required|exists:schools_institutions,id',
            'language' => 'required|string|max:10',
            'channel' => 'required|string|max:10',
        ]);

        $remindersTemplate->update($validatedData);

        return redirect()->route('reminders_template.index')->with('success', 'Reminder template updated successfully.');
    }

    public function destroy(RemindersTemplate $remindersTemplate)
    {
        $remindersTemplate->delete();
        return redirect()->route('reminders_template.index')->with('success', 'Reminder template deleted successfully.');
    }
}
