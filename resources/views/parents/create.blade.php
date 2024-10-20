<?php

//possible races are all major races in malaysia
$possibleRaces = [
    'Malay',
    'Chinese',
    'Indian',
    'Others'
];
?>
<x-app-layout>
    {{ dump(Auth::user()) }}
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Register as Parent</h2>

        <form action="{{ route('parents.store') }}" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto">
            @csrf

            <div class="mb-6">
                <label for="parent_name" class="block mb-2 text-sm font-medium text-gray-900">Parent Name</label>
                <input type="text"
                    id="parent_name"
                    name="parent_name"
                    value="{{ Auth::user()->name }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>

            <div class="mb-6">
                <label for="phone_number"
                    class="block mb-2 text-sm font-medium text-gray-900">

                    Phone Number</label>
                <input type="tel" id="phone_number"
                    name="phone_number"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>

            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email"
                    id="email"
                    name="email"
                    value="{{ Auth::user()->email }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>
            <div class="mb-6">
                <label for="race" class="block mb-2 text-sm font-medium text-gray-900">Race (Optional)</label>
                <select id="race"
                    name="race"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Select a race</option>
                    @foreach($possibleRaces as $race)
                    <option value="{{ $race }}">{{ $race }}</option>
                    @endforeach
                </select>
            </div>
            <!--- input type hidden -->
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">


            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900" for="picture">Upload Picture</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="picture" name="picture" type="file" required>
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Register</button>
        </form>
    </div>
</x-app-layout>