<?php
namespace App\Services;

use App\Models\User;
use Stripe\Stripe;
use Stripe\Subscription;

class SubscriptionService
{
    public function createOrUpdateSubscription(User $user, $data)
    {
        Stripe::setApiKey(config('cashier.secret'));

        $paymentMethod = $data['payment_method'];
        $plan          = $data['plan_id'];
        $user->createOrGetStripeCustomer();

        $user->updateDefaultPaymentMethod($paymentMethod);

        if ($user->subscribed('default')) {
            $user->subscription('default')->swap($plan);
        } else {
            $user->newSubscription('default', $plan)->create($paymentMethod);
        }
    }

    public function cancelSubscription(User $user)
    {
        if ($user->subscribed('default')) {
            $user->subscription('default')->cancel();
        }
    }

    public function renewSubscription(User $user, $action)
    {
        $subscription = $user->subscription('default');

        if ($action == 'resume') {
            if ($subscription->onGracePeriod()) {
                $subscription->resume();
            }
        } elseif ($action == 'pause') {
            if ($subscription->active()) {
                $subscription->pause();
            }
        } elseif($action == 'cancel'){
            if ($subscription) {
                $subscription->cancel();
            }
        }
    }
}
