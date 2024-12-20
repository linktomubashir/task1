<?php

namespace App\Services;

use Twilio\Rest\Client;
use App\Models\SmsHistory;

class SmsService
{
    protected $twilioClient;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $this->twilioClient = new Client($sid, $authToken);
    }

    public function sendSms($to, $message)
    {
        try {
            $messageSent = $this->twilioClient->messages->create(
                $to, 
                [
                    'from' => env('TWILIO_PHONE_NUMBER'),
                    'body' => $message
                ]
            );
            SmsHistory::create([
                'to' => $to,
                'from' => env('TWILIO_PHONE_NUMBER'),
                'message' => $message, 
                'message_sid' => $messageSent->sid,
            ]);

            return $messageSent->sid; 
        } catch (\Exception $e) { 
            return 'Error: ' . $e->getMessage();
        }
    }
}
