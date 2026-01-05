<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $team = [
            [
                'name' => 'John Anderson',
                'slug' => 'john-anderson',
                'designation' => 'CEO & Founder',
                'bio' => 'With over 20 years of experience in the technology industry, John founded the company with a vision to deliver innovative IT solutions that drive business growth.',
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/johnanderson',
                    'twitter' => 'https://twitter.com/johnanderson',
                ],
                'order' => 1,
            ],
            [
                'name' => 'Sarah Mitchell',
                'slug' => 'sarah-mitchell',
                'designation' => 'Chief Technology Officer',
                'bio' => 'Sarah leads our technical strategy and innovation initiatives. She brings deep expertise in cloud architecture and enterprise software development.',
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/sarahmitchell',
                    'github' => 'https://github.com/sarahmitchell',
                ],
                'order' => 2,
            ],
            [
                'name' => 'Michael Chen',
                'slug' => 'michael-chen',
                'designation' => 'VP of Engineering',
                'bio' => 'Michael oversees all engineering teams and ensures we deliver high-quality solutions. He is passionate about building scalable systems and mentoring developers.',
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/michaelchen',
                    'github' => 'https://github.com/michaelchen',
                ],
                'order' => 3,
            ],
            [
                'name' => 'Emily Rodriguez',
                'slug' => 'emily-rodriguez',
                'designation' => 'Head of Design',
                'bio' => 'Emily leads our design team creating beautiful and intuitive user experiences. Her work has won multiple industry awards for innovation in UX design.',
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/emilyrodriguez',
                    'dribbble' => 'https://dribbble.com/emilyrodriguez',
                ],
                'order' => 4,
            ],
            [
                'name' => 'David Park',
                'slug' => 'david-park',
                'designation' => 'Cloud Solutions Architect',
                'bio' => 'David specializes in designing and implementing enterprise cloud solutions. He holds certifications from AWS, Azure, and GCP.',
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/davidpark',
                ],
                'order' => 5,
            ],
            [
                'name' => 'Jessica Williams',
                'slug' => 'jessica-williams',
                'designation' => 'Project Manager',
                'bio' => 'Jessica ensures our projects are delivered on time and exceed client expectations. She is a certified PMP with expertise in Agile methodologies.',
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/jessicawilliams',
                ],
                'order' => 6,
            ],
            [
                'name' => 'Robert Taylor',
                'slug' => 'robert-taylor',
                'designation' => 'Senior Full-Stack Developer',
                'bio' => 'Robert is a versatile developer with expertise in both frontend and backend technologies. He loves solving complex problems with elegant solutions.',
                'social_links' => [
                    'github' => 'https://github.com/roberttaylor',
                    'linkedin' => 'https://linkedin.com/in/roberttaylor',
                ],
                'order' => 7,
            ],
            [
                'name' => 'Amanda Foster',
                'slug' => 'amanda-foster',
                'designation' => 'Business Development Manager',
                'bio' => 'Amanda builds strategic partnerships and helps clients find the right solutions for their business challenges. She is passionate about technology and customer success.',
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/amandafoster',
                ],
                'order' => 8,
            ],
        ];

        foreach ($team as $member) {
            Team::create(array_merge($member, ['is_active' => true]));
        }
    }
}
