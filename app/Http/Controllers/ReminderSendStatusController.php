<?php

namespace App\Http\Controllers;

use App\Models\ReminderSendStatus;
use App\Models\Reminders;
use App\Models\User;
use App\Models\SchoolsInstitutions;
use Illuminate\Http\Request;

class ReminderSendStatusController extends Controller
{
    public function index()
    {
        $statuses = ReminderSendStatus::with('reminder', 'user', 'school')->paginate(15);
        return view('reminder_send_status.index', compact('statuses'));
    }

    public function create()
    {
        $reminders = Reminders::all();
        $users = User::all();
        $schools = SchoolsInstitutions::all();
        return view('reminder_send_status.create', compact('reminders', 'users', 'schools'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'reminder_id' => 'required|exists:reminders,id',
            'user_id' => 'required|exists:users,id',
            'school_id' => 'required|exists:schools_institutions,id',
            'status' => 'required|integer',
        ]);

        ReminderSendStatus::create($validatedData);

        return redirect()->route('reminder_send_status.index')->with('success', 'Reminder send status created successfully.');
    }

    public function show(ReminderSendStatus $reminderSendStatus)
    {
        return view('reminder_send_status.show', compact('reminderSendStatus'));
    }

    public function edit(ReminderSendStatus $reminderSendStatus)
    {
        $reminders = Reminders::all();
        $users = User::all();
        $schools = SchoolsInstitutions::all();
        return view('reminder_send_status.edit', compact('reminderSendStatus', 'reminders', 'users', 'schools'));
    }

    public function update(Request $request, ReminderSendStatus $reminderSendStatus)
    {
        $validatedData = $request->validate([
            'reminder_id' => 'required|exists:reminders,id',
            'user_id' => 'required|exists:users,id',
            'school_id' => 'required|exists:schools_institutions,id',
            'status' => 'required|integer',
        ]);

        $reminderSendStatus->update($validatedData);

        return redirect()->route('reminder_send_status.index')->with('success', 'Reminder send status updated successfully.');
    }

    public function destroy(ReminderSendStatus $reminderSendStatus)
    {
        $reminderSendStatus->delete();
        return redirect()->route('reminder_send_status.index')->with('success', 'Reminder send status deleted successfully.');
    }
}
