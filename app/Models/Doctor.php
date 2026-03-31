<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'clinic_id',
        'name',
        'phone_number',
        'location_url',
        'specialization',
        'photo',
        'is_active',
        'sheets_row_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
