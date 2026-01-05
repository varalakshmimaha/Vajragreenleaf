<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'featured_image',
        'short_description',
        'description',
        'gallery',
        'technologies',
        'client_name',
        'project_url',
        'completed_date',
        'meta_title',
        'meta_description',
        'order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'technologies' => 'array',
        'completed_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PortfolioCategory::class, 'category_id');
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
        return $value ?: $this->title;
    }
}
