<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAction extends Model
{
    use HasUuids;

    protected $fillable = [
        'admin_id',
        'action_type',
        'entity_type',
        'entity_id',
        'action_details',
        'ip_address',
    ];

    protected $casts = [
        'action_details' => 'array',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
