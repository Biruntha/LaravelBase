<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthUserController;

use Illuminate\Support\Facades\Auth;

Route::group(['middleware' => ['throttle:120,1', 'userAction']], function () {
    Route::group(['middleware' => ['authCheck']], function () {
        Route::get('/login', [AuthUserController::class, "loginPage"])->name('login');
    });

    Route::get('/jobseeker/signup', [AuthUserController::class, 'jobseekerSignup'])->name('jobseeker-signup');
    Route::post('/jobseeker/register', [AuthUserController::class, 'jobseekerRegister'])->name('jobseeker-register');

    Route::get('/company/signup', [AuthUserController::class, 'companySignup'])->name('company-signup');
    Route::post('/company/register', [AuthUserController::class, 'companyRegister'])->name('company-register');
    Route::get('/verify', [AuthUserController::class, 'verifyEmail'])->name('verify');

    Route::post('/login', [AuthUserController::class, 'login'])->name('login-dash');
    Route::get('/email-otp', [AuthUserController::class, 'emailOTP'])->name('email-otp');
    Route::post('/logout', [AuthUserController::class, 'logout'])->name('logout');

    Route::get('/forgot-password', [AuthUserController::class, 'passwordRequest'])->name('password.request');
    Route::post('/forgot-password', [AuthUserController::class, 'forgotPassword'])->name('forgot-password');

    Route::group(['middleware' => ['Authent', 'web','notificationCount']], function () {
       
        Route::get('/error', function () {
            return view('errors.error');
        })->name('error');
        Route::get('/terminate-account', [UserController::class, "terminateMyAccount"])->name('terminate-account');

        //======================================================================================
        //System Setup Routes
        //======================================================================================
        Route::resource('/manage/users', UserController::class);
        Route::post('/manage/update-user-status', [UserController::class, 'updateStatus'])->name('update-user-status');

       
        //======================================================================================
        //Company Routes
        //======================================================================================
       
       
    });
});


