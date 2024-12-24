<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ChildsController;
use App\Http\Controllers\SchoolAdmin\SchoolsinstitutionsController;
use App\Http\Controllers\NotificationSettingsController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Auth;
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
    if (Auth::check()) {
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

    Route::get('/schools/{user}', [App\Http\Controllers\SchoolAdmin\SchoolsinstitutionsController::class, 'index'])->name('admin.schools_institutions.index');
});


//admin prefix routes
Route::prefix('admin')->group(function () {
    Route::get('/users', [UsersController::class, 'index'])
        ->name('admin.users.index');
});
// School admin prefix routes
Route::prefix('schools_admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('schools_admin.dashboard');

    Route::get('/schools/index/{id?}', [App\Http\Controllers\SchoolAdmin\SchoolsinstitutionsController::class, 'index'])
        ->name('schools_admin.schools.index');

    Route::get('/schools/create', [App\Http\Controllers\SchoolAdmin\SchoolsinstitutionsController::class, 'create'])
        ->name('schools_admin.schools.create')
        ->middleware('can:create,App\Models\Schoolsinstitutions');

    Route::post('/schools/store', [App\Http\Controllers\SchoolAdmin\SchoolsinstitutionsController::class, 'store'])
        ->name('schools_admin.schools.store')
        ->middleware('can:create,App\Models\Schoolsinstitutions');

    Route::get('/schools/edit/{id}', [App\Http\Controllers\SchoolAdmin\SchoolsinstitutionsController::class, 'edit'])
        ->name('schools_admin.schools.edit');

    Route::post('/schools/delete/{id}', [App\Http\Controllers\SchoolAdmin\SchoolsinstitutionsController::class, 'delete'])
        ->name('schools_admin.schools.delete');

    Route::get(
        '/schools/show/{schoolsinstitutions}',
        [SchoolsinstitutionsController::class, 'show']
    )
        ->name('schools_admin.schools.show')
        ->middleware('auth');

    Route::get('/schools/edit/{id}', [App\Http\Controllers\SchoolAdmin\SchoolsinstitutionsController::class, 'edit'])
        ->name('schools_admin.schools.edit');
    Route::post('/schools/update', [App\Http\Controllers\SchoolAdmin\SchoolsinstitutionsController::class, 'update'])
        ->name('schools_admin.schools.update');

    Route::post('/schools/delete', [App\Http\Controllers\SchoolAdmin\SchoolsinstitutionsController::class, 'delete'])
        ->name('schools_admin.schools.delete');
});


//parents path

// Parents Controller routes (resource-based)
Route::prefix('parents')->group(function () {
    Route::get('/', [ParentsController::class, 'index'])->name('parents.index');
    Route::get('/create', [ParentsController::class, 'create'])->name('parents.create');
    Route::get('/create_child', [ParentsController::class, 'createChild'])->name('parents.create_child');
    Route::get('/edit_child', [ParentsController::class, 'editChild'])->name('parents.edit_child');

    Route::post('/store', [ParentsController::class, 'store'])->name('parents.store');
    Route::get('/{parent}', [ParentsController::class, 'show'])->name('parents.show');
    Route::get('/{parent}/edit', [ParentsController::class, 'edit'])->name('parents.edit');
    Route::delete('/{parent}', [ParentsController::class, 'destroy'])->name('parents.destroy');
    Route::get('/{parent}/profile', [ParentsController::class, 'profile'])->name('parents.profile');
    Route::put('/parents/{parent}', [ParentsController::class, 'update'])->name('parents.update');
    Route::get('/manage_your_childs/{parent}', [ParentsController::class, 'manageYourChilds'])->name('parents.manage_your_childs');


    // Child management routes
    Route::post('/add-child', [ParentsController::class, 'addChild'])->name('parents.add-child');
    Route::get('/edit_child/{parent_id}/{child_id}', [ParentsController::class, 'editChild'])->name('parents.edit_child')->where(['parent_id' => '[0-9]+', 'child_id' => '[0-9]+']);;

    Route::delete('/{parent}/remove-child/{child}', [ParentsController::class, 'removeChild'])->name('parents.removeChild');

    // Attendance routes
    Route::get('/{parent}/attendance', [ParentsController::class, 'childrenAttendance'])
        ->name('parents.childrenAttendance');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/childs', [ChildsController::class, 'index'])->name('childs.index');
    Route::get('/childs/create', [ChildsController::class, 'create'])->name('childs.create');
    Route::get('/childs/teacher-create/{schoolId}', [ChildsController::class, 'teacherCreate'])->name('childs.teacher-create');
    Route::post('/childs', [ChildsController::class, 'store'])->name('childs.store');
    Route::get('/childs/{child}', [ChildsController::class, 'show'])->name('childs.show');
    Route::get('/childs/profile/{child}', [ChildsController::class, 'profile'])->name('childs.profile');

    Route::get('/childs/{child}/edit', [ChildsController::class, 'edit'])->name('childs.edit');
    Route::put('/childs/{child}', [ChildsController::class, 'update'])->name('childs.update');
    Route::delete('/childs/{child}', [ChildsController::class, 'destroy'])->name('childs.destroy');
    Route::get('/childs/{child}/attendance-history', [ChildsController::class, 'attendanceHistory'])->name('childs.attendanceHistory');
});
//avatar 
Route::post('/upload-avatar', [AvatarController::class, 'store']);
// Route::get('/upload-avatar', [AvatarController::class, 'index'])->name('avatar
Route::middleware(['auth'])->group(function () {
    Route::get(
        '/schools_admin/schools/show/{schoolsinstitutions}',
        [SchoolsinstitutionsController::class, 'show']
    )
        ->name('schools_admin.schools.show');

    Route::get('/schools_admin/dashboard', [SchoolsinstitutionsController::class, 'dashboard'])
        ->name('schools_admin.dashboard');

    Route::get('/schools_admin/schools/index/{id?}', [SchoolsinstitutionsController::class, 'index'])
        ->name('schools_admin.schools.index');

    Route::get('/schools_admin/schools/show/{schoolsinstitutions}', [SchoolsinstitutionsController::class, 'show'])
        ->name('schools_admin.schools.show');

    Route::get('/schools_admin/schools/create', [SchoolsinstitutionsController::class, 'create'])
        ->name('schools_admin.schools.create');

    Route::post('/schools_admin/schools/store', [SchoolsinstitutionsController::class, 'store'])
        ->name('schools_admin.schools.store');

    Route::get('/schools_admin/schools/edit/{id}', [SchoolsinstitutionsController::class, 'edit'])
        ->name('schools_admin.schools.edit')->where(['id' => '[0-9]+']);

    Route::post('/schools_admin/schools/update', [SchoolsinstitutionsController::class, 'update'])
        ->name('schools_admin.schools.update');


    Route::post('/schools_admin/schools/delete', [SchoolsinstitutionsController::class, 'delete'])
        ->name('schools_admin.schools.delete');

    Route::get('/schools_admin/schools/manage/{id}', [SchoolsinstitutionsController::class, 'manage'])
        ->name('schools_admin.schools.manage')->where(['id' => '[0-9]+']);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/notification-settings', [NotificationSettingsController::class, 'index'])
        ->name('notification-settings.index');
    Route::post('/notification-settings/{service}/setup', [NotificationSettingsController::class, 'setup'])
        ->name('notification-settings.setup');
});

// Dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resourceful routes for ChildsController
    Route::resource('childs', ChildsController::class);

    // Notification Settings Routes
    Route::get('/notification-settings', [NotificationSettingsController::class, 'index'])
        ->name('notification-settings.index');
    Route::post('/notification-settings/{service}/setup', [NotificationSettingsController::class, 'setup'])
        ->name('notification-settings.setup');

    // Avatar Upload Route
    Route::post('/upload-avatar', [AvatarController::class, 'store'])->name('avatar.upload');

    // Admin Prefix Routes using Resourceful Routing
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::resource('schools_institutions', SchoolsinstitutionsController::class)
            ->only(['index', 'show'])
            ->parameters(['schools_institutions' => 'user']);
    });

    // School Admin Prefix Routes using Resourceful Routing
    Route::prefix('schools_admin')->name('schools_admin.')->middleware(
        [
            'auth',
            \App\Http\Middleware\SchoolsAdminAccessMiddleware::class
        ]
    )->group(function () {
        Route::get('/dashboard', [SchoolsinstitutionsController::class, 'dashboard'])->name('dashboard');
        Route::resource('schools', SchoolsinstitutionsController::class);
    });

    // Parents Prefix Routes using Resourceful Routing
    Route::prefix('parents')->name('parents.')->group(function () {
        Route::resource('/', ParentsController::class)->parameters(['' => 'parent'])->except(['show']);

        // Additional Child Management Routes
        Route::post('/add-child', [ParentsController::class, 'addChild'])->name('add-child');
        Route::get('/edit_child/{parent_id}/{child_id}', [ParentsController::class, 'editChild'])
            ->name('edit_child')
            ->where(['parent_id' => '[0-9]+', 'child_id' => '[0-9]+']);
        Route::delete('/{parent}/remove-child/{child}', [ParentsController::class, 'removeChild'])->name('removeChild');

        // Attendance Routes
        Route::get('/{parent}/attendance', [ParentsController::class, 'childrenAttendance'])->name('childrenAttendance');
    });
});
//attendance routes 

// Attendance routes
Route::middleware(['auth'])->prefix('attendance')->name('attendance.')->group(function () {
    // Index route to show attendance for a specific date
    Route::get('/', [AttendanceController::class, 'index'])->name('index');

    // Create attendance record for specific school and child
    Route::get('/create/{schoolId}/{childId}', [AttendanceController::class, 'create'])
        ->name('create')
        ->where(['schoolId' => '[0-9]+', 'childId' => '[0-9]+'])->name('attendance.create');

    // Store new attendance record
    Route::post('/store', [AttendanceController::class, 'store'])->name('store');

    // Edit attendance for specific child and date
    Route::get('/edit/{childId}/{date}', [AttendanceController::class, 'edit'])
        ->name('edit')
        ->where(['childId' => '[0-9]+']);

    // Update attendance record
    Route::put('/update/{childId}/{date}', [AttendanceController::class, 'update'])
        ->name('update')
        ->where(['childId' => '[0-9]+']);

    // Delete attendance record
    Route::delete('/destroy/{childId}/{date}', [AttendanceController::class, 'destroy'])
        ->name('destroy')
        ->where(['childId' => '[0-9]+']);

    // Attendance report
    Route::get('/report', [AttendanceController::class, 'report'])->name('report');
});



// Additional Routes
Route::get('/about', function () {
    return view('about.index');
})->name('about');

Route::get('/cheatsheet', [App\Http\Controllers\CheatsheetController::class, 'index'])->name('cheatsheet.index');

// Admin Routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Users Management Routes
        Route::resource('users', \App\Http\Controllers\Admin\UsersController::class);
    });
