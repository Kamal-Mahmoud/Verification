<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomtVerificationTokenController extends Controller
{
    public function notice(Request $request): RedirectResponse|View
    {
        return $request->user("merchant")->hasVerifiedEmail()
            ? to_route("merchant.home")
            : view("merchant.auth.verify-email");
    }

    public function verify(Request $request): RedirectResponse
    {
        $merchant = Merchant::where("verification_token", $request->token)->firstOrFail();
        if (now()->isBefore($merchant->verification_token_expires_at)) {
            $merchant->verifyUsingVerificationToken();
            event(new Verified($merchant));
            return to_route("merchant.home");
        }
        abort(403, "Token Expired");
    }

    public function resend(Request $request): RedirectResponse
    {
        if ($request->user('merchant')->hasVerifiedEmail()) {
            return redirect()->intended(route("merchant.home", absolute: false));
        }

        $request->user('merchant')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
