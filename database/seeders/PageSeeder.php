<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionType;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Get section type IDs
        $sectionTypes = SectionType::pluck('id', 'slug')->toArray();

        // Create Home Page
        $homePage = Page::create([
            'title' => 'Home',
            'slug' => 'home',
            'meta_title' => 'Welcome to IT Business Solutions',
            'meta_description' => 'Leading IT solutions provider offering web development, cloud services, mobile apps, and digital transformation.',
            'is_active' => true,
            'is_homepage' => true,
        ]);

        // Add sections to home page
        $homeSections = [
            ['section_type_id' => $sectionTypes['banner'] ?? null, 'order' => 1],
            ['section_type_id' => $sectionTypes['about'] ?? null, 'order' => 2, 'settings' => [
                'title' => 'About Our Company',
                'subtitle' => 'Your Trusted Technology Partner',
                'content' => '<p>With over 15 years of experience, we have been helping businesses transform through technology. Our team of expert developers, designers, and strategists work together to deliver solutions that drive real results.</p><p>We believe in building long-term partnerships with our clients, understanding their unique challenges, and crafting tailored solutions that exceed expectations.</p>',
                'features' => [
                    'Experienced Team of 50+ Professionals',
                    '500+ Projects Successfully Delivered',
                    '24/7 Support & Maintenance',
                    'Agile Development Methodology'
                ],
            ]],
            ['section_type_id' => $sectionTypes['services'] ?? null, 'order' => 3, 'settings' => [
                'title' => 'Our Services',
                'subtitle' => 'Comprehensive IT Solutions',
                'show_featured' => true,
                'limit' => 6,
            ]],
            ['section_type_id' => $sectionTypes['how-we-work'] ?? null, 'order' => 4, 'settings' => [
                'title' => 'How We Work',
                'subtitle' => 'Our Proven Process',
            ]],
            ['section_type_id' => $sectionTypes['counters'] ?? null, 'order' => 5, 'settings' => [
                'background' => 'primary',
            ]],
            ['section_type_id' => $sectionTypes['portfolio'] ?? null, 'order' => 6, 'settings' => [
                'title' => 'Recent Projects',
                'subtitle' => 'Our Latest Work',
                'show_featured' => true,
                'limit' => 4,
            ]],
            ['section_type_id' => $sectionTypes['testimonials'] ?? null, 'order' => 7, 'settings' => [
                'title' => 'What Our Clients Say',
                'subtitle' => 'Trusted by Industry Leaders',
            ]],
            ['section_type_id' => $sectionTypes['team'] ?? null, 'order' => 8, 'settings' => [
                'title' => 'Meet Our Team',
                'subtitle' => 'The Experts Behind Our Success',
                'limit' => 4,
            ]],
            ['section_type_id' => $sectionTypes['clients'] ?? null, 'order' => 9, 'settings' => [
                'title' => 'Trusted By',
            ]],
            ['section_type_id' => $sectionTypes['blog'] ?? null, 'order' => 10, 'settings' => [
                'title' => 'Latest Insights',
                'subtitle' => 'From Our Blog',
                'limit' => 3,
            ]],
            ['section_type_id' => $sectionTypes['cta'] ?? null, 'order' => 11, 'settings' => [
                'title' => 'Ready to Start Your Project?',
                'subtitle' => 'Let\'s discuss how we can help transform your business with technology.',
                'cta_text' => 'Get in Touch',
                'cta_url' => '/contact',
            ]],
        ];

        foreach ($homeSections as $section) {
            if ($section['section_type_id']) {
                PageSection::create([
                    'page_id' => $homePage->id,
                    'section_type_id' => $section['section_type_id'],
                    'settings' => $section['settings'] ?? null,
                    'order' => $section['order'],
                    'is_active' => true,
                ]);
            }
        }

        // Create About Page
        $aboutPage = Page::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'meta_title' => 'About Us - IT Business Solutions',
            'meta_description' => 'Learn about our company, mission, vision, and the team behind our success.',
            'is_active' => true,
        ]);

        $aboutSections = [
            ['section_type_id' => $sectionTypes['about'] ?? null, 'order' => 1, 'settings' => [
                'title' => 'Who We Are',
                'subtitle' => 'A Leading IT Solutions Provider',
                'content' => '<p>Founded in 2010, we have grown from a small startup to a leading IT solutions provider serving clients worldwide. Our journey has been driven by a simple belief: technology should empower businesses, not complicate them.</p><p>Today, we are a team of 50+ passionate professionals including developers, designers, cloud architects, and project managers. We have successfully delivered over 500 projects across various industries including healthcare, finance, retail, and education.</p><h3>Our Mission</h3><p>To deliver innovative, reliable, and scalable technology solutions that help businesses thrive in the digital age.</p><h3>Our Vision</h3><p>To be the most trusted technology partner for businesses seeking digital transformation.</p>',
            ]],
            ['section_type_id' => $sectionTypes['counters'] ?? null, 'order' => 2],
            ['section_type_id' => $sectionTypes['team'] ?? null, 'order' => 3, 'settings' => [
                'title' => 'Our Leadership Team',
                'subtitle' => 'Meet the People Driving Our Success',
            ]],
            ['section_type_id' => $sectionTypes['how-we-work'] ?? null, 'order' => 4, 'settings' => [
                'title' => 'Our Process',
                'subtitle' => 'How We Deliver Excellence',
            ]],
            ['section_type_id' => $sectionTypes['clients'] ?? null, 'order' => 5],
            ['section_type_id' => $sectionTypes['cta'] ?? null, 'order' => 6, 'settings' => [
                'title' => 'Want to Join Our Team?',
                'subtitle' => 'We are always looking for talented individuals to join our growing team.',
                'cta_text' => 'View Careers',
                'cta_url' => '/careers',
            ]],
        ];

        foreach ($aboutSections as $section) {
            if ($section['section_type_id']) {
                PageSection::create([
                    'page_id' => $aboutPage->id,
                    'section_type_id' => $section['section_type_id'],
                    'settings' => $section['settings'] ?? null,
                    'order' => $section['order'],
                    'is_active' => true,
                ]);
            }
        }

        // Create Privacy Policy Page
        Page::create([
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'content' => '<h2>Privacy Policy</h2><p>Last updated: January 2025</p><p>This privacy policy describes how we collect, use, and protect your personal information when you use our website and services.</p><h3>Information We Collect</h3><p>We collect information you provide directly, such as when you fill out a contact form, request a quote, or subscribe to our newsletter.</p><h3>How We Use Your Information</h3><p>We use your information to respond to your inquiries, provide our services, and improve our website.</p><h3>Data Security</h3><p>We implement appropriate security measures to protect your personal information.</p><h3>Contact Us</h3><p>If you have questions about this privacy policy, please contact us.</p>',
            'meta_title' => 'Privacy Policy - IT Business Solutions',
            'meta_description' => 'Our privacy policy explains how we collect, use, and protect your personal information.',
            'is_active' => true,
        ]);

        // Create Terms of Service Page
        Page::create([
            'title' => 'Terms of Service',
            'slug' => 'terms-of-service',
            'content' => '<h2>Terms of Service</h2><p>Last updated: January 2025</p><p>By using our website and services, you agree to these terms of service.</p><h3>Use of Services</h3><p>You agree to use our services only for lawful purposes and in accordance with these terms.</p><h3>Intellectual Property</h3><p>All content on this website, including text, graphics, logos, and software, is our property or licensed to us.</p><h3>Limitation of Liability</h3><p>We are not liable for any indirect, incidental, or consequential damages arising from your use of our services.</p><h3>Changes to Terms</h3><p>We may update these terms from time to time. Continued use of our services constitutes acceptance of any changes.</p>',
            'meta_title' => 'Terms of Service - IT Business Solutions',
            'meta_description' => 'Read our terms of service to understand the rules and regulations for using our website and services.',
            'is_active' => true,
        ]);
    }
}
