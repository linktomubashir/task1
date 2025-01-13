<?php

namespace App\Listeners;

use App\Events\ReplyEmailEvent;
use App\Services\VerifyEmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReplyEmailListener
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
    public function handle(ReplyEmailEvent $event): void
    {
        $from = config('app.email');
        $replyTo = $event->support->email;
        $subject =  "Task 1";

        $template = "email.template";   // this is view like ===> view(email.template); 
        $data = [
            'msg' => $event->message,
        ];

        $emailSent = $this->EmailService->sendEmail(
            $from,
            $replyTo,
            $replyTo,
            $subject,
            $template,
            $data,
            null,
        );

    }
}
