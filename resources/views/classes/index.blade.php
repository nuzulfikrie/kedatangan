@extends('layouts.app-extended')
@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between mb-6"><div><h1 class="text-3xl font-bold">Classes</h1></div><a href="{{ route('classes.create') }}" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Add Class</a></div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase bg-gray-50"><tr><th class="px-6 py-3">Class Name</th><th class="px-6 py-3">School</th><th class="px-6 py-3">Child</th><th class="px-6 py-3">Actions</th></tr></thead>
            <tbody>
                @forelse($classes as $class)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $class->class_name }}</td>
                    <td class="px-6 py-4">{{ $class->schoolsinstitution->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $class->child->child_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4"><a href="{{ route('classes.edit', $class) }}" class="text-green-600 hover:underline">Edit</a></td>
                </tr>
                @empty<tr><td colspan="4" class="px-6 py-4 text-center">No classes found.</td></tr>@endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $classes->links() }}</div>
    </div>
</div>
@endsection
