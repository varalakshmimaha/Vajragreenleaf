<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'title',
        'subtitle',
        'description',
        'content',
        'image',
        'image_position',
        'gallery',
        'video_url',
        'layout',
        'columns',
        'container_width',
        'background_type',
        'background_color',
        'background_gradient',
        'background_image',
        'background_overlay',
        'text_color',
        'title_size',
        'title_alignment',
        'content_alignment',
        'padding_top',
        'padding_bottom',
        'margin_top',
        'margin_bottom',
        'animation_type',
        'animation_delay',
        'animation_duration',
        'items',
        'card_style',
        'card_hover',
        'card_rounded',
        'button_text',
        'button_url',
        'button_style',
        'button_size',
        'secondary_button_text',
        'secondary_button_url',
        'custom_css',
        'custom_js',
        'custom_class',
        'custom_id',
        'use_theme_colors',
        'order',
        'is_reusable',
        'is_active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'items' => 'array',
        'card_rounded' => 'boolean',
        'use_theme_colors' => 'boolean',
        'is_reusable' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'page_sections')
            ->withPivot(['order', 'is_active', 'overrides'])
            ->withTimestamps()
            ->orderBy('page_sections.order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeReusable($query)
    {
        return $query->where('is_reusable', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    // Layout options
    public static function getLayoutOptions(): array
    {
        return [
            'default' => 'Default (Text & Image)',
            'hero' => 'Hero Section',
            'cta' => 'Call to Action',
            'features' => 'Features Grid',
            'cards' => 'Cards Layout',
            'grid' => 'Content Grid',
            'slider' => 'Image Slider',
            'gallery' => 'Gallery Grid',
            'testimonials' => 'Testimonials',
            'pricing' => 'Pricing Table',
            'team' => 'Team Members',
            'tabs' => 'Tabbed Content',
            'accordion' => 'Accordion/FAQ',
            'contact' => 'Contact Form',
            'stats' => 'Statistics/Counters',
            'timeline' => 'Timeline',
            'custom' => 'Custom HTML',
        ];
    }

    public static function getAnimationOptions(): array
    {
        return [
            'none' => 'No Animation',
            'fade' => 'Fade In',
            'slide-up' => 'Slide Up',
            'slide-down' => 'Slide Down',
            'slide-left' => 'Slide Left',
            'slide-right' => 'Slide Right',
            'zoom' => 'Zoom In',
            'bounce' => 'Bounce',
        ];
    }

    public static function getCardStyles(): array
    {
        return [
            'default' => 'Default',
            'bordered' => 'Bordered',
            'shadow' => 'Shadow',
            'minimal' => 'Minimal',
            'gradient' => 'Gradient',
        ];
    }

    public static function getHoverEffects(): array
    {
        return [
            'none' => 'No Effect',
            'lift' => 'Lift Up',
            'scale' => 'Scale',
            'glow' => 'Glow',
            'border' => 'Border Color',
        ];
    }

    // Helper to get merged settings (with page-specific overrides)
    public function getMergedSettings(?array $overrides = null): array
    {
        $settings = $this->toArray();

        if ($overrides) {
            $settings = array_merge($settings, $overrides);
        }

        return $settings;
    }

    // Get background style
    public function getBackgroundStyle(): string
    {
        $styles = [];

        switch ($this->background_type) {
            case 'color':
                if ($this->background_color) {
                    $styles[] = "background-color: {$this->background_color}";
                }
                break;
            case 'gradient':
                if ($this->background_gradient) {
                    $styles[] = "background: {$this->background_gradient}";
                }
                break;
            case 'image':
                if ($this->background_image) {
                    $styles[] = "background-image: url('" . asset('storage/' . $this->background_image) . "')";
                    $styles[] = "background-size: cover";
                    $styles[] = "background-position: center";
                }
                break;
        }

        if ($this->text_color) {
            $styles[] = "color: {$this->text_color}";
        }

        return implode('; ', $styles);
    }

    // Get animation classes
    public function getAnimationClasses(): string
    {
        if ($this->animation_type === 'none') {
            return '';
        }

        return "animate-on-scroll animation-{$this->animation_type} delay-{$this->animation_delay} duration-{$this->animation_duration}";
    }

    // Get section classes
    public function getSectionClasses(): string
    {
        $classes = [];

        $classes[] = $this->padding_top;
        $classes[] = $this->padding_bottom;
        $classes[] = $this->margin_top;
        $classes[] = $this->margin_bottom;

        if ($this->custom_class) {
            $classes[] = $this->custom_class;
        }

        return implode(' ', array_filter($classes));
    }

    // Get container classes
    public function getContainerClasses(): string
    {
        switch ($this->container_width) {
            case 'full':
                return 'w-full px-4';
            case 'narrow':
                return 'max-w-4xl mx-auto px-4';
            default:
                return 'container mx-auto px-4';
        }
    }

    // Get title classes
    public function getTitleClasses(): string
    {
        $classes = [$this->title_size, 'font-bold'];

        switch ($this->title_alignment) {
            case 'left':
                $classes[] = 'text-left';
                break;
            case 'right':
                $classes[] = 'text-right';
                break;
            default:
                $classes[] = 'text-center';
        }

        return implode(' ', $classes);
    }

    // Get content alignment classes
    public function getContentAlignmentClasses(): string
    {
        switch ($this->content_alignment) {
            case 'left':
                return 'text-left';
            case 'right':
                return 'text-right';
            default:
                return 'text-center';
        }
    }

    // Get grid columns class
    public function getGridColumnsClass(): string
    {
        $cols = [
            '1' => 'grid-cols-1',
            '2' => 'grid-cols-1 md:grid-cols-2',
            '3' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
            '4' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
            '6' => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-6',
        ];

        return $cols[$this->columns] ?? 'grid-cols-1';
    }
}
