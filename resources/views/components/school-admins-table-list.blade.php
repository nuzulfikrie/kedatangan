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
        @foreach($schools as $school)
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
                    <a href="{{ route('school_admin.schools.edit', $school->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    <a href="{{ route('school_admin.schools.show', $school->id) }}" class="text-green-600 hover:text-green-900">View</a>
                    <form action="{{ route('school_admin.schools.delete', $school->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>