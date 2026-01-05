<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'career_id',
        'name',
        'email',
        'phone',
        'resume',
        'cover_letter_file',
        'cover_letter',
        'linkedin_url',
        'portfolio_url',
        'current_company',
        'current_position',
        'expected_salary',
        'available_from',
        'status',
        'notes',
    ];

    protected $casts = [
        'available_from' => 'date',
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'reviewing' => 'Under Review',
            'shortlisted' => 'Shortlisted',
            'interviewed' => 'Interviewed',
            'offered' => 'Offer Made',
            'hired' => 'Hired',
            'rejected' => 'Not Selected',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'reviewing' => 'blue',
            'shortlisted' => 'purple',
            'interviewed' => 'indigo',
            'offered' => 'cyan',
            'hired' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }
}
