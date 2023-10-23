<?php
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {

    Route::controller(Auth\AuthenticatedSessionController::class)->group(function(){

        Route::get('login', 'create')->name('login')->middleware('guest');

        Route::post('login', 'store')->name('login.store');

        Route::get('logout', 'destroy')->name('logout');
    });

    Route::controller(Auth\PasswordResetLinkController::class)->group(function(){

        Route::get('forgot-password', 'create') ->name('password.request');

        Route::post('forgot-password', 'store') ->name('password.email');

    });

    Route::controller(Auth\NewPasswordController::class)->group(function(){

        Route::get('reset-password/{token}', 'create')->name('password.reset');

        Route::post('reset-password', 'store')->name('password.store');

    });

});

    Route::middleware('auth')->group(function () {

        Route::put('password', [Auth\PasswordController::class, 'update'])->name('password.update');

        Route::controller(Auth\AuthenticatedSessionController::class)->group(function(){

        Route::get('logout', 'destroy')->name('logout');
        });
    });

?>
