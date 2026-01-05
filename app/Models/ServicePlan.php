<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServicePlan extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'price',
        'price_label',
        'features',
        'cta_text',
        'cta_url',
        'is_popular',
        'order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(ServiceEnquiry::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
