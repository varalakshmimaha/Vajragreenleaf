<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'banner_image',
        'banner_title',
        'banner_subtitle',
        'layout',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_active',
        'is_homepage',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_homepage' => 'boolean',
    ];

    // Page sections (pivot relationship)
    public function pageSections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }

    public function activePageSections(): HasMany
    {
        return $this->hasMany(PageSection::class)
            ->where('is_active', true)
            ->orderBy('order');
    }

    // Direct sections relationship
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'page_sections')
            ->withPivot(['order', 'is_active', 'overrides'])
            ->withTimestamps()
            ->orderBy('page_sections.order');
    }

    public function activeSections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'page_sections')
            ->withPivot(['order', 'is_active', 'overrides'])
            ->wherePivot('is_active', true)
            ->withTimestamps()
            ->orderBy('page_sections.order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getHomepage()
    {
        return static::where('is_homepage', true)
            ->where('is_active', true)
            ->with(['activePageSections.section', 'activePageSections.sectionType'])
            ->first();
    }

    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }
}
