@extends('layouts.app-extended')
@section('content')
<div class="max-w-7xl mx-auto px-4" x-data="{showDeleteModal:false,deleteId:null}">
    <div class="flex justify-between mb-6"><div><h1 class="text-3xl font-bold">Children</h1><p class="text-sm text-gray-500">Manage student information</p></div><a href="{{ route('childs.create') }}" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Add Child</a></div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase bg-gray-50"><tr><th class="px-6 py-3">Photo</th><th class="px-6 py-3">Name</th><th class="px-6 py-3">Gender</th><th class="px-6 py-3">School</th><th class="px-6 py-3">Parents</th><th class="px-6 py-3">Actions</th></tr></thead>
            <tbody>
                @forelse($childs as $child)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4"><img class="w-10 h-10 rounded-full" src="{{ $child->picture_path ? Storage::url($child->picture_path) : 'https://ui-avatars.com/api/?name='.urlencode($child->child_name) }}"></td>
                    <td class="px-6 py-4 font-medium">{{ $child->child_name }}</td>
                    <td class="px-6 py-4">{{ $child->child_gender }}</td>
                    <td class="px-6 py-4">{{ $child->schoolsinstitution->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $child->parents->count() }}</td>
                    <td class="px-6 py-4"><a href="{{ route('childs.show', $child) }}" class="text-blue-600 hover:underline">View</a> <a href="{{ route('childs.edit', $child) }}" class="text-green-600 hover:underline ml-2">Edit</a></td>
                </tr>
                @empty<tr><td colspan="6" class="px-6 py-4 text-center">No children found.</td></tr>@endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $childs->links() }}</div>
    </div>
</div>
@endsection
