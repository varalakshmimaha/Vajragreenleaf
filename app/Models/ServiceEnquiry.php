<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceEnquiry extends Model
{
    protected $fillable = [
        'service_id',
        'service_plan_id',
        'mobile',
        'email',
        'notes',
        'status',
        'admin_notes',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(ServicePlan::class, 'service_plan_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
