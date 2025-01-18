<?php

namespace App\Console\Commands;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
use App\Models\Product as ModelProduct;
use App\Models\Price as ModelPrice;

use Illuminate\Console\Command;

class SyncStripeProductsAndPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:stripe-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $this->info("Fetching products from Stripe...");
        $products = Product::all();

        foreach ($products->data as $stripeProduct) {
            $localProduct = ModelProduct::updateOrCreate(
                ['stripe_id' => $stripeProduct->id], 
                [
                    'name' => $stripeProduct->name,
                    'description' => $stripeProduct->description,
                ]
            );

            $this->info("Fetching prices for product: {$stripeProduct->id}...");
            $prices = Price::all(['product' => $stripeProduct->id]);

            foreach ($prices->data as $stripePrice) {
                ModelPrice::updateOrCreate(
                    ['stripe_id' => $stripePrice->id], 
                    [
                        'product_id' => $localProduct->id, 
                        'unit_amount' => $stripePrice->unit_amount/100,
                        'currency' => $stripePrice->currency,
                        'interval' => isset($stripePrice->recurring) ? $stripePrice->recurring->interval : null, 
                    ]
                );
            }
        }

        $this->info('Products and prices have been successfully synced!');
    }
}