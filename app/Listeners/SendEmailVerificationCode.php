<?php

namespace App\Listeners;

use App\Events\EmailVerificationCode;
use App\Services\VerifyEmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailVerificationCode
{
    /**
     * Create the event listener.
     */
    public function __construct(VerifyEmailService $verifyEmailService)
    {
        $this->verifyEmailService = $verifyEmailService;
    }

    /**
     * Handle the event.
     */
    public function handle(EmailVerificationCode $event): void
    {
        $email = $event->email;
        $verificationLink = $event->verificationLink;
        $data = [
            'subject' => 'Verify Email Address',
            'verificationUrl' => $verificationLink,
        ];

        $emailSent = $this->verifyEmailService->sendEmail(
            $email,
            "Verify Email Address",
            $data,
            null,
        );
        if(!$emailSent){
            dd($emailSent);
        }
    }
}
