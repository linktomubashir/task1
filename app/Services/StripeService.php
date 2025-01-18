<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function create($data)
    {
        $stripeProduct = Product::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        $stripePrice = Price::create([
            'unit_amount' => $data['price'] * 100,
            'currency' =>  $data['currency'],
            'recurring' => ['interval' => $data['interval']],
            'product' => $stripeProduct->id,
        ]);

        return [$stripeProduct, $stripePrice];
    }

    public function update($data, $stripeProductId, $stripePriceId)
    {

        $stripeProduct = Product::retrieve($stripeProductId);
        $stripeProduct->name = $data['name'];
        $stripeProduct->description = $data['description'];
        $stripeProduct->save();

        $stripePrice = Price::retrieve($stripePriceId);
        $stripePrice->unit_amount = $data['price'] * 100;
        $stripePrice->currency = $data['currency'];
        $stripePrice->recurring = ['interval' => $data['interval']];
        $stripePrice->save();

        return [$stripeProduct, $stripePrice];
    }

    public function archive($stripeProductId, $stripePriceId)
    {
        try {
            $stripePrice = Price::retrieve($stripePriceId);
            $stripePrice->active = false;
            $stripePrice->save();

            $stripeProduct = Product::retrieve($stripeProductId);
            $stripeProduct->active = false;
            $stripeProduct->save();
            
        } catch (\Exception $e) {
            throw new \Exception('Error deleting product and price from Stripe: ' . $e->getMessage());
        }
    }

    public function restore($stripeProductId, $stripePriceId)
    {
        try {
            $stripePrice = Price::retrieve($stripePriceId);
            $stripePrice->active = true;
            $stripePrice->save();

            $stripeProduct = Product::retrieve($stripeProductId);
            $stripeProduct->active = true;
            $stripeProduct->save();
            
        } catch (\Exception $e) {
            throw new \Exception('Error restoring product and price from Stripe: ' . $e->getMessage());
        }
    }
}
