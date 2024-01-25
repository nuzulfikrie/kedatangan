<?php

use App\Http\Controllers\DashboardController;
use App\Models\SchoolsInstitutions;
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


## for jetstream
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//check if user logged in
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    //use layout for guest

    return view('welcome')->layout('layouts.guest');
});

Route::get(
    '/about',
    function () {
        return view('about.index');
    }
)->name('about');

// admin prefix route
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard');
    //schools
    Route::get('/schools', function () {
        return view('admin.schools_institutions.index');
    })->name('admin.schools_institutions.index');
});

// shool admin prefix route
Route::prefix('school_admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('school_admin.dashboard');
    //schools
    Route::get(
        '/schools',
        function (SchoolsInstitutions $schoolsInstitutions) {
            return view('school_admin.schools.index');
        }
    )->name('school_admin.schools.index')->middleware('can:index,viewAny');

    Route::get(
        '/schools/create',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'create',
        ]
    )->name('school_admin.schools.create')->middleware('can:create,App\Models\Schoolsinstitutions');

    Route::post(
        '/schools/store',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'store',
        ]
    )->name('school_admin.schools.store')->middleware('can:store,App\Models\Schoolsinstitutions');

    Route::get(
        '/schools/edit/{id}',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'edit',
        ]
    )->name('school_admin.schools.edit');

    Route::post(
        '/schools/update',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'update',
        ]
    )->name('school_admin.schools.update');

    Route::delete(
        '/schools/delete/{id}',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'delete',
        ]
    )->name('school_admin.schools.delete');
});













## login get and post
// Route::get('/login', [LoginController::class, 'index'])->name('login');
// Route::post('/login', [LoginController::class, 'store']);

// ## register get and post
// Route::get('/register', [RegisterController::class, 'index'])->name('register');
// Route::post('/register', [RegisterController::class, 'store']);

 ## -- trick , you can use laravel jetstream.