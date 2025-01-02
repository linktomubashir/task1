<?php

namespace App\Jobs;

use App\Events\ItemOutOfStock;
use App\Models\Item;
use App\Models\SoldItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StockManagementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Item $item, public int $quantity,public string $coustomer_name,public string $coustomer_email)
    {
        $this->item = $item;
        $this->quantity = $quantity;
        $this->coustomer_name = $coustomer_name;
        $this->coustomer_email = $coustomer_email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();
        try {

            if ($this->item->quantity >= $this->quantity) {
                $this->item->quantity -= $this->quantity;
                $this->item->save();

                if ($this->item->quantity == 0) {
                    $this->item->status = 'out_of_stock';
                    $this->item->save();
                    event(new ItemOutOfStock($this->item));
                }
            }
            SoldItem::create([
                'coustomer_name'=> $this->coustomer_name,
                'coustomer_email'=> $this->coustomer_email,
                'item_id' => $this->item->id,
                'quantity' => $this->quantity,
                'brand' => $this->item->brand->id,
                'original_price' => $this->item->amount,
                'discount_price' => $this->item->getEffectivePrice(),
                'total_amount' => $this->item->getEffectivePrice() * $this->quantity,
                'status' => 'sold',
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
