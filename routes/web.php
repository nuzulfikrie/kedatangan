<?php

use App\Http\Controllers\DashboardController;
use App\Models\Schoolsinstitutions;
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

    Route::get('/schools', function () {
        return view('admin.schools_institutions.index');
    })->name('admin.schools_institutions.index');
});

// School admin prefix routes
Route::prefix('schools_admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('schools_admin.dashboard');

    Route::get('/schools/index/{id}', function (int $schoolAdminId) {
        try {
            $schoolAdmins = Schoolsadmin::where('school_admin_id', $schoolAdminId)->get();

            $hasSchool = $schoolAdmins->isNotEmpty();
            $schools = $hasSchool ? Schoolsinstitutions::whereIn('id', $schoolAdmins->pluck('school_id'))->get() : null;

            return view('schools_admin.schools.index', compact('schools', 'hasSchool'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        }
    })->name('schools_admin.schools.index');

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

    Route::post('/schools/update', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'update'])
        ->name('schools_admin.schools.update');

    Route::delete('/schools/delete/{id}', [App\Http\Controllers\SchoolAdmin\SchoolsController::class, 'delete'])
        ->name('schools_admin.schools.delete');
});
