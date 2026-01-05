<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        Theme::create([
            'name' => 'Blue Professional',
            'slug' => 'blue-professional',
            'primary_color' => '#2563eb',
            'secondary_color' => '#1e40af',
            'accent_color' => '#f59e0b',
            'text_color' => '#1f2937',
            'heading_color' => '#111827',
            'background_color' => '#ffffff',
            'heading_font' => 'Poppins',
            'body_font' => 'Inter',
            'is_active' => true,
            'is_default' => true,
        ]);

        Theme::create([
            'name' => 'Purple Modern',
            'slug' => 'purple-modern',
            'primary_color' => '#8b5cf6',
            'secondary_color' => '#6d28d9',
            'accent_color' => '#ec4899',
            'text_color' => '#1f2937',
            'heading_color' => '#111827',
            'background_color' => '#fafafa',
            'heading_font' => 'Montserrat',
            'body_font' => 'Open Sans',
            'is_active' => false,
            'is_default' => false,
        ]);

        Theme::create([
            'name' => 'Green Nature',
            'slug' => 'green-nature',
            'primary_color' => '#10b981',
            'secondary_color' => '#059669',
            'accent_color' => '#34d399',
            'text_color' => '#1f2937',
            'heading_color' => '#064e3b',
            'background_color' => '#f0fdf4',
            'heading_font' => 'Poppins',
            'body_font' => 'Nunito',
            'is_active' => false,
            'is_default' => false,
        ]);

        Theme::create([
            'name' => 'Orange Starter',
            'slug' => 'orange-starter',
            'primary_color' => '#f97316',
            'secondary_color' => '#ea580c',
            'accent_color' => '#0ea5e9',
            'text_color' => '#1f2937',
            'heading_color' => '#111827',
            'background_color' => '#fffbeb',
            'heading_font' => 'Roboto',
            'body_font' => 'Roboto',
            'is_active' => false,
            'is_default' => false,
        ]);
    }
}
