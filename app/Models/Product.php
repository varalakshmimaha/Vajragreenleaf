<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'main_image',
        'short_description',
        'description',
        'key_benefits',
        'directions',
        'actions_indications',
        'method_of_use',
        'dosage',
        'features',
        'video_url',
        'video_file',
        'pdf_file',
        'gallery',
        'price',
        'price_label',
        'meta_title',
        'meta_description',
        'order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'gallery' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function enquiries(): HasMany
    {
        return $this->hasMany(ProductEnquiry::class);
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
