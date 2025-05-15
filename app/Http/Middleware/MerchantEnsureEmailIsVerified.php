<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL as FacadesURL;
use Soap\Url;
use Symfony\Component\HttpFoundation\Response;

class MerchantEnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next , $redirectToRoute = null): Response
    {
        if (! $request->user('merchant') ||
            ($request->user('merchant') instanceof MustVerifyEmail &&
            ! $request->user('merchant')->hasVerifiedEmail())) {
            return $request->expectsJson()
            ? abort(403 , "Mail not Verified")
            : Redirect::guest(FacadesURL::route($redirectToRoute?:"merchant.verification.notice"));
        }

        return $next($request);
    }
}
