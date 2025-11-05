@extends('layouts.app-extended')

@section('title', 'Edit Teacher - Kedatangan')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="teacherForm()">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Teacher</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update teacher information</p>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="teacher_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Teacher Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="teacher_name" name="teacher_name" value="{{ old('teacher_name', $teacher->teacher_name) }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                       required>
                @error('teacher_name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="teacher_specialization" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Specialization <span class="text-red-500">*</span>
                </label>
                <input type="text" id="teacher_specialization" name="teacher_specialization" value="{{ old('teacher_specialization', $teacher->teacher_specialization) }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                       required>
                @error('teacher_specialization')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="user_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Associated User <span class="text-red-500">*</span>
                </label>
                <select id="user_id" name="user_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                        required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $teacher->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="school_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    School <span class="text-red-500">*</span>
                </label>
                <select id="school_id" name="school_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                        required>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id', $teacher->school_id) == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Profile Picture
                </label>
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <img :src="previewUrl || '{{ $teacher->picture_path ? Storage::url($teacher->picture_path) : "https://ui-avatars.com/api/?name=".urlencode($teacher->teacher_name) }}'"
                             class="w-24 h-24 rounded-full object-cover border-2 border-gray-300">
                    </div>
                    <div class="flex-1">
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600"
                               id="picture" name="picture" type="file" accept="image/*"
                               @change="previewImage($event)">
                        <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                @error('picture')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Update Teacher
                </button>
                <a href="{{ route('teachers.index') }}"
                   class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function teacherForm() {
    return {
        previewUrl: null,
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => { this.previewUrl = e.target.result; };
                reader.readAsDataURL(file);
            }
        }
    }
}
</script>
@endpush
