<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banker extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'description',
        'website_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
