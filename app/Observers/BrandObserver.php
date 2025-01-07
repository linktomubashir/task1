<?php

namespace App\Observers;

use App\Models\Brand;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class BrandObserver
{
    /**
     * Handle the Brand "created" event.
     */
    public function created(Brand $brand): void
    {
        $this->logAudit('create', $brand);
    }

    /**
     * Handle the Brand "updated" event.
     */
    public function updated(Brand $brand): void
    {
        $this->logAudit('update', $brand);

    }

    /**
     * Handle the Brand "deleted" event.
     */
    public function deleted(Brand $brand): void
    {
        // $this->logAudit('delete', $brand);
    }

    /**
     * Handle the Brand "restored" event.
     */
    public function restored(Brand $brand): void
    {
        $this->logAudit('restore', $brand);
    }

    /**
     * Handle the Brand "force deleted" event.
     */
    public function forceDeleted(Brand $brand): void
    {
        $this->logAudit('forceDelete', $brand);
    }

    private function logAudit($action, $model)
    {
        AuditLog::create([
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'user_id' => Auth::id(),
            'changes' => json_encode($model->getChanges()),
            'performed_at' => now(),
        ]);
    }
}
