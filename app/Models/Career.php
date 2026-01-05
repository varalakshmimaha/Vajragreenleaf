<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Career extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'job_type',
        'experience_level',
        'salary_range',
        'short_description',
        'description',
        'requirements',
        'benefits',
        'application_deadline',
        'positions',
        'is_active',
        'is_featured',
        'order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'application_deadline' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($career) {
            if (empty($career->slug)) {
                $career->slug = Str::slug($career->title);
            }
        });
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOpen($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('application_deadline')
              ->orWhere('application_deadline', '>=', now());
        });
    }

    public function isOpen(): bool
    {
        return is_null($this->application_deadline) || $this->application_deadline >= now();
    }

    public function getJobTypeLabel(): string
    {
        return match($this->job_type) {
            'full-time' => 'Full Time',
            'part-time' => 'Part Time',
            'contract' => 'Contract',
            'remote' => 'Remote',
            'internship' => 'Internship',
            default => ucfirst($this->job_type),
        };
    }

    public function getExperienceLevelLabel(): string
    {
        return match($this->experience_level) {
            'entry' => 'Entry Level',
            'mid' => 'Mid Level',
            'senior' => 'Senior Level',
            'lead' => 'Lead/Manager',
            'executive' => 'Executive',
            default => ucfirst($this->experience_level),
        };
    }
}
