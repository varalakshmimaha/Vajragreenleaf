<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = [
        'title',
        'issuing_authority',
        'image',
        'description',
        'certificate_number',
        'issue_date',
        'expiry_date',
        'order',
        'is_active',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];
}
