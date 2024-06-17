<x-app-layout>
    <div class="container mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-200 border-b">
                <h2 class="text-2xl font-bold text-gray-800">Edit School: {{ $school->name }}</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('schools_admin.schools.update') }}" method="POST">
                    @csrf
                    @method('POST')
                        <input type="hidden" id="id" name="id" value="{{$school->id}}">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                        <input type="text" id="name" name="name" value="{{ $school->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                        <input type="text" id="address" name="address" value="{{ $school->address }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ $school->phone_number }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="school_email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" id="school_email" name="school_email" value="{{ $school->school_email }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="school_website" class="block text-gray-700 text-sm font-bold mb-2">Website:</label>
                        <input type="url" id="school_website" name="school_website" value="{{ $school->school_website }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update
                        </button>
                        <a href="{{ route('schools_admin.schools.index',auth()->user()->id) }}" class="text-gray-700 hover:text-gray-900">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
