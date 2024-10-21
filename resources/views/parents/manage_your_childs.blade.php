<?php
$hasChild = $children->count() > 0;
?>

<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <h5 class="mb-3 text-base font-semibold text-gray-900 md:text-xl dark:text-white">
                {{'Manage your child'}} <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $children->count() }})</span>
            </h5>
            <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
                {{'Here you can manage your child'}}.
            </p>
        </div>
        <div class="mt-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <!-- if has child, show flowbite table, column, child name, age, school, edit, view -->
                @if($hasChild)
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{'Child Name'}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{'Age'}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{'School'}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{'Edit'}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{'View'}}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($children as $childParent)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $childParent->child->name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $childParent->child->age }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $childParent->child->school }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('edit_child', ['id' => $childParent->child->id]) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('view_child', ['id' => $childParent->child->id]) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @else
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                    <h5 class="mb-3 text-base font-semibold text-gray-900 md:text-xl dark:text-white">
                        {{'No child found'}} <span class="text-sm font-normal text-gray-500 dark:text-gray-400"></span>
                    </h5>
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        {{'Please add a child to manage.'}}
                        <!-- button to parent.create_child -->
                        <a href="{{ route('parents.create_child') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            {{'Add Child'}}
                        </a>
                    </p>
                </div>
                @endif
                </tbody>

            </div>
        </div>
</x-app-layout>