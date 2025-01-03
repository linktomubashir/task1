<?php

namespace App\Observers;

use App\Models\Item;
use App\Models\PriceHistory;
use Carbon\Carbon;

class ItemObserver
{

    /**
     * Handle the Item "created" event.
     */
    public function created(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "updated" event.
     */
    public function updating(Item $item): void
    {
        if ($item->isDirty('amount')) {
            PriceHistory::create([
                'item_id' => $item->id,
                'old_price' => $item->getOriginal('amount'),
                'new_price' => $item->amount,
                'changed_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Handle the Item "deleted" event.
     */
    public function deleted(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "restored" event.
     */
    public function restored(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "force deleted" event.
     */
    public function forceDeleted(Item $item): void
    {
        //
    }
}
