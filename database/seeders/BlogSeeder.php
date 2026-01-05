<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology', 'order' => 1],
            ['name' => 'Development', 'slug' => 'development', 'order' => 2],
            ['name' => 'Business', 'slug' => 'business', 'order' => 3],
            ['name' => 'Cloud Computing', 'slug' => 'cloud-computing', 'order' => 4],
        ];

        foreach ($categories as $category) {
            BlogCategory::create(array_merge($category, ['is_active' => true]));
        }

        $blogs = [
            [
                'category_id' => 1,
                'title' => 'The Future of AI in Business: Trends to Watch in 2025',
                'slug' => 'future-of-ai-in-business-2025',
                'excerpt' => 'Explore how artificial intelligence is reshaping industries and what business leaders need to know to stay competitive.',
                'content' => '<p>Artificial Intelligence continues to revolutionize how businesses operate. From automated customer service to predictive analytics, AI is no longer a futuristic concept but a present-day necessity.</p><h2>Key AI Trends for 2025</h2><p>The integration of generative AI into business workflows is accelerating at an unprecedented pace. Companies that embrace these technologies are seeing significant improvements in productivity and customer satisfaction.</p><h3>1. Automated Decision Making</h3><p>AI-powered systems are increasingly capable of making complex decisions that previously required human intervention. This shift is enabling faster response times and more consistent outcomes.</p><h3>2. Personalization at Scale</h3><p>Machine learning algorithms are enabling businesses to deliver highly personalized experiences to millions of customers simultaneously.</p><h3>3. Predictive Maintenance</h3><p>IoT sensors combined with AI are helping manufacturers predict equipment failures before they occur, reducing downtime and maintenance costs.</p>',
                'author_name' => 'Sarah Johnson',
                'published_at' => now()->subDays(2),
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'category_id' => 2,
                'title' => 'Best Practices for Building Scalable Web Applications',
                'slug' => 'best-practices-scalable-web-applications',
                'excerpt' => 'Learn the architectural patterns and coding practices that enable web applications to handle millions of users.',
                'content' => '<p>Building a web application that can scale to handle millions of users requires careful planning and the right architectural decisions from the start.</p><h2>Core Principles of Scalable Architecture</h2><p>Scalability is not an afterthought—it should be baked into your application from day one.</p><h3>Microservices vs Monolith</h3><p>Understanding when to use microservices architecture and when a well-designed monolith is the better choice is crucial for long-term success.</p><h3>Caching Strategies</h3><p>Implementing proper caching at various levels—browser, CDN, application, and database—can dramatically improve performance and reduce server load.</p><h3>Database Optimization</h3><p>From indexing strategies to read replicas, optimizing your database layer is essential for handling high traffic volumes.</p>',
                'author_name' => 'Michael Chen',
                'published_at' => now()->subDays(5),
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'category_id' => 3,
                'title' => 'Digital Transformation: A Roadmap for Success',
                'slug' => 'digital-transformation-roadmap-success',
                'excerpt' => 'A comprehensive guide to navigating digital transformation in your organization.',
                'content' => '<p>Digital transformation is more than just adopting new technologies—it is a fundamental reimagining of how your business operates and delivers value to customers.</p><h2>The Four Pillars of Digital Transformation</h2><h3>1. Technology Integration</h3><p>Modernizing your technology stack is the foundation, but it must be done strategically with clear business objectives in mind.</p><h3>2. Process Optimization</h3><p>Digitizing existing processes is not enough. True transformation requires rethinking how work gets done.</p><h3>3. Cultural Change</h3><p>Perhaps the most challenging aspect is shifting organizational culture to embrace continuous change and innovation.</p><h3>4. Customer Experience</h3><p>Every digital initiative should ultimately improve how customers interact with your business.</p>',
                'author_name' => 'Emily Rodriguez',
                'published_at' => now()->subDays(8),
                'is_featured' => true,
                'order' => 3,
            ],
            [
                'category_id' => 4,
                'title' => 'AWS vs Azure vs GCP: Choosing the Right Cloud Provider',
                'slug' => 'aws-azure-gcp-comparison',
                'excerpt' => 'An in-depth comparison of the three major cloud providers to help you make the right choice.',
                'content' => '<p>Choosing the right cloud provider is a critical decision that will impact your organization for years. Each of the major providers has unique strengths and considerations.</p><h2>Amazon Web Services (AWS)</h2><p>The market leader with the most comprehensive service catalog and largest community.</p><h2>Microsoft Azure</h2><p>Best for organizations heavily invested in the Microsoft ecosystem with strong enterprise features.</p><h2>Google Cloud Platform (GCP)</h2><p>Leading in machine learning and data analytics with competitive pricing.</p><h2>Making Your Decision</h2><p>Consider your existing technology stack, team expertise, and specific workload requirements when making your choice.</p>',
                'author_name' => 'David Park',
                'published_at' => now()->subDays(12),
                'is_featured' => false,
                'order' => 4,
            ],
            [
                'category_id' => 2,
                'title' => 'Introduction to Laravel 11: What\'s New',
                'slug' => 'introduction-laravel-11-whats-new',
                'excerpt' => 'Discover the exciting new features and improvements in Laravel 11.',
                'content' => '<p>Laravel 11 brings a host of new features and improvements that make PHP development even more enjoyable and productive.</p><h2>Streamlined Application Structure</h2><p>Laravel 11 introduces a more minimal application skeleton, reducing boilerplate and improving clarity.</p><h2>New Health Check Endpoint</h2><p>Built-in health check functionality makes it easier to monitor application status in production environments.</p><h2>Per-Second Rate Limiting</h2><p>More granular rate limiting options provide better control over API throttling.</p>',
                'author_name' => 'Alex Thompson',
                'published_at' => now()->subDays(15),
                'is_featured' => false,
                'order' => 5,
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create(array_merge($blog, ['is_active' => true]));
        }
    }
}
