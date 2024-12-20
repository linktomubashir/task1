<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\SendEmail;
use App\Services\EmailService;
use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct(EmailService $emailService, SmsService $smsService)
    {
        $this->emailService = $emailService;
        $this->smsService = $smsService;
    }

    /**
     * Handle the event.
     */
    public function handle(SendEmail $event): void
    {
        // $users = User::where('brand_updates', true)->get();

        // i not implenet subicribers funtionality so i use all users just for test

        $users = User::all();
        foreach ($users as $user) {
            $emailSent = $this->emailService->sendEmail(
                $user->email,
                $event->brand->name,
                ("This is message for test " . $event->brand->name),
            );
        }

        $this->smsService->sendSms(
            null,
            ("This is message for test " . $event->brand->name),
        );
    }
}
