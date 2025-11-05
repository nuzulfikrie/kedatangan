@extends('layouts.app-extended')
@section('title', 'Parents - Kedatangan')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ showDeleteModal: false, deleteId: null }">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Parents</h1>
            <p class="mt-1 text-sm text-gray-500">Manage parent information</p>
        </div>
        <a href="{{ route('parents.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
            Add Parent
        </a>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Photo</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Phone</th>
                    <th class="px-6 py-3">Children</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parents as $parent)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <img class="w-10 h-10 rounded-full" src="{{ $parent->picture_path ? Storage::url($parent->picture_path) : 'https://ui-avatars.com/api/?name='.urlencode($parent->parent_name) }}">
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $parent->parent_name }}</td>
                    <td class="px-6 py-4">{{ $parent->email }}</td>
                    <td class="px-6 py-4">{{ $parent->phone_number }}</td>
                    <td class="px-6 py-4">{{ $parent->childs->count() }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('parents.show', $parent) }}" class="text-blue-600 hover:underline">View</a>
                        <a href="{{ route('parents.edit', $parent) }}" class="text-green-600 hover:underline ml-2">Edit</a>
                        <button @click="deleteId = {{ $parent->id }}; showDeleteModal = true" class="text-red-600 hover:underline ml-2">Delete</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-4 text-center">No parents found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $parents->links() }}</div>
    </div>
</div>
@endsection
