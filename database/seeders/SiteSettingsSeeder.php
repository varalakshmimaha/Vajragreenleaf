<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_title', 'value' => 'TechVision Solutions', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Transforming Ideas into Digital Reality', 'type' => 'text', 'group' => 'general'],
            ['key' => 'footer_text', 'value' => 'We are a leading IT company specializing in cutting-edge software development, cloud solutions, and digital transformation services.', 'type' => 'textarea', 'group' => 'general'],

            // SEO
            ['key' => 'meta_title', 'value' => 'TechVision Solutions - Leading IT Company', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'TechVision Solutions provides world-class IT services including software development, cloud solutions, mobile apps, and digital transformation.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'meta_keywords', 'value' => 'IT company, software development, web development, mobile apps, cloud solutions', 'type' => 'text', 'group' => 'seo'],

            // Contact
            ['key' => 'address', 'value' => '123 Tech Park, Silicon Valley, CA 94025, USA', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'phone', 'value' => '+1 (555) 123-4567', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'email', 'value' => 'info@techvision.com', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'working_hours', 'value' => 'Mon - Fri: 9:00 AM - 6:00 PM', 'type' => 'text', 'group' => 'contact'],

            // Social
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/techvision', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/techvision', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/techvision', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/techvision', 'type' => 'text', 'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }
}
