<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// API sections

    //Login
    Route::post('login', [App\Http\Controllers\Api\Auth\LoginController::class, 'login']);

    // Registration
    Route::post('register', [App\Http\Controllers\Api\Auth\RegistrationController::class, 'register']);

    // user token used for auth verification
    Route::group( ['middleware' => ['jwt.verify'] ],function() {
        Route::post('logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']);
        Route::get('get_user', [App\Http\Controllers\Api\User\ProfileController::class, 'get_user']);

    });

