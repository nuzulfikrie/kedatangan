@props(['role'])

@if ($role == 'father' || $role == 'mother')
@php
$parent = \App\Models\Parents::where('user_id', Auth::user()->id)->first();
@endphp

@if ($parent)
@php
$children = \App\Models\ChildParents::where('parent_id', $parent->id)->with('child')->get();
@endphp

@if ($children->count() > 0)
<h2 class="mb-6 text-xl font-semibold text-gray-900 dark:text-white">Your Children:</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
    @foreach ($children as $child)
    <div>
        <a href="{{ route('childs.profile', $child->child->id) }}"
            class="flex items-center justify-center w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            {{ $child->child->child_name }}
        </a>
    </div>
    @endforeach
</div>
@else
<p class="mt-4 text-gray-500 dark:text-gray-400">No children registered.</p>
@endif
@else
<div class="flex justify-center">
    <a href="{{ route('parents.create') }}"
        class="mt-2 inline-block bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
        No parent records found, Register as Parent
    </a>
</div>
@endif
@endif