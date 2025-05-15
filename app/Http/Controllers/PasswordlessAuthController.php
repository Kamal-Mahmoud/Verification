<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PasswordlessAuthController extends Controller
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
        $merchant->sendEmailVerificationNotification();
        return back()->with('status', 'A verification email has been sent to your email address.');
    }
    public function verify($merchant)
    {
        Auth::guard("merchant")->loginUsingId($merchant);
        return redirect()->route('merchant.home');
    } 
}
