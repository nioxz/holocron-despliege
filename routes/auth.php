<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\AuthNew\Register;
use App\Livewire\AuthNew\Signin;
use Illuminate\Support\Facades\Route;
Route::middleware('guest')->group(function () {



    Route::get('login', Signin::class)->name('login');
    Route::get('register', Register::class)->name('register');


    Route::view('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Route::view('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {

    Route::view('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::view('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
