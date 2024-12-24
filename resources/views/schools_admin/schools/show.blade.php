<x-app-layout>

    <div class="container mx-auto py-8">
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="px-6 py-4 bg-gray-200 border-b">
                <h2 class="text-2xl font-bold text-gray-800">{{ $school->name }}</h2>
            </div>
            <div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700">{{ $school->name }}</h3>
                    <p class="mt-2 text-gray-600">{{ $school->address }}</p>
                    <p class="mt-2 text-gray-600">{{ $school->phone_number }}</p>
                    <p class="mt-2 text-gray-600">{{ $school->school_email }}</p>
                    <a href="{{ $school->school_website }}" class="mt-2 text-blue-500 hover:text-blue-700 block">{{ $school->school_website }}</a>
                    <div class="mt-4 flex space-x-4">
                        <a href="{{ route('schools_admin.schools.manage', $school->id) }}" class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800">Manage</a>

                        <a href="{{ route('schools_admin.schools.edit', $school->id) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit</a>
                        <form action="{{ route('schools_admin.schools.delete') }}" method="POST" class="inline-block">
                            <input type="hidden" name="id" value="{{$school->id}}">
                            @csrf
                            @method('POST')
                            <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>