<?php

namespace Database\Seeders;

use App\Models\HowWeWorkStep;
use Illuminate\Database\Seeder;

class HowWeWorkSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                'title' => 'Discovery & Planning',
                'description' => 'We start by understanding your business goals, challenges, and requirements. Our team conducts thorough research and creates a detailed project roadmap.',
                'icon' => 'fa-solid fa-lightbulb',
                'order' => 1,
            ],
            [
                'title' => 'Design & Prototype',
                'description' => 'Our designers create intuitive user interfaces and interactive prototypes. We iterate based on your feedback until the design perfectly matches your vision.',
                'icon' => 'fa-solid fa-pencil-ruler',
                'order' => 2,
            ],
            [
                'title' => 'Development',
                'description' => 'Our expert developers bring the designs to life using the latest technologies and best practices. We follow agile methodologies for transparent progress.',
                'icon' => 'fa-solid fa-code',
                'order' => 3,
            ],
            [
                'title' => 'Testing & QA',
                'description' => 'Rigorous testing ensures your solution works flawlessly. We perform functional, performance, security, and user acceptance testing.',
                'icon' => 'fa-solid fa-bug',
                'order' => 4,
            ],
            [
                'title' => 'Deployment',
                'description' => 'We handle the complete deployment process, ensuring smooth transition to production. Our team sets up monitoring and backup systems.',
                'icon' => 'fa-solid fa-rocket',
                'order' => 5,
            ],
            [
                'title' => 'Support & Maintenance',
                'description' => 'Our relationship does not end at launch. We provide ongoing support, updates, and optimization to keep your solution running at peak performance.',
                'icon' => 'fa-solid fa-headset',
                'order' => 6,
            ],
        ];

        foreach ($steps as $step) {
            HowWeWorkStep::create(array_merge($step, ['is_active' => true]));
        }
    }
}
