<?php
$hasChild = $children->count() > 0;
?>

<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 flex justify-between items-center">
            <div>
                <h5 class="mb-3 text-base font-semibold text-gray-900 md:text-xl dark:text-white">
                    {{'Manage your child'}} <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $children->count() }})</span>
                </h5>
                <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    {{'Here you can manage your child'}}.
                </p>
            </div>
            @if($hasChild)

            <!-- button to parent.create_child -->
            <a href="{{ route('parents.create_child') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{'Add Child'}}
            </a>
            @else

            @endif

        </div>
        <div class="mt-4">
            <?php
            ?>
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <!-- if has child, show flowbite table, column, child name, age, school, edit, view -->
                @if($hasChild)
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{'Avatar'}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{'Child Name'}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{'Age'}}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{'School'}}
                                </th>
                                <!-- avatar , tailwind auto resize to circle 30px by 30px -->

                                <th scope="col" class="px-6 py-3">0
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

                                <td class="px-6 py-4">
                                    <img src="{{ Storage::disk('s3')->url($childParent->child->picture_path)}}" alt="{{ $childParent->child->child_name }}'s Avatar" class="w-8 h-8 rounded-full">
                                </td>
                                <td class="px-6 py-4">
                                    {{ $childParent->child->child_name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $childParent->child->age() }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $childParent->child->school->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('parents.edit_child', ['parent_id' => $parent->id, 'child_id' => $childParent->child->id]) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('childs.profile', $childParent->child->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
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