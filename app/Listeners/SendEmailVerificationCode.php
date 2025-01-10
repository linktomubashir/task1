<?php

namespace App\Listeners;

use App\Events\EmailVerificationCode;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailVerificationCode
{
    /**
     * Create the event listener.
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Handle the event.
     */
    public function handle(EmailVerificationCode $event): void
    {
        $email = $event->email;
        $verificationCode = $event->verificationCode;

        $emailSent = $this->emailService->sendEmail(
            $email,
            "Verify Email Address",
            ("Your email verification code is: $verificationCode"),
            null,
        );
        if(!$emailSent){
            dd($emailSent);
        }
    }
}
