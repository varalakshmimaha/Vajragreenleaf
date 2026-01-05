<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SiteSettingsSeeder::class,
            MenuSeeder::class,
            ThemeSeeder::class,
            SectionTypeSeeder::class,
            ServiceSeeder::class,
            ProductSeeder::class,
            PortfolioSeeder::class,
            BlogSeeder::class,
            TeamSeeder::class,
            TestimonialSeeder::class,
            ClientSeeder::class,
            CounterSeeder::class,
            HowWeWorkSeeder::class,
            BannerSeeder::class,
            PageSeeder::class,
        ]);
    }
}
