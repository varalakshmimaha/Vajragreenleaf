<?php

namespace Database\Seeders;

use App\Models\Career;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        Career::create([
            'title' => 'Senior Full Stack Developer',
            'slug' => 'senior-full-stack-developer',
            'department' => 'Engineering',
            'location' => 'Remote / Bangalore, India',
            'job_type' => 'full-time',
            'experience_level' => 'senior',
            'short_description' => 'Join our dynamic team as a Senior Full Stack Developer and work on cutting-edge web applications.',
            'description' => "<h3>About the Role</h3><p>We are looking for a Senior Full Stack Developer to join our dynamic team. You will be responsible for developing and maintaining web applications using modern technologies.</p><h3>Responsibilities</h3><ul><li>Design and develop scalable web applications</li><li>Lead technical discussions and code reviews</li><li>Mentor junior developers</li><li>Collaborate with product and design teams</li><li>Write clean, maintainable, and well-documented code</li><li>Participate in architectural decisions</li></ul>",
            'requirements' => "<ul><li>5+ years of experience in web development</li><li>Strong proficiency in PHP, Laravel, JavaScript</li><li>Experience with React or Vue.js</li><li>Familiarity with MySQL, PostgreSQL</li><li>Experience with Git version control</li><li>Excellent problem-solving skills</li></ul>",
            'benefits' => "<ul><li>Competitive salary</li><li>Health insurance</li><li>Flexible working hours</li><li>Remote work options</li><li>Professional development budget</li><li>Annual team outings</li></ul>",
            'salary_range' => '18-25 LPA',
            'positions' => 2,
            'application_deadline' => now()->addDays(30),
            'is_active' => true,
            'is_featured' => true,
        ]);

        Career::create([
            'title' => 'UI/UX Designer',
            'slug' => 'ui-ux-designer',
            'department' => 'Design',
            'location' => 'Mumbai, India',
            'job_type' => 'full-time',
            'experience_level' => 'mid',
            'short_description' => 'Create amazing user experiences as our UI/UX Designer.',
            'description' => "<h3>About the Role</h3><p>We are seeking a creative UI/UX Designer to create amazing user experiences. The ideal candidate should have an eye for clean design and possess superior UI/UX skills.</p><h3>Responsibilities</h3><ul><li>Create user-centered designs by understanding business requirements</li><li>Develop wireframes, prototypes, and high-fidelity mockups</li><li>Conduct user research and usability testing</li><li>Collaborate with developers for implementation</li><li>Maintain design systems and brand guidelines</li></ul>",
            'requirements' => "<ul><li>3+ years of experience in UI/UX design</li><li>Proficiency in Figma, Adobe XD, or Sketch</li><li>Strong portfolio showcasing design projects</li><li>Understanding of user-centered design principles</li><li>Excellent communication skills</li></ul>",
            'benefits' => "<ul><li>Competitive salary</li><li>Health insurance</li><li>Creative freedom</li><li>Modern office space</li><li>Learning opportunities</li></ul>",
            'salary_range' => '10-15 LPA',
            'positions' => 1,
            'application_deadline' => now()->addDays(45),
            'is_active' => true,
            'is_featured' => true,
        ]);

        Career::create([
            'title' => 'DevOps Engineer',
            'slug' => 'devops-engineer',
            'department' => 'Engineering',
            'location' => 'Remote',
            'job_type' => 'remote',
            'experience_level' => 'mid',
            'short_description' => 'Help us build and maintain our cloud infrastructure as a DevOps Engineer.',
            'description' => "<h3>About the Role</h3><p>We are looking for a DevOps Engineer to help us build and maintain our cloud infrastructure. You will work closely with our development team to ensure smooth deployments and reliable systems.</p><h3>Responsibilities</h3><ul><li>Manage and optimize AWS/GCP cloud infrastructure</li><li>Implement CI/CD pipelines</li><li>Automate deployment processes</li><li>Monitor system performance and security</li><li>Troubleshoot and resolve infrastructure issues</li></ul>",
            'requirements' => "<ul><li>3+ years of DevOps experience</li><li>Experience with AWS, GCP, or Azure</li><li>Knowledge of Docker, Kubernetes</li><li>Experience with CI/CD tools (Jenkins, GitLab CI)</li><li>Strong Linux administration skills</li><li>Scripting skills (Bash, Python)</li></ul>",
            'benefits' => "<ul><li>Competitive salary</li><li>Fully remote position</li><li>Flexible hours</li><li>Stock options</li><li>Annual learning budget</li></ul>",
            'salary_range' => '12-18 LPA',
            'positions' => 1,
            'application_deadline' => now()->addDays(30),
            'is_active' => true,
            'is_featured' => false,
        ]);

        Career::create([
            'title' => 'Marketing Intern',
            'slug' => 'marketing-intern',
            'department' => 'Marketing',
            'location' => 'Bangalore, India',
            'job_type' => 'internship',
            'experience_level' => 'entry',
            'short_description' => 'Gain hands-on experience in digital marketing and content creation.',
            'description' => "<h3>About the Role</h3><p>Join our marketing team as an intern and gain hands-on experience in digital marketing, content creation, and social media management.</p><h3>What You'll Do</h3><ul><li>Assist in social media content creation</li><li>Help with email marketing campaigns</li><li>Conduct market research</li><li>Support the marketing team in daily activities</li></ul>",
            'requirements' => "<ul><li>Currently pursuing or recently completed degree in Marketing/Communications</li><li>Strong written and verbal communication</li><li>Basic knowledge of social media platforms</li><li>Enthusiastic and eager to learn</li></ul>",
            'benefits' => "<ul><li>Stipend provided</li><li>Certificate upon completion</li><li>Possibility of full-time offer</li><li>Mentorship from experienced professionals</li></ul>",
            'salary_range' => '15,000-25,000/month',
            'positions' => 3,
            'application_deadline' => now()->addDays(15),
            'is_active' => true,
            'is_featured' => false,
        ]);
    }
}
