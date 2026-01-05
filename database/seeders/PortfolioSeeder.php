<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development', 'slug' => 'web-development', 'order' => 1],
            ['name' => 'Mobile Apps', 'slug' => 'mobile-apps', 'order' => 2],
            ['name' => 'UI/UX Design', 'slug' => 'ui-ux-design', 'order' => 3],
            ['name' => 'Cloud Solutions', 'slug' => 'cloud-solutions', 'order' => 4],
        ];

        foreach ($categories as $category) {
            PortfolioCategory::create(array_merge($category, ['is_active' => true]));
        }

        $portfolios = [
            [
                'category_id' => 1,
                'title' => 'E-Commerce Platform for RetailMax',
                'slug' => 'ecommerce-retailmax',
                'short_description' => 'A complete e-commerce solution with inventory management and multi-vendor support.',
                'description' => '<p>We developed a comprehensive e-commerce platform for RetailMax, a leading retail chain. The platform handles thousands of daily transactions with seamless performance.</p><h3>Project Highlights</h3><ul><li>Multi-vendor marketplace functionality</li><li>Real-time inventory sync</li><li>Advanced analytics dashboard</li><li>Mobile-responsive design</li></ul>',
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'AWS'],
                'client_name' => 'RetailMax Inc.',
                'project_url' => 'https://retailmax.example.com',
                'completed_date' => '2024-06-15',
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'category_id' => 2,
                'title' => 'HealthTrack Mobile App',
                'slug' => 'healthtrack-mobile-app',
                'short_description' => 'A health monitoring app with wearable device integration.',
                'description' => '<p>HealthTrack is a comprehensive health monitoring application that syncs with various wearable devices to track fitness goals, sleep patterns, and vital signs.</p>',
                'technologies' => ['React Native', 'Node.js', 'MongoDB', 'Firebase'],
                'client_name' => 'HealthTech Solutions',
                'project_url' => null,
                'completed_date' => '2024-08-20',
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'category_id' => 3,
                'title' => 'Banking Dashboard Redesign',
                'slug' => 'banking-dashboard-redesign',
                'short_description' => 'Complete UI/UX overhaul for a digital banking platform.',
                'description' => '<p>We redesigned the entire user interface for SecureBank\'s digital banking platform, focusing on accessibility, user experience, and modern design principles.</p>',
                'technologies' => ['Figma', 'Adobe XD', 'Tailwind CSS', 'React'],
                'client_name' => 'SecureBank',
                'project_url' => 'https://securebank.example.com',
                'completed_date' => '2024-05-10',
                'is_featured' => true,
                'order' => 3,
            ],
            [
                'category_id' => 4,
                'title' => 'Enterprise Cloud Migration',
                'slug' => 'enterprise-cloud-migration',
                'short_description' => 'Migrated legacy systems to AWS with zero downtime.',
                'description' => '<p>Successfully migrated a Fortune 500 company\'s legacy infrastructure to AWS, achieving 40% cost reduction and improved scalability.</p>',
                'technologies' => ['AWS', 'Docker', 'Kubernetes', 'Terraform'],
                'client_name' => 'GlobalCorp Industries',
                'project_url' => null,
                'completed_date' => '2024-09-01',
                'is_featured' => true,
                'order' => 4,
            ],
            [
                'category_id' => 1,
                'title' => 'Learning Management System',
                'slug' => 'learning-management-system',
                'short_description' => 'Custom LMS for corporate training programs.',
                'description' => '<p>Built a feature-rich learning management system supporting video courses, quizzes, certifications, and progress tracking for enterprise clients.</p>',
                'technologies' => ['Laravel', 'React', 'PostgreSQL', 'WebRTC'],
                'client_name' => 'EduCorp',
                'project_url' => 'https://learn.educorp.example.com',
                'completed_date' => '2024-07-25',
                'is_featured' => false,
                'order' => 5,
            ],
            [
                'category_id' => 2,
                'title' => 'Food Delivery App',
                'slug' => 'food-delivery-app',
                'short_description' => 'On-demand food delivery app with real-time tracking.',
                'description' => '<p>Developed a complete food delivery ecosystem including customer app, restaurant dashboard, and driver app with real-time GPS tracking.</p>',
                'technologies' => ['Flutter', 'Firebase', 'Google Maps API', 'Stripe'],
                'client_name' => 'QuickBite',
                'project_url' => null,
                'completed_date' => '2024-04-18',
                'is_featured' => false,
                'order' => 6,
            ],
        ];

        foreach ($portfolios as $portfolio) {
            Portfolio::create(array_merge($portfolio, ['is_active' => true]));
        }
    }
}
