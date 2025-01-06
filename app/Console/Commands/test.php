<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\SoldItem;
use App\Models\Brand;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:test';

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
        $brands = Brand::all();
        foreach($brands as $brand){

        
      $items = Item::all();
      foreach($items as $item){
        $this->info($brand->id);
        SoldItem::create([
            'item_id' => $item->id,
            'brand_id' => $brand->id ?? $item->brand_id,
            'coustomer_name' => 'mubashir',
            'coustomer_email' => 'mubashir@gmail.com',
            'quantity' => $item->quantity,
            'original_price' => $item->amount,
            'total_amount' => $item->quantity * $item->amount,
            'discount_price' => $item->getEffectivePrice(),
        ]);
      }        
    }
}
}
