<?php

// Possible races are all major races in Malaysia
$possibleRaces = [
    'Malay',
    'Chinese',
    'Indian',
    'Others'
];

?>
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Edit Parent Details</h2>

        <form action="{{ route('parents.update', $parent->id) }}" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto">
            @csrf
            @method('PUT')

            {{-- Display Error Messages --}}
            @if($errors->any())
            <div class="mb-6 bg-red-100 text-red-700 px-4 py-3 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mb-6">
                <label for="parent_name" class="block mb-2 text-sm font-medium text-gray-900">Parent Name</label>
                <input type="text"
                    id="parent_name"
                    name="parent_name"
                    value="{{ old('parent_name', $parent->parent_name) }}"
                    placeholder="Enter parent name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            <div class="mb-6">
                <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900">Phone Number</label>
                <input type="tel"
                    id="phone_number"
                    name="phone_number"
                    value="{{ old('phone_number', $parent->phone_number) }}"
                    placeholder="Enter phone number"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $parent->email) }}"
                    placeholder="Enter email address"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    required>
            </div>

            <div class="mb-6">
                <label for="race" class="block mb-2 text-sm font-medium text-gray-900">Race (Optional)</label>
                <select id="race"
                    name="race"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Select a race</option>
                    @foreach($possibleRaces as $race)
                    <option value="{{ $race }}"
                        {{ old('race', $parent->race) === $race ? 'selected' : '' }}>
                        {{ $race }}
                    </option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="user_id" value="{{ $parent->user_id }}">

            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900" for="picture">Upload New Picture</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                    id="picture"
                    name="picture"
                    type="file">

                @if($parent->picture_path)
                <p class="mt-2 text-sm text-gray-600">Current Picture:</p>
                <img src="{{ Storage::disk('s3')->url($parent->picture_path) }}" alt="Current Picture" class="mt-2 w-24 h-24 rounded-lg">
                @endif
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Save Changes
                </button>
                <button type="reset" class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Reset
                </button>
            </div>
        </form>
    </div>
</x-app-layout>