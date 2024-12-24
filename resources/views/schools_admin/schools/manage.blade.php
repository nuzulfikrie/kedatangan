<x-app-layout>

    <div class="container mx-auto py-8">
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="px-6 py-4 bg-gray-200 border-b">
                <h2 class="text-2xl font-bold text-gray-800">{{ $school->name }}</h2>
            </div>
            <div>
                <div class="relative overflow-x-auto shadow-md">
                    <div class="w-full p-4 border border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700">
                        <div class="grid grid-cols-3">

                            <div class="col-span-2 sm:col-span-1">
                                <!--- add child button here --->
                                <a href="{{ route('childs.teacher-create', $school->id) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Child</a>
                                <!--- add child button here --->
                            </div>


                        </div>
                    </div>


                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Child Name</th>
                                <th scope="col" class="px-6 py-3">Date of Birth</th>
                                <th scope="col" class="px-6 py-3">Gender</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Picture</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($childs as $child)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $child->child_name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($child->dob)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $child->child_gender }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $child->email }}
                                </td>
                                <td class="px-6 py-4">
                                    <img src="{{ asset($child->picture_path) }}" alt="{{ $child->child_name }}" class="w-10 h-10 rounded-full">
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('attendance.create', ['schoolId' => $school->id, 'childId' => $child->id]) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Manage Attendance</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>