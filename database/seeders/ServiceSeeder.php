<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'icon' => 'fas fa-code',
                'short_description' => 'Custom web applications built with cutting-edge technologies for optimal performance and scalability.',
                'description' => '<p>We build powerful, scalable web applications tailored to your business needs. Our team of expert developers uses the latest technologies and best practices to create solutions that drive growth.</p><h3>What We Offer</h3><ul><li>Custom Web Applications</li><li>E-commerce Solutions</li><li>Content Management Systems</li><li>API Development</li><li>Progressive Web Apps</li></ul>',
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'Mobile App Development',
                'slug' => 'mobile-apps',
                'icon' => 'fas fa-mobile-alt',
                'short_description' => 'Native and cross-platform mobile applications that deliver exceptional user experiences.',
                'description' => '<p>Transform your ideas into powerful mobile applications. We develop iOS and Android apps that engage users and drive business results.</p><h3>Our Expertise</h3><ul><li>iOS Development</li><li>Android Development</li><li>React Native Apps</li><li>Flutter Development</li><li>App Store Optimization</li></ul>',
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'Cloud Solutions',
                'slug' => 'cloud-solutions',
                'icon' => 'fas fa-cloud',
                'short_description' => 'Scalable cloud infrastructure and migration services for modern businesses.',
                'description' => '<p>Leverage the power of cloud computing to scale your business efficiently. We provide comprehensive cloud solutions including migration, optimization, and management.</p><h3>Services Include</h3><ul><li>Cloud Migration</li><li>AWS, Azure, GCP</li><li>DevOps & CI/CD</li><li>Cloud Security</li><li>Cost Optimization</li></ul>',
                'is_featured' => true,
                'order' => 3,
            ],
            [
                'name' => 'UI/UX Design',
                'slug' => 'ui-ux-design',
                'icon' => 'fas fa-paint-brush',
                'short_description' => 'User-centered design that creates memorable digital experiences.',
                'description' => '<p>Create stunning user experiences with our design expertise. We focus on user research, intuitive interfaces, and beautiful aesthetics.</p><h3>Design Services</h3><ul><li>User Research</li><li>Wireframing & Prototyping</li><li>Visual Design</li><li>Interaction Design</li><li>Design Systems</li></ul>',
                'is_featured' => true,
                'order' => 4,
            ],
            [
                'name' => 'Cybersecurity',
                'slug' => 'cybersecurity',
                'icon' => 'fas fa-shield-alt',
                'short_description' => 'Comprehensive security solutions to protect your digital assets.',
                'description' => '<p>Protect your business from cyber threats with our comprehensive security solutions. We help you identify vulnerabilities and implement robust security measures.</p>',
                'is_featured' => false,
                'order' => 5,
            ],
            [
                'name' => 'Data Analytics',
                'slug' => 'data-analytics',
                'icon' => 'fas fa-chart-bar',
                'short_description' => 'Transform data into actionable insights that drive business decisions.',
                'description' => '<p>Unlock the power of your data. Our analytics solutions help you make data-driven decisions and gain competitive advantages.</p>',
                'is_featured' => false,
                'order' => 6,
            ],
        ];

        foreach ($services as $service) {
            $created = Service::create(array_merge($service, ['is_active' => true]));

            // Add plans for first service
            if ($service['slug'] === 'web-development') {
                $created->plans()->createMany([
                    [
                        'name' => 'Starter',
                        'price' => 2999,
                        'price_label' => 'starting from',
                        'features' => ['5 Pages', 'Responsive Design', 'Basic SEO', 'Contact Form', '1 Month Support'],
                        'cta_text' => 'Get Started',
                        'order' => 1,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Professional',
                        'price' => 7999,
                        'price_label' => 'starting from',
                        'features' => ['15 Pages', 'Custom Design', 'Advanced SEO', 'CMS Integration', 'E-commerce Ready', '3 Months Support'],
                        'cta_text' => 'Get Started',
                        'is_popular' => true,
                        'order' => 2,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Enterprise',
                        'price' => null,
                        'price_label' => 'Custom Quote',
                        'features' => ['Unlimited Pages', 'Custom Features', 'API Integration', 'Priority Support', 'Dedicated Manager', 'SLA Guarantee'],
                        'cta_text' => 'Contact Us',
                        'order' => 3,
                        'is_active' => true,
                    ],
                ]);
            }
        }
    }
}
