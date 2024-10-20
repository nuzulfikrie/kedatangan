<?php

use App\Http\Controllers\DashboardController;
use App\Models\Schoolsinstitutions;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Schoolsadmin;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\AvatarController;

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
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Use layout for guest

    // Use layout for guest
    return view('welcome');
})->name('welcome');



// Dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Additional routes
Route::get('/about', function () {
    return view('about.index');
})->name('about');

Route::get('/cheatsheet', [App\Http\Controllers\CheatsheetController::class, 'index'])->name('cheatsheet.index');

// Admin prefix routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard');

    Route::get('/schools/{user}', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'index'])->name('admin.schools_institutions.index');
});

// School admin prefix routes
Route::prefix('schools_admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('schools_admin.dashboard');

    Route::get('/schools/index/{id?}', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'index'])
        ->name('schools_admin.schools.index');

    Route::get('/schools/create', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'create'])
        ->name('schools_admin.schools.create')
        ->middleware('can:create,App\Models\Schoolsinstitutions');

    Route::post('/schools/store', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'store'])
        ->name('schools_admin.schools.store')
        ->middleware('can:create,App\Models\Schoolsinstitutions');

    Route::get('/schools/edit/{id}', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'edit'])
        ->name('schools_admin.schools.edit');

    Route::post('/schools/delete/{id}', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'delete'])
        ->name('schools_admin.schools.delete');

    Route::get('/schools/show/{id}', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'show'])
        ->name('schools_admin.schools.show');

    Route::get('/schools/edit/{id}', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'edit'])
        ->name('schools_admin.schools.edit');
    Route::post('/schools/update', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'update'])
        ->name('schools_admin.schools.update');

    Route::post('/schools/delete', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'delete'])
        ->name('schools_admin.schools.delete');
});


//parents path

// Parents Controller routes (resource-based)
Route::prefix('parents')->group(function () {
    Route::get('/', [ParentsController::class, 'index'])->name('parents.index');
    Route::get('/create', [ParentsController::class, 'create'])->name('parents.create');
    Route::post('/store', [ParentsController::class, 'store'])->name('parents.store');
    Route::get('/{parent}', [ParentsController::class, 'show'])->name('parents.show');
    Route::get('/{parent}/edit', [ParentsController::class, 'edit'])->name('parents.edit');
    Route::delete('/{parent}', [ParentsController::class, 'destroy'])->name('parents.destroy');
    Route::get('/{parent}/profile', [ParentsController::class, 'profile'])->name('parents.profile');
    Route::put('/parents/{parent}', [ParentsController::class, 'update'])->name('parents.update');
    Route::get('/manage_your_childs/{parent}', [ParentsController::class, 'manageYourChilds'])->name('parents.manage_your_childs');


    // Child management routes
    Route::post('/{parent}/add-child', [ParentsController::class, 'addChild'])->name('parents.addChild');
    Route::delete('/{parent}/remove-child/{child}', [ParentsController::class, 'removeChild'])->name('parents.removeChild');

    // Attendance routes
    Route::get('/{parent}/attendance', [ParentsController::class, 'childrenAttendance'])
        ->name('parents.childrenAttendance');
});

//avatar 
Route::post('/upload-avatar', [AvatarController::class, 'store']);
