<?php

namespace App\Services;

use Exception;
use Vonage\Client;
use Vonage\SMS\Message\SMS;
use Vonage\Client\Credentials\Basic;
use Illuminate\Validation\ValidationException;



class Vonage
{
    public function send($merchant)
    {
        $basic  = new Basic("039c702d", "sWM7y5hphWPrJIgc");
        $client = new Client($basic);

        try {
            $response = $client->sms()->send(
                new SMS("+966536187116", env('APP_NAME'), "Your OTP is $merchant->otp")
            );
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'phone' => 'The phone number you entered is not valid.',
            ]);
        }
    }
}
