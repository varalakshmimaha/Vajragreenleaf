<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Transform Your Business with Technology',
                'subtitle' => 'We deliver innovative IT solutions that drive growth and efficiency',
                'cta_text' => 'Get Started',
                'cta_url' => '/contact',
                'cta_text_2' => 'Our Services',
                'cta_url_2' => '/services',
                'order' => 1,
            ],
            [
                'title' => 'Enterprise Cloud Solutions',
                'subtitle' => 'Scale your infrastructure with our expert cloud migration and management services',
                'cta_text' => 'Learn More',
                'cta_url' => '/services/cloud-solutions',
                'cta_text_2' => 'Contact Us',
                'cta_url_2' => '/contact',
                'order' => 2,
            ],
            [
                'title' => 'Custom Software Development',
                'subtitle' => 'From concept to deployment, we build tailored solutions for your unique challenges',
                'cta_text' => 'View Portfolio',
                'cta_url' => '/portfolio',
                'cta_text_2' => 'Request Quote',
                'cta_url_2' => '/contact',
                'order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create(array_merge($banner, ['is_active' => true]));
        }
    }
}
