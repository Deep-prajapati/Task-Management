<?php

use App\Http\Controllers\Panel\{
    AuthController,
    PanelController,
};
use Illuminate\Support\Facades\Route;


// Authentication Routes
Route::get('/', [AuthController::class , 'LogIn'])->name('panel.login');
Route::post('/login', [AuthController::class , 'LogInSubmit'])->name('login.submit');

Route::get('register' , [AuthController::class , 'Register'])->name('register');
Route::post('register-user' , [AuthController::class , 'RegisterSubmit'])->name('register.submit');

Route::get('otp/verification/{mode}' , [AuthController::class , 'OTPVerification'])->name('otp.verification');
Route::post('otp/verification/submit/{mode}' , [AuthController::class , 'VerifyOTP'])->name('verify.otp');

Route::get('forget/password' , [AuthController::class , 'ForgetPassword'])->name('forget.password');
Route::post('forget/password/submit' , [AuthController::class , 'ForgetPasswordSubmit'])->name('forget.password.submit');

Route::get('reset/password' , [AuthController::class , 'ResetPassword'])->name('reset.password');
Route::post('reset/password/submit' , [AuthController::class , 'ResetPasswordSubmit'])->name('reset.password.submit');

Route::get('resend-otp/{email}' , [AuthController::class , 'ResendOTP'])->name('resend.otp');

Route::get('terms-and-conditions' , [AuthController::class , 'TeamsAndConditions'])->name('terms.and.conditions');
Route::post('check/email' , [AuthController::class , 'CheckEmail'])->name('check.email');

Route::get('logout' , [AuthController::class , 'Logout'])->name('logout');


// Panel Routes
Route::group(['middleware' => 'Panel-Auth'], function () 
{
    Route::controller(PanelController::class)->group(function () 
    {
        Route::get('/dashboard', 'Dashboard')->name('dashboard');

        Route::get('/profile', 'Profile')->name('profile');
        Route::post('/profile', 'ProfileUpdate')->name('profile.update');
        Route::post('/password', 'PasswordUpdate')->name('password.update');

        Route::group(['prefix' => 'task', 'as' => 'task.'], function () 
        {
            Route::get('/add', 'AddTask')->name('add');
            Route::post('/add', 'TaskStore')->name('store');

            Route::get('/list', 'TaskList')->name('list');
            Route::get('/status/{id}', 'TaskStatus')->name('status');

            Route::get('/edit/{id}', 'TaskEdit')->name('edit');
            Route::post('/edit/{id}', 'TaskUpdate')->name('update');

            Route::post('/cancel/{id}', 'TaskCancel')->name('cancel');
            Route::delete('/delete', 'TaskDelete')->name('delete');
        });
    });
});

Route::get('/ApiLogin', function(){
    return response()->json([
        'status' => false,
        'message' => 'Login Required',
        'data' => [],
    ],401);
})->name('login');

// Route::any('{url}', function(){
//     return view('_pages.404');
// })->where('url', '.*');





