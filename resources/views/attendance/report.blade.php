@extends('layouts.app-extended')
@section('content')
<div class="max-w-7xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Attendance Report</h1>
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div><label class="block mb-2 text-sm">Start Date</label><input type="date" name="start_date" value="{{ $startDate }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5"></div>
            <div><label class="block mb-2 text-sm">End Date</label><input type="date" name="end_date" value="{{ $endDate }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5"></div>
            <div><label class="block mb-2 text-sm">School</label><select name="school_id" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5"><option value="">All Schools</option>@foreach($schools as $school)<option value="{{$school->id}}">{{$school->name}}</option>@endforeach</select></div>
            <div class="flex items-end"><button type="submit" class="w-full text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Generate Report</button></div>
        </form>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Total Records</h3><p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total'] }}</p></div>
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Present</h3><p class="text-3xl font-bold text-green-600 mt-2">{{ $statistics['present'] }}</p></div>
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Absent</h3><p class="text-3xl font-bold text-red-600 mt-2">{{ $statistics['absent'] }}</p></div>
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Late/Excused</h3><p class="text-3xl font-bold text-yellow-600 mt-2">{{ $statistics['late'] + $statistics['excused'] }}</p></div>
    </div>
</div>
@endsection
