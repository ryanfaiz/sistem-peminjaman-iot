<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Admin\ItemController as AdminItemController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

use App\Http\Controllers\User\ItemController as UserItemController;
use App\Http\Controllers\User\LoanController as UserLoanController;
use App\Http\Controllers\User\ProfileController as UserProfileController;


// Public + Guest
Route::view('/', 'home')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


// Authenticated Routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'redirectBasedOnRole'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

    Route::prefix('user')
        ->middleware('role:mahasiswa,dosen')
        ->name('user.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');

            // Item browsing
            Route::resource('items', UserItemController::class)->only(['index', 'show']);

            // Borrow
            Route::resource('borrow', UserLoanController::class)->only(['index', 'create', 'store', 'show']);
            Route::get('borrow/{borrow}/export', [UserLoanController::class, 'export'])->name('borrow.export');

            // Profile
            Route::get('profile', [UserProfileController::class, 'show'])->name('profile');
            Route::put('profile', [UserProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password.update');
        });

    Route::prefix('admin')
        ->middleware('role:admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

            Route::resource('items', AdminItemController::class);
            Route::resource('users', AdminUserController::class)->only(['index', 'show', 'edit', 'update']);

            Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');

            Route::resource('loans', AdminLoanController::class)->only(['index', 'edit']);

            // Loan actions
            Route::prefix('loans/{loan}')->group(function () {
                Route::post('approve', [AdminLoanController::class, 'approve'])->name('loans.approve');
                Route::post('reject',  [AdminLoanController::class, 'reject'])->name('loans.reject');
                Route::post('borrow',  [AdminLoanController::class, 'borrow'])->name('loans.borrow');
                Route::post('return',  [AdminLoanController::class, 'return'])->name('loans.return');
            });
        });
});
