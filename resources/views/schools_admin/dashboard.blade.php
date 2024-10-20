<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Current controller
$controller = Route::currentRouteAction();
// Get controller name
$controllerName = explode('@', $controller)[0];
// Get action name
$userId = Auth::user()->id;
$userName = Auth::user()->name;
$textMain = 'This site built for teachers and students. To ';

?>
<x-app-layout>
    <div class="p-6 lg:p-8 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <x-application-logo class="block h-12 w-auto" />

        <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-purple-500">
            Welcome {{ $userName }}
        </h1>

        <p class="mt-6 text-gray-500 leading-relaxed dark:text-white leading-relaxed">
            {{ $textMain }}
        </p>
    </div>

    <div class="bg-gray-200 dark:bg-gray-800 dark:border-gray-700 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
        <div class="bg-gray-300 dark:bg-gray-700 rounded-lg p-6">
            <!-- Empty grid -->
        </div>

        <div class="bg-gray-300 dark:bg-gray-700 rounded-lg p-6">
            <!-- Empty grid -->
        </div>

        <div class="bg-gray-300 dark:bg-gray-700 rounded-lg p-6">
            <!-- Empty grid -->
        </div>

        <div class="bg-gray-300 dark:bg-gray-700 rounded-lg p-6">
            <!-- Empty grid -->
        </div>
    </div>
    @can('create', App\Models\Schoolsinstitutions::class)
    <?php if ($hasSchools === false): ?>
        <div class="bg-gray-200 dark:bg-gray-800 dark:border-gray-700 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-6 h-6 stroke-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <h2 class="ml-3 text-xl font-semibold text-gray-900 dark:text-white">
                        <a href="https://laravel.com/docs">Add School</a>
                    </h2>
                </div>

                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    Looks like you did not have any school , create one.
                </p>

                <p class="mt-4 text-sm">
                    <!-- button add school -->


                    <a href="{{ route('schools_admin.schools.create') }}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Add Schools
                    </a>


                </p>
            </div>
            <div>
                <?php
                $linkWithId = route('schools_admin.schools.index', $userId);
                ?>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-6 h-6 stroke-gray-400">
                        <path stroke-linecap="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <h2 class="ml-3 text-xl font-semibold text-gray-900 dark:text-white">
                        <a href="{{ $linkWithId }}">View all Schools</a>
                    </h2>
                </div>

                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    View all schools.
                </p>

                <p class="mt-4 text-sm">

                    <a href="{{ $linkWithId }}" class="inline-flex items-center font-semibold text-indigo-700">
                        List all schools


                    </a>
                </p>
            </div>
        </div>
    <?php endif; ?>
    @endcan
</x-app-layout>
<script>
    export default {
        data() {
            return {
                show: true,

            }
        },

        watch: {
            '$page.props.flash': {
                handler() {
                    this.show = true
                    setTimeout(() => {
                        this.show = false
                    }, 5000)
                },
                deep: true
            },
        },
    }
</script>