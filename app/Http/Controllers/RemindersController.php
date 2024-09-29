<?php

namespace App\Http\Controllers;

use App\Models\Reminders;
use App\Models\Childs;
use Illuminate\Http\Request;

class RemindersController extends Controller
{
    public function index()
    {
        $reminders = Reminders::with('child')->paginate(15);
        return view('reminders.index', compact('reminders'));
    }

    public function create()
    {
        $children = Childs::all();
        return view('reminders.create', compact('children'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'child_id' => 'required|exists:childs,id',
            'date' => 'required|date',
            'reminder' => 'required|string|max:255',
        ]);

        Reminders::create($validatedData);

        return redirect()->route('reminders.index')->with('success', 'Reminder created successfully.');
    }

    public function show(Reminders $reminder)
    {
        return view('reminders.show', compact('reminder'));
    }

    public function edit(Reminders $reminder)
    {
        $children = Childs::all();
        return view('reminders.edit', compact('reminder', 'children'));
    }

    public function update(Request $request, Reminders $reminder)
    {
        $validatedData = $request->validate([
            'child_id' => 'required|exists:childs,id',
            'date' => 'required|date',
            'reminder' => 'required|string|max:255',
        ]);

        $reminder->update($validatedData);

        return redirect()->route('reminders.index')->with('success', 'Reminder updated successfully.');
    }

    public function destroy(Reminders $reminder)
    {
        $reminder->delete();
        return redirect()->route('reminders.index')->with('success', 'Reminder deleted successfully.');
    }
}
