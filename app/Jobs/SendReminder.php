<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\SubscriptionReminder;
use App\Models\User;
use Carbon\Carbon;

class SendReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $date = Carbon::now()->addDays(3);
        $users = User::all();
        foreach($users as $user){
        $subscription = $user->subscription('default');
        if($subscription){
        $endDate = Carbon::createFromTimestamp($subscription->asStripeSubscription()->current_period_end);
        if($endDate <= $date){
            $user->notify(new SubscriptionReminder($user));
        }
        }

    } 

       
    }
}
