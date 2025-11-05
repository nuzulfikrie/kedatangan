@extends('layouts.app-extended')
@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="flex justify-between mb-6"><h1 class="text-3xl font-bold">Child Details</h1><a href="{{ route('childs.edit', $child) }}" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Edit</a></div>
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center gap-6 pb-6 border-b"><img class="w-32 h-32 rounded-full" src="{{ $child->picture_path ? Storage::url($child->picture_path) : 'https://ui-avatars.com/api/?name='.urlencode($child->child_name) }}"><div><h2 class="text-2xl font-bold">{{ $child->child_name }}</h2><p class="text-gray-600">{{ $child->email }}</p></div></div>
        <div class="grid grid-cols-2 gap-6 mt-6">
            <div><h3 class="text-sm font-medium text-gray-500">Gender</h3><p class="mt-1 text-lg">{{ $child->child_gender }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">School</h3><p class="mt-1 text-lg">{{ $child->schoolsinstitution->name ?? 'N/A' }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">Parents</h3><p class="mt-1 text-lg">{{ $child->parents->pluck('parent_name')->join(', ') }}</p></div>
            <div><h3 class="text-sm font-medium text-gray-500">Created</h3><p class="mt-1 text-lg">{{ $child->created_at->format('M d, Y') }}</p></div>
        </div>
    </div>
</div>
@endsection
