<?php

namespace App\Services;

use Twilio\Rest\Client;
use App\Models\SmsHistory;

class SmsService
{
    public $twilioClient;
    public $from;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $this->twilioClient = new Client($sid, $authToken);
        $this->from = env('TWILIO_PHONE_NUMBER');
    }

    public function sendSms(string $to = null, string $message): string
    {
        $to = $to ?? "+923184016076";
        $to = $this->formatPhoneNumber($to);
        try {
            $messageSent = $this->twilioClient->messages->create(
                $to, 
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );
            SmsHistory::create([
                'to' => $to,
                'from' => $this->from,
                'message' => $message, 
                'message_sid' => $messageSent->sid,
            ]);

            return $messageSent->sid; 
        } catch (\Exception $e) { 
            return 'Error: ' . $e->getMessage();
        }
    }

    private function formatPhoneNumber($phoneNumber)
    {
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        if (substr($phoneNumber, 0, 1) !== '+') {
            $phoneNumber = '+' . $phoneNumber;
        }

        return $phoneNumber;
    }
}
