<?php

namespace App\Listeners;

use App\Events\SubscriptionReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\VerifyEmailService;
use App\Services\SmsService;

class SubscriptionReminderListener
{
    /**
     * Create the event listener.
     */
    public function __construct(VerifyEmailService $EmailService,SmsService $smsService)
    {
        $this->EmailService = $EmailService;
        $this->smsService = $smsService;
    }


    /**
     * Handle the event.
     */
    public function handle(SubscriptionReminder $event): void
    {
        $to = $event->user->email;
        $from = config('app.email');
        $subject =  "Task 1";

        $template = "email.template";   // this is view like ===> view(email.template); 
        $data = [
            'subject' => 'Subscription  Expire',
            'greeting' => 'Assalam-o-Alikum '.$event->user->name,
            'msg' => 'Your Subscription will Expire on '.$event->endDate,
            'actionUrl' => url('http://127.0.0.1:8000/'),
            'actionText' => 'View',
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
        $this->smsService->sendSms(
            null,
            ("Your Subscription will Expire on " .$event->endDate),
        );
    }
}
