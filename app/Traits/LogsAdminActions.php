<?php

namespace App\Traits;

use App\Models\AdminAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait LogsAdminActions
{
    protected function logAdminAction(
        string $actionType,
        string $entityType,
        ?string $entityId = null,
        array $details = [],
    ): void {
        try {
            AdminAction::create([
                'admin_id'       => auth()->id(),
                'action_type'    => $actionType,
                'entity_type'    => $entityType,
                'entity_id'      => $entityId,
                'action_details' => $details ?: null,
                'ip_address'     => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Admin action log failed', ['error' => $e->getMessage()]);
        }
    }

    protected function logCreate(Model $model, array $details = []): void
    {
        $this->logAdminAction('create', $model->getTable(), $model->getKey(), $details);
    }

    protected function logUpdate(Model $model, array $details = []): void
    {
        $this->logAdminAction('update', $model->getTable(), $model->getKey(), $details);
    }

    protected function logDelete(Model $model, array $details = []): void
    {
        $this->logAdminAction('delete', $model->getTable(), $model->getKey(), $details);
    }
}
