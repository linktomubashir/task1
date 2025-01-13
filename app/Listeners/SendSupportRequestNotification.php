<?php

namespace App\Listeners;

use App\Events\SupportRequestEvent;
use App\Services\VerifyEmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSupportRequestNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(VerifyEmailService $EmailService)
    {
        $this->EmailService = $EmailService;
    }

    /**
     * Handle the event.
     */
    public function handle(SupportRequestEvent $event): void
    {
        $from = $event->email;
        $to = config('app.email');
        $subject =  "Task 1";

        $template = "email.template";   // this is view like ===> view(email.template); 
        $data = [
            'subject' => $event->subject,
            'msg' => $event->message,
        ];

        $emailSent = $this->EmailService->sendEmail(
            $from,
            $to,
            null,
            $subject,
            $template,
            $data,
            null,
        );

    }
}
