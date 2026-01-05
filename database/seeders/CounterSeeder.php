<?php

namespace Database\Seeders;

use App\Models\Counter;
use Illuminate\Database\Seeder;

class CounterSeeder extends Seeder
{
    public function run(): void
    {
        $counters = [
            [
                'title' => 'Projects Completed',
                'value' => 500,
                'suffix' => '+',
                'icon' => 'fa-solid fa-check-circle',
                'order' => 1,
            ],
            [
                'title' => 'Happy Clients',
                'value' => 200,
                'suffix' => '+',
                'icon' => 'fa-solid fa-smile',
                'order' => 2,
            ],
            [
                'title' => 'Team Members',
                'value' => 50,
                'suffix' => '+',
                'icon' => 'fa-solid fa-users',
                'order' => 3,
            ],
            [
                'title' => 'Years Experience',
                'value' => 15,
                'suffix' => '+',
                'icon' => 'fa-solid fa-trophy',
                'order' => 4,
            ],
        ];

        foreach ($counters as $counter) {
            Counter::create(array_merge($counter, ['is_active' => true]));
        }
    }
}
