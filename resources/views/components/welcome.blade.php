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

<!-- if user is father or mother get all childrens and render link to child profiles -->
@if ($role == 'super_admin')
<x-super-admin-display :role="$role" />
@endif

@if ($role == 'father' || $role == 'mother')
<div class="bg-gray-200 dark:bg-gray-800 dark:border-gray-700 bg-opacity-25 p-6 lg:p-8">
    <x-parent-child-display :role="$role" />
</div>
@endif
<!-- conditional end -->

<div class="bg-gray-200 dark:bg-gray-800 dark:border-gray-700 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">

</div>