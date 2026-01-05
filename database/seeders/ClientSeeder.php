<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => 'TechCorp', 'website' => 'https://techcorp.example.com', 'order' => 1],
            ['name' => 'GlobalBank', 'website' => 'https://globalbank.example.com', 'order' => 2],
            ['name' => 'HealthPlus', 'website' => 'https://healthplus.example.com', 'order' => 3],
            ['name' => 'RetailMax', 'website' => 'https://retailmax.example.com', 'order' => 4],
            ['name' => 'EduLearn', 'website' => 'https://edulearn.example.com', 'order' => 5],
            ['name' => 'LogiFlow', 'website' => 'https://logiflow.example.com', 'order' => 6],
            ['name' => 'FinanceHub', 'website' => 'https://financehub.example.com', 'order' => 7],
            ['name' => 'MediaPro', 'website' => 'https://mediapro.example.com', 'order' => 8],
            ['name' => 'CloudNine', 'website' => 'https://cloudnine.example.com', 'order' => 9],
            ['name' => 'DataDriven', 'website' => 'https://datadriven.example.com', 'order' => 10],
        ];

        foreach ($clients as $client) {
            Client::create(array_merge($client, ['is_active' => true]));
        }
    }
}
