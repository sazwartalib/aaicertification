<?php

use App\Http\Controllers\Admin\AdministratorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('courses', CourseController::class)->except('show');
        Route::resource('enrollments', EnrollmentController::class)->except('show');
        Route::resource('certificates', CertificateController::class)->except('show');

        Route::resource('administrators', AdministratorController::class)
            ->only(['index', 'create', 'store', 'destroy']);

        Route::get('account', [PasswordController::class, 'edit'])->name('account.edit');
        Route::put('account/password', [PasswordController::class, 'update'])
            ->middleware('throttle:6,1')
            ->name('account.password.update');
    });
