@extends('layouts.app-extended')
@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between mb-6"><div><h1 class="text-3xl font-bold">Schools & Institutions</h1></div><a href="{{ route('schools.create') }}" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Add School</a></div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($schools as $school)
        <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition">
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $school->name }}</h3>
            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($school->address, 60) }}</p>
            <div class="flex justify-between text-sm text-gray-500 mb-4">
                <div><span class="font-medium">Teachers:</span> {{ $school->teachers_count }}</div>
                <div><span class="font-medium">Students:</span> {{ $school->childs_count }}</div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('schools.dashboard', $school) }}" class="flex-1 text-center text-white bg-blue-600 font-medium rounded-lg text-sm px-4 py-2">Dashboard</a>
                <a href="{{ route('schools.edit', $school) }}" class="text-green-600 hover:text-green-800 p-2">Edit</a>
                <a href="{{ route('schools.show', $school) }}" class="text-blue-600 hover:text-blue-800 p-2">View</a>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12"><p class="text-gray-500">No schools found.</p></div>
        @endforelse
    </div>
    <div class="mt-6">{{ $schools->links() }}</div>
</div>
@endsection
