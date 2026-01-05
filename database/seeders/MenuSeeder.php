<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Header Menu
        $headerMenu = Menu::create([
            'name' => 'Main Navigation',
            'location' => 'header',
            'is_active' => true,
        ]);

        $headerItems = [
            ['title' => 'Home', 'url' => '/', 'order' => 1],
            ['title' => 'About Us', 'url' => '/about-us', 'order' => 2],
            ['title' => 'Services', 'url' => '/services', 'order' => 3],
            ['title' => 'Products', 'url' => '/products', 'order' => 4],
            ['title' => 'Portfolio', 'url' => '/portfolio', 'order' => 5],
            ['title' => 'Blog', 'url' => '/blog', 'order' => 6],
            ['title' => 'Contact', 'url' => '/contact', 'order' => 7],
        ];

        foreach ($headerItems as $item) {
            $headerMenu->allItems()->create($item);
        }

        // Footer Column 1
        $footerCol1 = Menu::create([
            'name' => 'Quick Links',
            'location' => 'footer_col1',
            'is_active' => true,
        ]);

        $footerCol1Items = [
            ['title' => 'About Us', 'url' => '/about-us', 'order' => 1],
            ['title' => 'Our Services', 'url' => '/services', 'order' => 2],
            ['title' => 'Portfolio', 'url' => '/portfolio', 'order' => 3],
            ['title' => 'Blog', 'url' => '/blog', 'order' => 4],
            ['title' => 'Contact Us', 'url' => '/contact', 'order' => 5],
        ];

        foreach ($footerCol1Items as $item) {
            $footerCol1->allItems()->create($item);
        }

        // Footer Column 2
        $footerCol2 = Menu::create([
            'name' => 'Our Services',
            'location' => 'footer_col2',
            'is_active' => true,
        ]);

        $footerCol2Items = [
            ['title' => 'Web Development', 'url' => '/services/web-development', 'order' => 1],
            ['title' => 'Mobile Apps', 'url' => '/services/mobile-apps', 'order' => 2],
            ['title' => 'Cloud Solutions', 'url' => '/services/cloud-solutions', 'order' => 3],
            ['title' => 'UI/UX Design', 'url' => '/services/ui-ux-design', 'order' => 4],
        ];

        foreach ($footerCol2Items as $item) {
            $footerCol2->allItems()->create($item);
        }

        // Footer Column 3 (Legal)
        $footerCol3 = Menu::create([
            'name' => 'Legal',
            'location' => 'footer_col3',
            'is_active' => true,
        ]);

        $footerCol3Items = [
            ['title' => 'Privacy Policy', 'url' => '/privacy-policy', 'order' => 1],
            ['title' => 'Terms of Service', 'url' => '/terms-of-service', 'order' => 2],
        ];

        foreach ($footerCol3Items as $item) {
            $footerCol3->allItems()->create($item);
        }
    }
}
