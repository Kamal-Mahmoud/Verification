<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Validation\ValidationException;

class Twilio
{
    public function send($merchant)
    {
        // Your Account SID and Auth Token from console.twilio.com
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $client = new Client($sid, $token);

        // Use the Client to make requests to the Twilio REST API

        try {
            $client->messages->create(
                // The number you'd like to send the message to
                $merchant->phone,
                [
                    // A Twilio phone number you purchased at https://console.twilio.com
                    'from' => env('TWILIO_PHONE_NUMBER'),
                    // The body of the text message you'd like to send
                    'body' => "HEY $merchant->name, Your OTP is $merchant->otp"
                ]
            );
        } catch (TwilioException $e) {
            throw ValidationException::withMessages([
                'phone' => 'The phone number you entered is not valid.',
            ]);
        }
    }
}
