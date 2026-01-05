<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectionType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'view_path',
        'fields',
        'is_active',
    ];

    protected $casts = [
        'fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function pageSections(): HasMany
    {
        return $this->hasMany(PageSection::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
