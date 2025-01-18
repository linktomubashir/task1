<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Subscription;
use Carbon\Carbon;
use App\Services\SubscriptionService;
use App\Models\User;
use Log;

class WebhookController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('cashier.secret'));
        
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('cashier.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);

            switch ($event->type) {
                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event);
                    break;
                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event);
                    break;
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event);
                    break;
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event);
                    break;
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event);
                    break;
                case 'customer.subscription.trial_will_end':
                    $this->handleSubscriptionTrialWillEnd($event);
                    break;
                default:
                    Log::info('Unhandled event type: ' . $event->type);
                    break;
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'failure'], 400);
        }
    }

    protected function handleInvoicePaymentSucceeded($event)
    {
        $subscription = Subscription::retrieve($event->data->object->subscription);
        $user = User::find($event->data->object->customer);
        $user->subscription('default')->update(['status' => 'active']);
    }

    protected function handleInvoicePaymentFailed($event)
    {
        $subscription = Subscription::retrieve($event->data->object->subscription);
        $user = User::find($event->data->object->customer);
        $this->subscriptionService->renewSubscription($user, 'pause');
    }

    protected function handleSubscriptionCreated($event)
    {
        $subscription = $event->data->object;
         $user = User::where('stripe_id',$subscription->customer)->first();
         $user->subscription('default')->createorupdate([
            'quantity' => $subscription->items->data[0]->quantity,
                'ends_at' => Carbon::createFromTimestamp($subscription->current_period_end)
         ]);
    }

    protected function handleSubscriptionUpdated($event)
    {
        $subscription = $event->data->object;
        $user = User::where('stripe_id',$subscription->customer)->first();
        if ($user) {
            $user->subscription('default')->update([
                'stripe_status' => $subscription->status,
                'stripe_price' => $subscription->items->data[0]->plan->id,
                'quantity' => $subscription->items->data[0]->quantity,
                'ends_at' => Carbon::createFromTimestamp($subscription->current_period_end)
            ]);
        }
    }

    protected function handleSubscriptionDeleted($event)
    {
        $subscription = $event->data->object;
         $user = User::where('stripe_id',$subscription->customer)->first();
        $user->subscription('default')->update([
            'stripe_status' => $subscription->status,
        ]);
    }

    protected function handleSubscriptionTrialWillEnd($event)
    {
        $subscription = $event->data->object;
         $user = User::where('stripe_id',$subscription->customer)->first();
         $user->subscription('default')->update([
            'trial_ends_at' => Carbon::createFromTimestamp($subscription->trial_end),
        ]);
    }
}
