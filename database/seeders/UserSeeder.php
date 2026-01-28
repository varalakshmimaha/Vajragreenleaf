<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@itbusiness.com',
            'mobile' => '9876543210',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'address' => 'Admin Office',
            'state' => 'State',
            'city' => 'City',
            'pincode' => '123456',
        ]);
    }
}
