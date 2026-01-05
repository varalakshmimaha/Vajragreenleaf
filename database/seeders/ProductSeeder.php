<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'CloudManager Pro',
                'slug' => 'cloudmanager-pro',
                'short_description' => 'All-in-one cloud infrastructure management platform.',
                'description' => '<p>CloudManager Pro is an enterprise-grade cloud management solution that simplifies infrastructure monitoring, scaling, and optimization across multiple cloud providers.</p><h3>Key Features</h3><ul><li>Multi-cloud Dashboard</li><li>Automated Scaling</li><li>Cost Analytics</li><li>Security Monitoring</li><li>API Integration</li></ul>',
                'features' => [
                    ['title' => 'Multi-Cloud Support', 'description' => 'Manage AWS, Azure, and GCP from a single dashboard'],
                    ['title' => 'Cost Optimization', 'description' => 'AI-powered recommendations to reduce cloud spending'],
                    ['title' => 'Security First', 'description' => 'Built-in compliance and security monitoring'],
                ],
                'price' => 299,
                'price_label' => '/month',
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'DevFlow CI/CD',
                'slug' => 'devflow-cicd',
                'short_description' => 'Streamlined continuous integration and deployment pipeline.',
                'description' => '<p>DevFlow automates your software delivery pipeline from code commit to production deployment. Built for modern development teams.</p>',
                'features' => [
                    ['title' => 'Fast Builds', 'description' => 'Parallel builds that reduce wait times'],
                    ['title' => 'Easy Configuration', 'description' => 'YAML-based pipeline configuration'],
                    ['title' => 'Integrations', 'description' => 'Connect with GitHub, GitLab, and more'],
                ],
                'price' => 99,
                'price_label' => '/month',
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'SecureVault',
                'slug' => 'securevault',
                'short_description' => 'Enterprise password and secrets management solution.',
                'description' => '<p>SecureVault provides military-grade encryption for your sensitive data, passwords, and API keys. Perfect for teams of all sizes.</p>',
                'features' => [
                    ['title' => 'Zero-Knowledge', 'description' => 'Your data is encrypted before leaving your device'],
                    ['title' => 'Team Sharing', 'description' => 'Securely share credentials with team members'],
                    ['title' => 'Audit Logs', 'description' => 'Complete visibility into access history'],
                ],
                'price' => 49,
                'price_label' => '/month',
                'is_featured' => true,
                'order' => 3,
            ],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, ['is_active' => true]));
        }
    }
}
