<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class RandomReferralSeeder extends Seeder
{
    private function generateUniqueReferralId()
    {
        do {
            $referralId = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        } while (User::where('referral_id', $referralId)->exists());
        return $referralId;
    }

    public function run(): void
    {
        $this->command->info('🌳 Growing a large random referral tree...');

        // Find or create a root user
        $rootUser = User::where('email', 'admin@example.com')->first();
        if (!$rootUser) {
            $rootUser = User::create([
                'name' => 'Main Admin',
                'email' => 'admin@example.com',
                'mobile' => '9999999999',
                'username' => 'ADMIN_ROOT',
                'password' => Hash::make('password123'),
                'referral_id' => $this->generateUniqueReferralId(),
                'is_active' => true,
                'role' => 'admin',
            ]);
        }

        $this->command->info("📍 Root node: {$rootUser->name} ({$rootUser->referral_id})");

        $levels = 4; // levels below root
        $minPerUser = 2;
        $maxPerUser = 4;
        
        $currentLevelUsers = [$rootUser];
        $totalCreated = 0;

        for ($i = 1; $i <= $levels; $i++) {
            $nextLevelUsers = [];
            foreach ($currentLevelUsers as $sponsor) {
                // Skip some users to create an uneven tree
                if (rand(1, 10) > 8 && $i > 1) continue;

                $count = rand($minPerUser, $maxPerUser);
                for ($j = 1; $j <= $count; $j++) {
                    $name = $this->generateRandomName();
                    $user = User::create([
                        'name' => $name,
                        'email' => strtolower(str_replace(' ', '.', $name)) . '.' . rand(100, 999) . '@example.com',
                        'mobile' => '9' . str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT),
                        'username' => strtoupper(Str::random(8)),
                        'password' => Hash::make('password123'),
                        'referral_id' => $this->generateUniqueReferralId(),
                        'sponsor_referral_id' => $sponsor->referral_id,
                        'is_active' => (rand(1, 10) > 2), // 80% active
                        'role' => 'user',
                    ]);
                    $nextLevelUsers[] = $user;
                    $totalCreated++;
                }
            }
            $this->command->info("✅ Level {$i}: Created " . count($nextLevelUsers) . " users");
            $currentLevelUsers = $nextLevelUsers;
            
            if (empty($currentLevelUsers)) break;
        }

        $this->command->info("✨ Tree growth complete! Total new users: {$totalCreated}");
    }

    private function generateRandomName()
    {
        $firstNames = ['Rahul', 'Amit', 'Priya', 'Sneha', 'Vikram', 'Anjali', 'Deepak', 'Suresh', 'Kavita', 'Arjun', 'Sanjay', 'Pooja', 'Neha', 'Rohan', 'Sunil', 'Anita'];
        $lastNames = ['Sharma', 'Verma', 'Gupta', 'Singh', 'Patel', 'Reddy', 'Kumar', 'Joshi', 'Nair', 'Mehta', 'Iyer', 'Khan', 'Desai'];
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
}
