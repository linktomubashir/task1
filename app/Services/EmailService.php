<?php

namespace App\Services;

use App\Models\EmailHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;

class EmailService
{
    public function sendEmail(string $to, string $subject, string $msg, $attachment = null): bool
    {
        try {
            Mail::send([], [], function ($mail) use ($to, $subject, $msg, $attachment) {
                $mail->from('mubashrhussain41@gmail.com')
                     ->to($to)
                     ->subject($subject)
                     ->html($msg);

                if ($attachment instanceof UploadedFile && $attachment->isValid()) {
                    $mail->attach(
                        $attachment->getRealPath(),
                        [
                            'as' => $attachment->getClientOriginalName(),
                            'mime' => $attachment->getMimeType(),
                        ]
                    );
                } elseif (is_string($attachment) && file_exists($attachment)) {
                    $mail->attach($attachment);
                }
            });

            EmailHistory::create([
                'to' => $to,
                'subject' => $subject,
                'message' => $msg,
                'attachment' => $attachment instanceof UploadedFile ? $attachment->getClientOriginalName() : basename($attachment),
             ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
