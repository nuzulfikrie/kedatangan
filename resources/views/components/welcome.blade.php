<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
//current controller
$controller = Route::currentRouteAction();
//get controller name
$controllerName = explode('@', $controller)[0];
//get action name

$userName = Auth::user()->name;
$role = Auth::user()->role;
$textMain = 'This site built for teachers , parents and students.';

?>

<div class="p-6 lg:p-8 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <x-application-logo class="block h-12 w-auto" />

    <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-purple-500">
        Welcome {{ $userName }}
    </h1>

    <p class="mt-6 text-gray-500 leading-relaxed dark:text-white leading-relaxed">
        {{ $textMain }}
    </p>
</div>

<!-- conditional -->
<!-- if user is father or mother get all childrens and render link to child profiles -->
<div class="bg-gray-200 dark:bg-gray-800 dark:border-gray-700 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">

    <?php if ($role == 'father' || $role == 'mother'): ?>
        <?php

        $parent = \App\Models\Parents::where('user_id', Auth::user()->id)->first();

        if ($parent) {


            $children = \App\Models\Childs::where('parent_id', $parent->id)->get();

            if ($children->count() > 0) {
                echo '<h2 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Your Children:</h2>';
                echo '<ul class="mt-2 list-disc list-inside text-gray-500 dark:text-gray-400">';
                foreach ($children as $child) {
                    echo '<li><a href="' . route('child.profile', $child->id) . '" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">' . e($child->name) . '</a></li>';
                }
                echo '</ul>';
            } else {
                echo '<p class="mt-4 text-gray-500 dark:text-gray-400">No children registered.</p>';
            }
        } else {
            echo '<div class="flex justify-center">';
            echo '<a href="' . route('parents.create') . '" class="mt-2 inline-block bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">No parent  records found,Register as Parent</a>';
            echo '</div>';
        }
        ?>

    <?php endif; ?>
</div>
<!-- conditional end -->
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

<div class="bg-gray-200 dark:bg-gray-800 dark:border-gray-700 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
    <div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-6 h-6 stroke-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            <h2 class="ml-3 text-xl font-semibold text-gray-900 dark:text-white">
                <a href="https://laravel.com/docs">Documentation</a>
            </h2>
        </div>

        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
            Laravel has wonderful documentation covering every aspect of the framework. Whether you're new to the framework or have previous experience, we recommend reading all of the documentation from beginning to end.
        </p>

        <p class="mt-4 text-sm">
            <a href="https://laravel.com/docs" class="inline-flex items-center font-semibold text-indigo-700">
                Explore the documentation

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ml-1 w-5 h-5 fill-indigo-500">
                    <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                </svg>
            </a>
        </p>
    </div>
</div>