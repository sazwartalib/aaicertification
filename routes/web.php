<?php

use App\Http\Controllers\CertificatePrintController;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DigitalCertificateController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('enrollments.store');

Route::get('/verify', [CertificateVerificationController::class, 'index'])->name('verify.index');

Route::get('/c/{certificate:uuid}', [DigitalCertificateController::class, 'show'])->name('certificates.show');

Route::get('/admin/certificates/{certificate}/print', [CertificatePrintController::class, 'show'])
    ->middleware('auth')
    ->name('certificates.print');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
