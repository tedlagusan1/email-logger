<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard')->middleware(['auth']);

Route::match(['post', 'get'], '/register', [AuthController::class, 'register'])->name('register');
Route::match(['post', 'get'], '/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/registered/{id}', [AuthController::class, 'registered'])->name('registered');
Route::get('/verify/{id}', [AuthController::class, 'verify'])->name('verify');

Route::get('/trysend', [AuthController::class, 'trysend']);

Route::get('/verified', function () {
    return view('verified.index');
})->name('verified');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
