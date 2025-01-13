<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerifyEmailService
{
    public function sendEmail(string $from, string $to = null, $replyTo = null, string $subject, string $template, array $data = [], $attachment = null): bool
    {

        try {
           
            Mail::send($template, $data, function ($mail) use ($from, $to, $replyTo, $subject, $attachment) {
                $mail->from($from);
                    $mail->to($to); 

                if ($replyTo) {
                    $mail->replyTo($replyTo); 
                }
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
