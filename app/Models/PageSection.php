<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    protected $fillable = [
        'page_id',
        'section_id',
        'section_type_id',
        'title',
        'content',
        'settings',
        'order',
        'is_active',
        'overrides',
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'overrides' => 'array',
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function sectionType(): BelongsTo
    {
        return $this->belongsTo(SectionType::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Check if this is an old-style section type
    public function isOldType(): bool
    {
        return $this->section_type_id !== null && $this->sectionType !== null;
    }

    // Check if this is a new-style section
    public function isNewSection(): bool
    {
        return $this->section_id !== null && $this->section !== null;
    }

    // Get section with overrides applied (for new sections)
    public function getSectionWithOverrides(): ?Section
    {
        if (!$this->section) {
            return null;
        }

        $section = $this->section;

        if ($this->overrides && is_array($this->overrides)) {
            foreach ($this->overrides as $key => $value) {
                if ($value !== null && $value !== '') {
                    $section->{$key} = $value;
                }
            }
        }

        return $section;
    }

    // Get display name for the section
    public function getDisplayName(): string
    {
        if ($this->isOldType()) {
            return $this->sectionType->name;
        }

        if ($this->isNewSection()) {
            return $this->section->name;
        }

        return 'Unknown Section';
    }

    // Get the view path for rendering
    public function getViewPath(): string
    {
        if ($this->isOldType()) {
            return $this->sectionType->view_path ?? 'sections.' . $this->sectionType->slug;
        }

        return 'components.section-renderer';
    }
}
