<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'thumbnail',
        'category_id',
        'tags',
        'order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : asset('storage/' . $this->image);
    }

    public function getImageUrl(): string
    {
        return asset('storage/' . $this->image);
    }
}
