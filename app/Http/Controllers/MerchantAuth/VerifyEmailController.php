<?php

namespace App\Http\Controllers\MerchantAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\MerchantEmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(MerchantEmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user('merchant')->hasVerifiedEmail()) {
            return redirect()->intended(route('merchant.home', absolute: false).'?verified=1');
        }

        if ($request->user('merchant')->markEmailAsVerified()) { 
            event(new Verified($request->user('merchant')));
        }

        return redirect()->intended(route('merchant.home', absolute: false).'?verified=1');
    }
}
// http://127.0.0.1:8000/verify-email/6/813afd82b9aa70c680d1e7485b6e768deff0bdb7?expires=1744899195&signature=486e1b9158db1b2580aed38c1896a203af26923d75218f3522f6fa37380d5f8b


//http://127.0.0.1:8000/merchant/verify-email/7/cd01316a15ee31a47f37cf992124ff2d547ac54f?expires=1744900901&signature=1c9364f005f67799d12a0814a7032bc36d1ed68392b12e741f78f9ab866a6af6