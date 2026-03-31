<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommendation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'disease_id',
        'doctor_id',
        'recommendation_text',
        'priority',
        'is_active',
        'sheets_row_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority'  => 'integer',
    ];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
