<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $fillable = [
        'title',
        'value',
        'suffix',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'value' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedValueAttribute(): string
    {
        return number_format($this->value) . ($this->suffix ?? '');
    }
}
