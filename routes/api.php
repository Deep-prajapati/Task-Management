<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PanelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Authentication Routes
Route::post('/login', [AuthController::class , 'LogIn']);
Route::post('/registration', [AuthController::class , 'Registration']);
Route::post('otp/verification/{mode}' , [AuthController::class , 'OTPVerification']);
Route::post('forget/password' , [AuthController::class , 'ForgetPassword']);
Route::post('reset/password' , [AuthController::class , 'ResetPassword']);
Route::get('resend-otp' , [AuthController::class , 'ResendOTP']);
Route::get('check/email' , [AuthController::class , 'CheckEmail']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('dashboard' , [PanelController::class , 'Dashboard']);

    Route::get('/profile/info', [PanelController::class , 'Profile']);
    Route::post('/profile/update', [PanelController::class , 'ProfileUpdate']);
    Route::post('/password/update', [PanelController::class , 'PasswordUpdate']);

    Route::group(['prefix' => 'task'], function () 
    {
        Route::post('/add', [PanelController::class , 'TaskStore']);

        Route::get('/list', [PanelController::class , 'TaskList']);
        Route::get('/status/{id}', [PanelController::class , 'TaskStatus']);

        Route::get('/edit/{id}', [PanelController::class , 'TaskEdit']);
        Route::post('/update/{id}', [PanelController::class , 'TaskUpdate']);

        Route::post('/cancel/{id}', [PanelController::class , 'TaskCancel']);
        Route::delete('/delete/{id}', [PanelController::class , 'TaskDelete']);
    });
});
