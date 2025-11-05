@extends('layouts.app-extended')
@section('content')
<div class="max-w-6xl mx-auto px-4">
    <div class="flex justify-between mb-6"><h1 class="text-3xl font-bold">{{ $school->name }}</h1><a href="{{ route('schools.edit', $school) }}" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Edit</a></div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Teachers</h3><p class="text-3xl font-bold text-gray-900 mt-2">{{ $school->teachers_count }}</p></div>
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Students</h3><p class="text-3xl font-bold text-gray-900 mt-2">{{ $school->childs_count }}</p></div>
        <div class="bg-white p-6 rounded-lg shadow"><h3 class="text-gray-500 text-sm font-medium">Classes</h3><p class="text-3xl font-bold text-gray-900 mt-2">{{ $school->classes_count }}</p></div>
    </div>
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">School Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><h3 class="text-sm font-medium text-gray-500">Address</h3><p class="mt-1 text-lg">{{ $school->address }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">Phone</h3><p class="mt-1 text-lg">{{ $school->phone_number }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">Email</h3><p class="mt-1 text-lg">{{ $school->school_email }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">Website</h3><p class="mt-1 text-lg"><a href="{{ $school->school_website }}" target="_blank" class="text-blue-600 hover:underline">{{ $school->school_website }}</a></p></div>
        </div>
    </div>
</div>
@endsection
