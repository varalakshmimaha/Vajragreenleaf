<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ReferralDummyDataSeeder extends Seeder
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
        $this->command->info('🌱 Creating dummy referral network...');
        
        // Root User
        $rootUser = User::create([
            'name' => 'Demo Root User',
            'email' => 'demo.root@example.com',
            'mobile' => '9000000001',
            'username' => 'DEMO_ROOT',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => null,
            'address' => '123 Demo Street',
            'city' => 'Hyderabad',
            'state' => 'Telangana',
            'pincode' => '500001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $this->command->info("✅ Created root user: {$rootUser->name} (ID: {$rootUser->referral_id})");

        // Level 1 - 3 users
        $l1_1 = User::create([
            'name' => 'Level1 User A',
            'email' => 'level1a@example.com',
            'mobile' => '9000000002',
            'username' => 'DEMO_L1_A',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $rootUser->referral_id,
            'address' => '456 Demo Avenue',
            'city' => 'Bangalore',
            'state' => 'Karnataka',
            'pincode' => '560001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $l1_2 = User::create([
            'name' => 'Level1 User B',
            'email' => 'level1b@example.com',
            'mobile' => '9000000003',
            'username' => 'DEMO_L1_B',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $rootUser->referral_id,
            'address' => '789 Demo Road',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $l1_3 = User::create([
            'name' => 'Level1 User C',
            'email' => 'level1c@example.com',
            'mobile' => '9000000004',
            'username' => 'DEMO_L1_C',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $rootUser->referral_id,
            'address' => '321 Demo Plaza',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'pincode' => '110001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $this->command->info("✅ Created 3 Level 1 users");

        // Level 2 - 5 users
        $l2_1 = User::create([
            'name' => 'Level2 User A1',
            'email' => 'level2a1@example.com',
            'mobile' => '9000000005',
            'username' => 'DEMO_L2_A1',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l1_1->referral_id,
            'address' => '111 Demo Park',
            'city' => 'Pune',
            'state' => 'Maharashtra',
            'pincode' => '411001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $l2_2 = User::create([
            'name' => 'Level2 User A2',
            'email' => 'level2a2@example.com',
            'mobile' => '9000000006',
            'username' => 'DEMO_L2_A2',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l1_1->referral_id,
            'address' => '222 Demo Hills',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'pincode' => '380001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $l2_3 = User::create([
            'name' => 'Level2 User B1',
            'email' => 'level2b1@example.com',
            'mobile' => '9000000007',
            'username' => 'DEMO_L2_B1',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l1_2->referral_id,
            'address' => '333 Demo Valley',
            'city' => 'Chennai',
            'state' => 'Tamil Nadu',
            'pincode' => '600001',
            'is_active' => false, // Inactive
            'role' => 'user',
        ]);

        $l2_4 = User::create([
            'name' => 'Level2 User B2',
            'email' => 'level2b2@example.com',
            'mobile' => '9000000008',
            'username' => 'DEMO_L2_B2',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l1_2->referral_id,
            'address' => '444 Demo Heights',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $l2_5 = User::create([
            'name' => 'Level2 User C1',
            'email' => 'level2c1@example.com',
            'mobile' => '9000000009',
            'username' => 'DEMO_L2_C1',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l1_3->referral_id,
            'address' => '555 Demo Gardens',
            'city' => 'Kolkata',
            'state' => 'West Bengal',
            'pincode' => '700001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $this->command->info("✅ Created 5 Level 2 users");

        // Level 3 - 4 users
        $l3_1 = User::create([
            'name' => 'Level3 User A11',
            'email' => 'level3a11@example.com',
            'mobile' => '9000000010',
            'username' => 'DEMO_L3_A11',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l2_1->referral_id,
            'address' => '666 Demo Corner',
            'city' => 'Indore',
            'state' => 'Madhya Pradesh',
            'pincode' => '452001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $l3_2 = User::create([
            'name' => 'Level3 User A12',
            'email' => 'level3a12@example.com',
            'mobile' => '9000000011',
            'username' => 'DEMO_L3_A12',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l2_1->referral_id,
            'address' => '777 Demo Square',
            'city' => 'Nagpur',
            'state' => 'Maharashtra',
            'pincode' => '440001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $l3_3 = User::create([
            'name' => 'Level3 User A21',
            'email' => 'level3a21@example.com',
            'mobile' => '9000000012',
            'username' => 'DEMO_L3_A21',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l2_2->referral_id,
            'address' => '888 Demo Circle',
            'city' => 'Surat',
            'state' => 'Gujarat',
            'pincode' => '395001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $l3_4 = User::create([
            'name' => 'Level3 User B21',
            'email' => 'level3b21@example.com',
            'mobile' => '9000000013',
            'username' => 'DEMO_L3_B21',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l2_4->referral_id,
            'address' => '999 Demo Lane',
            'city' => 'Kochi',
            'state' => 'Kerala',
            'pincode' => '682001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $this->command->info("✅ Created 4 Level 3 users");

        // Level 4 - 2 users (to test "Load More")
        $l4_1 = User::create([
            'name' => 'Level4 User A111',
            'email' => 'level4a111@example.com',
            'mobile' => '9000000014',
            'username' => 'DEMO_L4_A111',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l3_1->referral_id,
            'address' => '1010 Demo Tower',
            'city' => 'Visakhapatnam',
            'state' => 'Andhra Pradesh',
            'pincode' => '530001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $l4_2 = User::create([
            'name' => 'Level4 User A121',
            'email' => 'level4a121@example.com',
            'mobile' => '9000000015',
            'username' => 'DEMO_L4_A121',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l3_2->referral_id,
            'address' => '1111 Demo Castle',
            'city' => 'Coimbatore',
            'state' => 'Tamil Nadu',
            'pincode' => '641001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $this->command->info("✅ Created 2 Level 4 users");

        // Level 5 - 3 users (deep network testing)
        User::create([
            'name' => 'Level5 User A1111',
            'email' => 'level5a1111@example.com',
            'mobile' => '9000000016',
            'username' => 'DEMO_L5_A1111',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l4_1->referral_id ?? $this->generateUniqueReferralId(),
            'address' => '1212 Demo Heights',
            'city' => 'Mysore',
            'state' => 'Karnataka',
            'pincode' => '570001',
            'is_active' => true,
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Level5 User A1211',
            'email' => 'level5a1211@example.com',
            'mobile' => '9000000017',
            'username' => 'DEMO_L5_A1211',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l4_2->referral_id ?? $this->generateUniqueReferralId(),
            'address' => '1313 Demo Residency',
            'city' => 'Madurai',
            'state' => 'Tamil Nadu',
            'pincode' => '625001',
            'is_active' => true,
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Level5 User Deep',
            'email' => 'level5deep@example.com',
            'mobile' => '9000000018',
            'username' => 'DEMO_L5_DEEP',
            'password' => Hash::make('password123'),
            'referral_id' => $this->generateUniqueReferralId(),
            'sponsor_referral_id' => $l4_1->referral_id ?? $this->generateUniqueReferralId(),
            'address' => '1414 Demo Skyline',
            'city' => 'Lucknow',
            'state' => 'Uttar Pradesh',
            'pincode' => '226001',
            'is_active' => true,
            'role' => 'user',
        ]);

        $this->command->info("✅ Created 3 Level 5 users");
        $this->command->info('');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('✅ DUMMY REFERRAL NETWORK CREATED!');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info("   Root User: {$rootUser->name}");
        $this->command->info("   Referral ID: {$rootUser->referral_id}");
        $this->command->info('   Level 1: 3 users');
        $this->command->info('   Level 2: 5 users');
        $this->command->info('   Level 3: 4 users');
        $this->command->info('   Level 4: 2 users');
        $this->command->info('   Level 5: 3 users');
        $this->command->info('   Total: 18 users');
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('   Email: Any demo email above');
        $this->command->info('   Password: password123');
        $this->command->info('');
        $this->command->info('🌐 View Referral Network:');
        $this->command->info("   http://127.0.0.1:8000/admin/users/{$rootUser->id}");
        $this->command->info('');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
