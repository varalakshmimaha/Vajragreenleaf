<?php

namespace Database\Seeders;

use App\Models\SectionType;
use Illuminate\Database\Seeder;

class SectionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Banner Slider',
                'slug' => 'banner',
                'description' => 'Hero banner section with image/video slides',
                'view_path' => 'sections.banner',
            ],
            [
                'name' => 'About Section',
                'slug' => 'about',
                'description' => 'About us section with image and text',
                'view_path' => 'sections.about',
            ],
            [
                'name' => 'Services Grid',
                'slug' => 'services',
                'description' => 'Display services in a grid layout',
                'view_path' => 'sections.services',
            ],
            [
                'name' => 'How We Work',
                'slug' => 'how-we-work',
                'description' => 'Step-by-step process section',
                'view_path' => 'sections.how-we-work',
            ],
            [
                'name' => 'Counter Stats',
                'slug' => 'counters',
                'description' => 'Animated counter statistics section',
                'view_path' => 'sections.counters',
            ],
            [
                'name' => 'Portfolio Grid',
                'slug' => 'portfolio',
                'description' => 'Display portfolio items',
                'view_path' => 'sections.portfolio',
            ],
            [
                'name' => 'Blog Posts',
                'slug' => 'blog',
                'description' => 'Display latest blog posts',
                'view_path' => 'sections.blog',
            ],
            [
                'name' => 'Team Members',
                'slug' => 'team',
                'description' => 'Display team members',
                'view_path' => 'sections.team',
            ],
            [
                'name' => 'Testimonials',
                'slug' => 'testimonials',
                'description' => 'Client testimonials slider',
                'view_path' => 'sections.testimonials',
            ],
            [
                'name' => 'Clients Logos',
                'slug' => 'clients',
                'description' => 'Display client logos',
                'view_path' => 'sections.clients',
            ],
            [
                'name' => 'Call to Action',
                'slug' => 'cta',
                'description' => 'Call to action section',
                'view_path' => 'sections.cta',
            ],
            [
                'name' => 'Custom HTML',
                'slug' => 'custom-html',
                'description' => 'Custom HTML content section',
                'view_path' => 'sections.custom-html',
            ],
        ];

        foreach ($types as $type) {
            SectionType::create(array_merge($type, ['is_active' => true]));
        }
    }
}
