<?php

use App\Http\Controllers\DashboardController;
use App\Models\SchoolsInstitutions;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Schoolsadmin;

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
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

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

//page cheatsheet for tailwind css
Route::get(
    '/cheatsheet',
    [
        App\Http\Controllers\CheatsheetController::class,
        'index',

    ]
);

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
Route::prefix('schools_admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('schools_admin.dashboard');
    //schools
    Route::get(
        '/schools/index/{id}',
        function (int $schoolAdminId) {
            try {
                $schoolAdmins = Schoolsadmin::get()->where('school_admin_id', $schoolAdminId);

                $hasSchool = $schoolAdmins->isNotEmpty();

                if ($hasSchool) {
                    $schoolIds = $schoolAdmins->pluck('school_id');
                    $schools = Schoolsinstitutions::get()
                        ->whereIn('id', $schoolIds);
                } else {
                    $schools = null;
                }


                return view('schools_admin.schools.index', compact('schools', 'hasSchool'));
            } catch (Exception $e) {
                //flash message


                return redirect()->route('dashboard')->with(
                    'error ' . $e->getMessage()

                );
            }
        }
    )->name('schools_admin.schools.index');

    Route::get(
        '/schools/create',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'create',
        ]
    )->name('schools_admin.schools.create')->middleware('can:create,App\Models\Schoolsinstitutions');

    Route::post(
        '/schools/store',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'store',
        ]
    )->name('schools_admin.schools.store')
        // can - 'method in policy' , 'model class'
        ->middleware('can:create,App\Models\Schoolsinstitutions');

    Route::get(
        '/schools/edit/{id}',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'edit',
        ]
    )->name('schools_admin.schools.edit');

    Route::post(
        '/schools/delete/{id}',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'delete',
        ]
    )->name('schools_admin.schools.delete');

    Route::get(
        '/schools/show/{id}',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'show',
        ]
    )->name('schools_admin.schools.show');

    Route::post(
        '/schools/update',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'update',
        ]
    )->name('schools_admin.schools.update');

    Route::delete(
        '/schools/delete/{id}',
        [
            App\Http\Controllers\SchoolAdmin\SchoolsController::class,
            'delete',
        ]
    )->name('schools_admin.schools.delete');
});













## login get and post
// Route::get('/login', [LoginController::class, 'index'])->name('login');
// Route::post('/login', [LoginController::class, 'store']);

// ## register get and post
// Route::get('/register', [RegisterController::class, 'index'])->name('register');
// Route::post('/register', [RegisterController::class, 'store']);

 ## -- trick , you can use laravel jetstream.