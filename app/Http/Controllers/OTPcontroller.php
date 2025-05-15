<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Services\Twilio;
use App\Services\Vonage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OTPcontroller extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);
        $merchant = Merchant::where('email', $request->email)->first();
        if (!$merchant) {
            throw ValidationException::withMessages([
                'email' => 'The email address you entered is not registered.',
            ]);
        }
        $merchant->generateOtp();
        if (config("verification.otp_provider") == "twilio") {
            (new Twilio($merchant))->send($merchant);
        }
        if (config("verification.otp_provider" == "vonage")) {
            (new Vonage($merchant))->send($merchant);
        }

        return view('merchant.auth.verify-otp', ['email' => $merchant->email]);
    }
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required',
            "email" => 'required|email|max:255',
        ]);
        $merchant = Merchant::where('email', $request->email)->first();
        if (!$merchant) {
            throw ValidationException::withMessages([
                'email' => 'The email address you entered is not registered.',
            ]);
        }
        if ($merchant->otp != $request->otp) {
            throw ValidationException::withMessages([
                'otp' => 'The OTP you entered is incorrect.',
            ]);
        }
        if ($merchant && $merchant->otp == $request->otp) {
            if (now()->isBefore($merchant->otp_expires_at)) {
                $merchant->resetOpt();
                Auth::guard('merchant')->login($merchant);
                return redirect()->route('merchant.home');
            } else {
                throw ValidationException::withMessages([
                    "otp" => "The OTP has expired. Please request a new one.",
                ]);
            }
        }
    }
}
