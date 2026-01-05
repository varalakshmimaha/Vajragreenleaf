<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id')->orderBy('published_at', 'desc');
    }

    public function publishedBlogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
