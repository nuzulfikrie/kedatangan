@extends('layouts.app-extended')

@section('title', 'Teacher Details - Kedatangan')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Teacher Details</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">View teacher information</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('teachers.edit', $teacher) }}"
               class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Edit
            </a>
            <a href="{{ route('teachers.index') }}"
               class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100">
                Back
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <!-- Profile Header -->
            <div class="flex items-center gap-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                <img class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700"
                     src="{{ $teacher->picture_path ? Storage::url($teacher->picture_path) : 'https://ui-avatars.com/api/?name='.urlencode($teacher->teacher_name).'&size=128' }}"
                     alt="{{ $teacher->teacher_name }}">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $teacher->teacher_name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $teacher->teacher_specialization }}</p>
                    <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        Active Teacher
                    </span>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">School</h3>
                    <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $teacher->schoolsinstitution->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Specialization</h3>
                    <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $teacher->teacher_specialization }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">User Account</h3>
                    <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $teacher->user->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $teacher->user->email ?? 'N/A' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</h3>
                    <p class="mt-1 text-lg text-gray-900 dark:text-white capitalize">{{ $teacher->user->role ?? 'N/A' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</h3>
                    <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $teacher->created_at->format('M d, Y') }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</h3>
                    <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $teacher->updated_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
