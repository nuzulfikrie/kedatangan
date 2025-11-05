<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

## Jetstream Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

## Protected Routes - Require Authentication
Route::middleware(['auth'])->group(function () {

    // Schools/Institutions Routes
    Route::resource('schools', \App\Http\Controllers\SchoolsinstitutionsController::class);
    Route::get('schools/{school}/dashboard', [\App\Http\Controllers\SchoolsinstitutionsController::class, 'dashboard'])
        ->name('schools.dashboard');

    // Teachers Routes
    Route::resource('teachers', \App\Http\Controllers\Teachers::class);

    // Parents Routes
    Route::resource('parents', \App\Http\Controllers\ParentsController::class);

    // Children Routes
    Route::resource('childs', \App\Http\Controllers\ChildsController::class);

    // Classes Routes
    Route::resource('classes', \App\Http\Controllers\ClassesController::class);

    // Attendance Routes
    Route::resource('attendance', \App\Http\Controllers\AttendanceController::class);
    Route::get('attendance-today', [\App\Http\Controllers\AttendanceController::class, 'markToday'])
        ->name('attendance.mark-today');
    Route::get('attendance-report', [\App\Http\Controllers\AttendanceController::class, 'report'])
        ->name('attendance.report');
});

## Authentication routes are handled by Laravel Jetstream/Fortify