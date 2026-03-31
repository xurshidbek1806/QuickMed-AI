<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disease extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'clinic_id',
        'name',
        'category',
        'description',
        'min_age',
        'max_age',
        'is_active',
        'sheets_row_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'min_age'   => 'integer',
        'max_age'   => 'integer',
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

    public function scopeForAge($query, int $age)
    {
        return $query->where('min_age', '<=', $age)->where('max_age', '>=', $age);
    }

    public function scopeForAgeCategory($query, string $category)
    {
        $ranges = [
            'godak'     => [0, 1],
            'bola'      => [1, 18],
            'osmir'     => [12, 16],
            'yoshlar'   => [14, 30],
            'orta_yosh' => [30, 60],
            'keksalar'  => [60, 150],
        ];

        if (!isset($ranges[$category])) {
            return $query;
        }

        [$min, $max] = $ranges[$category];

        return $query->where('min_age', '<=', $max)->where('max_age', '>=', $min);
    }
}
