<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerifyEmailService
{
    public function sendEmail(string $to, string $subject, array $data = [], $attachment = null): bool
{
    try {
        Mail::send('email.template', $data, function ($mail) use ($to, $subject, $attachment) {
            $mail->from('mubashrhussain41@gmail.com');
            $mail->to($to);
            $mail->subject($subject);
        });

        return true;
    } catch (\Exception $e) {
        Log::error('Email could not be sent: ' . $e->getMessage());
dd($e->getMessage());
        return false;
    }
}

}
