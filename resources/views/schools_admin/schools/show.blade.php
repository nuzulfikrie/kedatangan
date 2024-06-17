<x-app-layout>

<div class="container mx-auto py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-200 border-b">
            <h2 class="text-2xl font-bold text-gray-800">{{ $school->name }}</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">{{ $school->name }}</h3>
                <p class="mt-2 text-gray-600">{{ $school->address }}</p>
                <p class="mt-2 text-gray-600">{{ $school->phone_number }}</p>
                <p class="mt-2 text-gray-600">{{ $school->school_email }}</p>
                <a href="{{ $school->school_website }}" class="mt-2 text-blue-500 hover:text-blue-700 block">{{ $school->school_website }}</a>
                <div class="mt-4 flex space-x-4">
                    <a href="{{ route('schools_admin.schools.edit', $school->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                    <form action="{{ route('schools_admin.schools.delete') }}" method="POST" class="inline-block">
                        <input type="hidden" name="id" value="{{$school->id}}">
                        @csrf
                        @method('POST')
                        <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
