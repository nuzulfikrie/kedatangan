<?php
dump('-- parent --');
dump($parent);
dump('-- children --');
dump($children);
?>

<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <h5 class="mb-3 text-base font-semibold text-gray-900 md:text-xl dark:text-white">
                Panel Title
            </h5>
            <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
                This is an empty panel. You can add your content here.
            </p>
            <div class="mt-4">
                <!-- Add your content here -->
            </div>
        </div>
    </div>
</x-app-layout>