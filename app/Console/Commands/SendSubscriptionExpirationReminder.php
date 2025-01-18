<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\SubscriptionExpirationReminder;
use App\Events\SubscriptionReminder;
use App\Models\User;
use Carbon\Carbon;

class SendSubscriptionExpirationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:expiration-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $date = Carbon::now()->addDays(3);
        $users = User::all();
        foreach ($users as $user) {
            try {
                $subscription = $user->subscription('default');
                if ($subscription) {
                    $this->info("User: " . $user->name);
                    $endDate = Carbon::createFromTimestamp($subscription->asStripeSubscription()->current_period_end);
        
                    $this->info("Subscription End Date: " . $endDate);
                    if ($endDate <= $date) {
                        event(new SubscriptionReminder($user, $endDate));
                        $this->info("Event dispatched for user: " . $user->name);
                    }
                } else {
                    $this->info("No subscription found for user: " . $user->name);
                }
            } catch (Exception $e) {
                $this->error("Error processing user " . $user->name . ": " . $e->getMessage());
            }
        }
    }        

}
