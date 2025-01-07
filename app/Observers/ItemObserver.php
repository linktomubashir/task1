<?php

namespace App\Observers;

use App\Models\Item;
use App\Models\Auditlog;
use App\Models\PriceHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ItemObserver
{

    /**
     * Handle the Item "created" event.
     */
    public function created(Item $item): void
    {
        $this->logAudit('create', $item);
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

    public function updated(Item $item): void
    {
        $this->logAudit('update', $item);
    }

    /**
     * Handle the Item "deleted" event.
     */
    public function deleted(Item $item): void
    {
        $this->logAudit('delete', $item);

    }

    /**
     * Handle the Item "restored" event.
     */
    public function restored(Item $item): void
    {
        $this->logAudit('restore', $item);
    }

    /**
     * Handle the Item "force deleted" event.
     */
    public function forceDeleted(Item $item): void
    {
        $this->logAudit('forceDelete', $item);
    }
    public function logAudit($action,$model)
    {
        AuditLog::create([
            'action' => $action,
            'model_type' => get_class($model) ?? 'model',
            'model_id' => $model->id,
            'user_id' => Auth::id(),
            'changes' => json_encode($model->getChanges()),
            'performed_at' => now(),
        ]);
    }
}
