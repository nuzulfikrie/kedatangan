<div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
    <div class="flex-row items-center justify-between p-4 space-y-3 sm:flex sm:space-y-0 sm:space-x-4">
        <div>
            <h5 class="mr-3 font-semibold dark:text-white">{{__('List of schools')}}</h5>
            <p class="text-gray-500 dark:text-gray-400">Manage all your existing schools or add a new one</p>
        </div>

        <a href="{{ route('schools_admin.schools.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Add Schools
        </a>
    </div>

    <!-- Search and Filter Section -->
    <div class="p-4 bg-white dark:bg-gray-800">
        <form action="{{ route('schools_admin.schools.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text"
                    name="query"
                    value="{{ $query }}"
                    placeholder="Search schools..."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex-none">
                <select name="filter"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Schools</option>
                    <option value="active" {{ $filter === 'active' ? 'selected' : '' }}>Active Schools</option>
                    <option value="inactive" {{ $filter === 'inactive' ? 'selected' : '' }}>Inactive Schools</option>
                </select>
            </div>

            <div class="flex-none">
                <button type="submit"
                    class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Address
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Phone Number
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        School Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        School Website
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Created At
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Updated At
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Record Active
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($schools as $school)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $school->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $school->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $school->address }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $school->phone_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $school->school_email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $school->school_website }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $school->created_at }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $school->updated_at }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $school->record_active ? 'true' : 'false' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('schools_admin.schools.edit', $school->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <a href="{{ route('schools_admin.schools.show', $school->id) }}" class="text-green-600 hover:text-green-900">View</a>

                        <form action="{{ route('schools_admin.schools.delete') }}" method="POST" class="inline-block">
                            <input type="hidden" name="id" value="{{$school->id}}">
                            @csrf
                            @method('POST')
                            <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                        No schools found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4">
        {{ $schools->withQueryString()->links() }}
    </div>
</div>