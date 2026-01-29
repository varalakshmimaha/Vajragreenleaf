<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'banner_image',
        'banner_title',
        'banner_subtitle',
        'icon',
        'image',
        'short_description',
        'description',
        'meta_title',
        'meta_description',
        'order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function plans(): HasMany
    {
        return $this->hasMany(ServicePlan::class)->orderBy('order');
    }

    public function activePlans(): HasMany
    {
        return $this->hasMany(ServicePlan::class)
            ->where('is_active', true)
            ->orderBy('order');
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(ServiceEnquiry::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->name;
    }
}
