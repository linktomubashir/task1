<?php

namespace App\Jobs;

use App\Events\ItemOutOfStock;
use App\Models\Item;
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
    public function __construct(public Item $item, public int $quantity)
    {
        $this->item = $item;
        $this->quantity = $quantity;
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
                    // $this->item->status = 'out_of_stock';
                    // $this->item->save();

                }
                event(new ItemOutOfStock($this->item));
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
