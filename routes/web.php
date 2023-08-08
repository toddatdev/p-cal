<?php

use App\Http\Controllers\Admin\EarningController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
})->name('root');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::post('/store', [ProfileController::class, 'store'])->name('profile.store');
    });


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Roles

    Route::middleware(['can:roles'])->group(function () {

        Route::get('/roles', [RoleController::class, 'index'])->name('roles');
        Route::prefix('role')->group(function () {

            Route::get('/create', [RoleController::class, 'create'])->name('role.create');
            Route::post('/store', [RoleController::class, 'store'])->name('role.store');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
            Route::post('/delete', [RoleController::class, 'destroy'])->name('role.delete');

        });
    });

    // Employees

    Route::middleware(['can:employees'])->group(function () {

        Route::prefix('employees')->group(function () {

            Route::get('/', [EmployeeController::class, 'index'])->name('employees');
            Route::get('/get-parent-role-users', [EmployeeController::class, 'getParentRoleUsers'])->name('employees.get_parent_role_users');
            Route::get('/get-info', [EmployeeController::class, 'getInfo'])->name('employees.get_info');
            Route::post('/store', [EmployeeController::class, 'store'])->name('employees.store');
            Route::post('/delete', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        });
    });

    // Admin Settings

    Route::middleware(['can:admin settings'])->group(function () {

        Route::prefix('settings')->group(function () {

            Route::get('/', [SettingsController::class, 'index'])->name('settings');
            Route::post('/store', [SettingsController::class, 'store'])->name('setting.store');
            Route::post('/delete', [SettingsController::class, 'destroy'])->name('settings.delete');
        });
    });

    // Projects

    Route::middleware(['can:projects'])->group(function () {

        Route::prefix('projects')->group(function () {

            Route::get('/', [ProjectController::class, 'index'])->name('projects');
            Route::get('/parent-users', [ProjectController::class, 'parentUsersList'])->name('projects.parent_users');
            Route::get('/edit', [ProjectController::class, 'edit'])->name('project.edit');
            Route::post('/store', [ProjectController::class, 'store'])->name('projects.store');
            Route::post('/destroy', [ProjectController::class, 'destroy'])->name('project.destroy');
            Route::get('/project-details/{id}', [ProjectController::class, 'projectDetails'])->name('project.details');
            Route::post('/store-earning', [EarningController::class, 'store'])->name('project.earning.store');
            Route::get('/edit-project-earning', [EarningController::class, 'edit'])->name('project.earning.edit');
            Route::post('/destroy-earning', [EarningController::class, 'destroy'])->name('project.earning.destroy');
            Route::post('/complete_project', [ProjectController::class, 'completeProject'])->name('project.complete_project');

            Route::prefix('/project-details')->group(function () {

                Route::post('/get-commission', [CommissionController::class, 'getCommission'])->name('projects.project_details.get_commission');
               Route::post('/edit-commission', [CommissionController::class, 'editCommission'])->name('projects.project_details.edit_commission');

               Route::post('users-list-for-stop-earning', [EarningController::class, 'usersListForStopEarning'])->name('projects.project_details.users_list_for_stop_earning');
               Route::post('/stop-earning', [EarningController::class, 'stopEarning'])->name('projects.project_details.stop_earning');
            });
        });
    });


    Route::prefix('earnings')->group(function () {
        Route::get('/', [EarningController::class, 'index'])->name('earnings');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications');
        Route::post('/approve', [NotificationController::class, 'approve'])->name('notifications.approve');
        Route::get('/project-detail', [NotificationController::class, 'projectDetail'])->name('notifications.project-detail');
        Route::get('/commission-detail', [NotificationController::class, 'commissionDetail'])->name('notifications.commission-detail');
    });

    Route::get('/profiles', function () {
        return view('dashboard');
    })->name('profiles');


});
