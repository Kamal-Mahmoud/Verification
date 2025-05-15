<?php

namespace App\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\MerchantEmailNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Merchant extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    protected $guarded = ['id'];

    public function sendEmailVerificationNotification()
    {
        if (config("verification.way") == "email") {
            $url = URL::temporarySignedRoute(
                "merchant.verification.verify",
                now()->addMinutes(60),
                [
                    'id' => $this->getKey(),
                    'hash' => sha1($this->getEmailForVerification())
                ]
            );
            $this->notify(new MerchantEmailNotification($url));
        }
        if (config("verification.way") == "cvt") {
            $this->generateVerificationToken();
            $url = route(
                "merchant.verification.verify",
                [
                    'id' => $this->getKey(),
                    'token' => $this->verification_token
                ]
            );
            $this->notify(new MerchantEmailNotification($url));
        }

        if (config("verification.way") == "passwordLess") {
            $url = URL::temporarySignedRoute(
                "merchant.login.verify",
                now()->addMinutes(60),
                [
                    'merchant' => $this->getKey(),
                ]
            );
            $this->notify(new MerchantEmailNotification($url));
        }
    }
    public function generateVerificationToken()
    {
        if (config("verification.way") == "cvt") {
            $this->verification_token = bin2hex(random_bytes(32));
            $this->verification_token_expires_at = now()->addMinutes(10);
            $this->save();
        }
    }
    public function verifyUsingVerificationToken()
    {
        if (config("verification.way") == "cvt") {
            $this->email_verified_at = now();
            $this->verification_token = null;
            $this->verification_token_expires_at = null;
            $this->save();
        }
    }

    public function generateOtp()
    {
        if (config("verification.way") == "otp") {
            $this->otp = substr(str_shuffle(str_repeat('0123456789', 6)), 0, 6);
            $this->otp_expires_at = now()->addMinutes(10);
            $this->save();
        }
    }
    public function resetOpt()
    {
        if (config("verification.way") == "otp") {
            $this->otp = null;
            $this->otp_expires_at = null;
            $this->save();
        }
    }
}
