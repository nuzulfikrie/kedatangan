@extends('layouts.app-extended')
@section('content')
<div class="max-w-4xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Edit Attendance</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('attendance.update', $attendance) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div><label class="block mb-2">Child</label><input type="text" value="{{ $attendance->child->child_name }}" class="bg-gray-100 border text-sm rounded-lg block w-full p-2.5" readonly></div>
            <div><label class="block mb-2">Date *</label><input type="date" name="date" value="{{ $attendance->date->format('Y-m-d') }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div><label class="block mb-2">Status *</label><select name="status" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required><option value="present" {{$attendance->status=='present'?'selected':''}}>Present</option><option value="absent" {{$attendance->status=='absent'?'selected':''}}>Absent</option><option value="late" {{$attendance->status=='late'?'selected':''}}>Late</option><option value="excused" {{$attendance->status=='excused'?'selected':''}}>Excused</option></select></div>
            <div class="flex gap-4"><button type="submit" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Update</button><a href="{{ route('attendance.index') }}" class="py-2.5 px-5 text-sm bg-white border rounded-lg">Cancel</a></div>
        </form>
    </div>
</div>
@endsection
