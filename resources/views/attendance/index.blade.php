@extends('layouts.app-extended')
@section('content')
<div class="max-w-7xl mx-auto px-4" x-data="{filterDate:''}">
    <div class="flex justify-between mb-6"><div><h1 class="text-3xl font-bold">Attendance Records</h1></div><a href="{{ route('attendance.mark-today') }}" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Mark Attendance Today</a></div>
    <div class="bg-white shadow-md rounded-lg p-4 mb-4"><form class="flex gap-4" method="GET"><input type="date" name="date" value="{{ request('date') }}" class="bg-gray-50 border text-sm rounded-lg p-2.5"><button type="submit" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Filter</button><a href="{{ route('attendance.report') }}" class="py-2.5 px-5 text-sm bg-gray-100 border rounded-lg">View Report</a></form></div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase bg-gray-50"><tr><th class="px-6 py-3">Child</th><th class="px-6 py-3">Date</th><th class="px-6 py-3">Status</th><th class="px-6 py-3">Actions</th></tr></thead>
            <tbody>
                @forelse($attendances as $attendance)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $attendance->child->child_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $attendance->date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusClasses = [
                                'present' => 'bg-green-100 text-green-800',
                                'absent' => 'bg-red-100 text-red-800',
                                'late' => 'bg-yellow-100 text-yellow-800',
                                'excused' => 'bg-blue-100 text-blue-800'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClasses[$attendance->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4"><a href="{{ route('attendance.edit', $attendance) }}" class="text-green-600 hover:underline">Edit</a></td>
                </tr>
                @empty<tr><td colspan="4" class="px-6 py-4 text-center">No attendance records found.</td></tr>@endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $attendances->links() }}</div>
    </div>
</div>
@endsection
