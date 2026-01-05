<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'client_name' => 'Jennifer Walsh',
                'designation' => 'CEO',
                'company_name' => 'TechStart Inc.',
                'review' => 'Working with this team has been an absolute pleasure. They delivered our e-commerce platform ahead of schedule and the results exceeded our expectations. Our online sales have increased by 150% since launch.',
                'rating' => 5,
                'order' => 1,
            ],
            [
                'client_name' => 'Marcus Thompson',
                'designation' => 'CTO',
                'company_name' => 'FinanceHub',
                'review' => 'Their expertise in cloud architecture helped us migrate our entire infrastructure to AWS with zero downtime. The team\'s professionalism and technical knowledge are truly impressive.',
                'rating' => 5,
                'order' => 2,
            ],
            [
                'client_name' => 'Lisa Chen',
                'designation' => 'Product Manager',
                'company_name' => 'HealthPlus',
                'review' => 'The mobile app they developed for us has transformed how we engage with our patients. The user experience is intuitive and our app store ratings have been consistently positive.',
                'rating' => 5,
                'order' => 3,
            ],
            [
                'client_name' => 'Robert Martinez',
                'designation' => 'Director of Operations',
                'company_name' => 'LogiFlow Systems',
                'review' => 'They built us a custom logistics management system that has streamlined our operations significantly. We\'ve reduced manual processing time by 70% and errors by 90%.',
                'rating' => 5,
                'order' => 4,
            ],
            [
                'client_name' => 'Sarah O\'Brien',
                'designation' => 'Marketing Director',
                'company_name' => 'BrandVision Agency',
                'review' => 'The website redesign they delivered has dramatically improved our conversion rates. Their attention to detail in both design and functionality sets them apart from other agencies.',
                'rating' => 4,
                'order' => 5,
            ],
            [
                'client_name' => 'David Kim',
                'designation' => 'Founder',
                'company_name' => 'EduLearn Platform',
                'review' => 'From concept to launch, they guided us through every step of building our online learning platform. Their agile approach and clear communication made the process smooth and efficient.',
                'rating' => 5,
                'order' => 6,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create(array_merge($testimonial, ['is_active' => true]));
        }
    }
}
