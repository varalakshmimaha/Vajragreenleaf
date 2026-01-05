<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'colors',
        'typography',
        'settings',
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'heading_color',
        'background_color',
        'heading_font',
        'body_font',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'colors' => 'array',
        'typography' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public static function getActive()
    {
        return static::where('is_active', true)->first()
            ?? static::where('is_default', true)->first();
    }

    public function activate(): void
    {
        static::query()->update(['is_active' => false]);
        $this->update(['is_active' => true]);
        Cache::flush();
    }

    // Accessor for primary_color - supports both JSON and direct column
    public function getPrimaryColorAttribute($value)
    {
        if ($value) return $value;
        return $this->colors['primary'] ?? '#2563eb';
    }

    public function getSecondaryColorAttribute($value)
    {
        if ($value) return $value;
        return $this->colors['secondary'] ?? '#1e40af';
    }

    public function getAccentColorAttribute($value)
    {
        if ($value) return $value;
        return $this->colors['accent'] ?? '#f59e0b';
    }

    public function getTextColorAttribute($value)
    {
        if ($value) return $value;
        return $this->colors['text'] ?? '#1f2937';
    }

    public function getHeadingColorAttribute($value)
    {
        if ($value) return $value;
        return $this->colors['heading'] ?? '#111827';
    }

    public function getBackgroundColorAttribute($value)
    {
        if ($value) return $value;
        return $this->colors['background'] ?? '#f9fafb';
    }

    public function getHeadingFontAttribute($value)
    {
        if ($value) return $value;
        return $this->typography['heading'] ?? 'Poppins';
    }

    public function getBodyFontAttribute($value)
    {
        if ($value) return $value;
        return $this->typography['body'] ?? 'Inter';
    }

    public function getCssVariables(): string
    {
        $css = ':root {';

        $css .= "--color-primary: {$this->primary_color};";
        $css .= "--color-secondary: {$this->secondary_color};";
        $css .= "--color-accent: {$this->accent_color};";
        $css .= "--color-text: {$this->text_color};";
        $css .= "--color-heading: {$this->heading_color};";
        $css .= "--color-background: {$this->background_color};";
        $css .= "--font-heading: '{$this->heading_font}';";
        $css .= "--font-body: '{$this->body_font}';";

        $css .= '}';
        return $css;
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::flush();
        });

        static::deleted(function () {
            Cache::flush();
        });
    }
}
