@extends('layouts.app-extended')
@section('content')
<div class="max-w-6xl mx-auto px-4" x-data="{date:'{{ $date }}',attendance:{}}">
    <h1 class="text-3xl font-bold mb-6">Mark Attendance</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <div class="mb-6"><label class="block mb-2 font-medium">Date *</label><input type="date" name="date" :value="date" class="bg-gray-50 border text-sm rounded-lg p-2.5" required></div>
            <div class="space-y-4">
                @foreach($schools as $school)
                <div class="border rounded-lg p-4">
                    <h3 class="font-bold text-lg mb-4">{{ $school->name }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($school->childs as $child)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3"><img class="w-10 h-10 rounded-full" src="{{ $child->picture_path ? Storage::url($child->picture_path) : 'https://ui-avatars.com/api/?name='.urlencode($child->child_name) }}"><span>{{ $child->child_name }}</span></div>
                            <input type="hidden" name="attendance[{{ $loop->parent->index }}][child_id]" value="{{ $child->id }}">
                            <select name="attendance[{{ $loop->parent->index }}][status]" class="bg-white border text-sm rounded-lg p-2" required><option value="present">Present</option><option value="absent">Absent</option><option value="late">Late</option><option value="excused">Excused</option></select>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            <div class="flex gap-4 mt-6"><button type="submit" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Save Attendance</button><a href="{{ route('attendance.index') }}" class="py-2.5 px-5 text-sm bg-white border rounded-lg">Cancel</a></div>
        </form>
    </div>
</div>
@endsection
