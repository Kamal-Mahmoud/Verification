<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\MerchantMiddleware;
use App\Http\Controllers\CustomtVerificationTokenController;
use App\Http\Controllers\MerchantAuth\VerifyEmailController;
use App\Http\Controllers\MerchantAuth\RegisteredUserController;
use App\Http\Controllers\MerchantAuth\AuthenticatedSessionController;
use App\Http\Controllers\MerchantAuth\EmailVerificationPromptController;
use App\Http\Controllers\MerchantAuth\EmailVerificationNotificationController;
use App\Http\Controllers\OTPcontroller;
use App\Http\Controllers\PasswordlessAuthController;

Route::middleware('guest:merchant')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    if (config("verification.way") == "passwordLess") {

        Route::post('login', [PasswordlessAuthController::class, 'store']);
        Route::get('verify-email/{merchant}', [PasswordlessAuthController::class, 'verify']) // دة اللي بيستقبل الضغط ع الزار اللي اتبعت
            ->middleware(['signed', 'throttle:6,1'])
            ->name('login.verify');
    }
    elseif (config("verification.way") == "otp") {
        Route::post('login', [OTPcontroller::class, 'store']);
        Route::post('verify-otp', [OTPcontroller::class, 'verify'])->name("verifyOTP");
    }
    else {
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    }


    // Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    //     ->name('password.request');

    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    //     ->name('password.email');

    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    //     ->name('password.reset');

    // Route::post('reset-password', [NewPasswordController::class, 'store'])
    //     ->name('password.store');
});

Route::middleware(MerchantMiddleware::class)->group(function () {
    if (config('verification.way') == 'passwordLess') {
        Route::get('verify-email', [PasswordlessAuthController::class, 'notice'])
            ->name('verification.notice');
    } elseif (config('verification.way') == 'email') {
        Route::get('verify-email', EmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
    }

    if (config('verification.way') == 'cvt') {
        Route::get('verify-email', [CustomtVerificationTokenController::class, 'notice'])
            ->name('verification.notice');

        Route::get('verify-email/{id}/{token}', [CustomtVerificationTokenController::class, 'verify'])
            ->middleware(['throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [CustomtVerificationTokenController::class, 'resend'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
    }


    // Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
    //     ->name('password.confirm');

    // Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
