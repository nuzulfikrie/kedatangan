@extends('layouts.app-extended')
@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="flex justify-between mb-6"><h1 class="text-3xl font-bold">Parent Details</h1><a href="{{ route('parents.edit', $parent) }}" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Edit</a></div>
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center gap-6 pb-6 border-b"><img class="w-32 h-32 rounded-full" src="{{ $parent->picture_path ? Storage::url($parent->picture_path) : 'https://ui-avatars.com/api/?name='.urlencode($parent->parent_name) }}"><div><h2 class="text-2xl font-bold">{{ $parent->parent_name }}</h2><p class="text-gray-600">{{ $parent->email }}</p></div></div>
        <div class="grid grid-cols-2 gap-6 mt-6">
            <div><h3 class="text-sm font-medium text-gray-500">Phone</h3><p class="mt-1 text-lg">{{ $parent->phone_number }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">Email</h3><p class="mt-1 text-lg">{{ $parent->email }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">Children</h3><p class="mt-1 text-lg">{{ $parent->childs->count() }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">Created</h3><p class="mt-1 text-lg">{{ $parent->created_at->format('M d, Y') }}</p></div>
        </div>
    </div>
</div>
@endsection
