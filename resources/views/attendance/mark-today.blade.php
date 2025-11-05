@extends('layouts.app-extended')
@section('content')
<div class="max-w-6xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Mark Attendance for Today</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <div class="mb-4 p-4 bg-blue-50 rounded-lg"><p class="text-blue-800 font-medium">Marking attendance for: {{ now()->format('l, F j, Y') }}</p></div>
            <div class="space-y-4">
                @foreach($schools as $school)
                <div class="border rounded-lg p-4">
                    <h3 class="font-bold text-lg mb-4">{{ $school->name }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($school->childs as $idx => $child)
                        @if(!in_array($child->id, $markedAttendance))
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3 mb-2"><img class="w-8 h-8 rounded-full" src="{{ $child->picture_path ? Storage::url($child->picture_path) : 'https://ui-avatars.com/api/?name='.urlencode($child->child_name) }}"><span class="font-medium">{{ $child->child_name }}</span></div>
                            <input type="hidden" name="attendance[{{ $idx }}][child_id]" value="{{ $child->id }}">
                            <select name="attendance[{{ $idx }}][status]" class="w-full bg-white border text-sm rounded-lg p-2" required><option value="present" selected>Present</option><option value="absent">Absent</option><option value="late">Late</option><option value="excused">Excused</option></select>
                        </div>
                        @endif
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
