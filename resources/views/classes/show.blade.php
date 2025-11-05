@extends('layouts.app-extended')
@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="flex justify-between mb-6"><h1 class="text-3xl font-bold">Class Details</h1><a href="{{ route('classes.edit', $class) }}" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Edit</a></div>
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="grid grid-cols-2 gap-6">
            <div><h3 class="text-sm font-medium text-gray-500">Class Name</h3><p class="mt-1 text-lg">{{ $class->class_name }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">School</h3><p class="mt-1 text-lg">{{ $class->schoolsinstitution->name ?? 'N/A' }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">Child</h3><p class="mt-1 text-lg">{{ $class->child->child_name ?? 'N/A' }}</p></div>
        </div>
    </div>
</div>
@endsection
