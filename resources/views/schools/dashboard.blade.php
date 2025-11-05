@extends('layouts.app-extended')
@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-6"><h1 class="text-3xl font-bold">{{ $school->name }} - Dashboard</h1><p class="text-gray-600">Overview of school statistics and activities</p></div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Total Teachers</h3><p class="text-3xl font-bold text-gray-900 mt-2">{{ $school->teachers_count }}</p></div>
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Total Students</h3><p class="text-3xl font-bold text-gray-900 mt-2">{{ $school->childs_count }}</p></div>
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Total Classes</h3><p class="text-3xl font-bold text-gray-900 mt-2">{{ $school->classes_count }}</p></div>
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Present Today</h3><p class="text-3xl font-bold text-green-600 mt-2">{{ $attendanceToday['present'] ?? 0 }}</p></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow-md rounded-lg p-6"><h2 class="text-xl font-bold mb-4">Today's Attendance</h2><div class="space-y-2"><div class="flex justify-between"><span>Present</span><span class="font-bold text-green-600">{{ $attendanceToday['present'] ?? 0 }}</span></div><div class="flex justify-between"><span>Absent</span><span class="font-bold text-red-600">{{ $attendanceToday['absent'] ?? 0 }}</span></div><div class="flex justify-between"><span>Late</span><span class="font-bold text-yellow-600">{{ $attendanceToday['late'] ?? 0 }}</span></div><div class="flex justify-between"><span>Excused</span><span class="font-bold text-blue-600">{{ $attendanceToday['excused'] ?? 0 }}</span></div></div></div>
        <div class="bg-white shadow-md rounded-lg p-6"><h2 class="text-xl font-bold mb-4">Quick Actions</h2><div class="space-y-2"><a href="{{ route('attendance.mark-today') }}" class="block w-full text-center text-white bg-blue-600 font-medium rounded-lg text-sm px-5 py-2.5">Mark Attendance</a><a href="{{ route('attendance.report') }}?school_id={{ $school->id }}" class="block w-full text-center text-gray-900 bg-white border font-medium rounded-lg text-sm px-5 py-2.5">View Reports</a><a href="{{ route('teachers.index') }}" class="block w-full text-center text-gray-900 bg-white border font-medium rounded-lg text-sm px-5 py-2.5">Manage Teachers</a></div></div>
    </div>
</div>
@endsection
