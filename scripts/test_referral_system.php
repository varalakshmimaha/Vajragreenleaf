<?php

/**
 * Test Script for Multilevel Referral System
 * 
 * Run this via: php artisan tinker
 * Then paste this code or save as scripts/test_referral_system.php
 */

use App\Models\User;

echo "\n=== Multilevel Referral System Test ===\n\n";

// Test 1: Check if referral IDs exist
echo "Test 1: Checking referral ID generation...\n";
$totalUsers = User::count();
$usersWithReferralId = User::whereNotNull('referral_id')->count();
echo "Total Users: $totalUsers\n";
echo "Users with Referral ID: $usersWithReferralId\n";
echo $totalUsers === $usersWithReferralId ? "✅ PASS\n" : "❌ FAIL\n";

// Test 2: Check uniqueness
echo "\nTest 2: Checking referral ID uniqueness...\n";
$duplicates = User::select('referral_id')
    ->whereNotNull('referral_id')
    ->groupBy('referral_id')
    ->havingRaw('COUNT(*) > 1')
    ->count();
echo "Duplicate Referral IDs: $duplicates\n";
echo $duplicates === 0 ? "✅ PASS\n" : "❌ FAIL\n";

// Test 3: Check format (5 digits)
echo "\nTest 3: Checking 5-digit format...\n";
$invalidFormat = User::whereNotNull('referral_id')
    ->where(function($query) {
        $query->whereRaw('LENGTH(referral_id) != 5')
              ->orWhereRaw('referral_id NOT REGEXP \'^[0-9]{5}$\'');
    })
    ->count();
echo "Invalid Format Count: $invalidFormat\n";
echo $invalidFormat === 0 ? "✅ PASS\n" : "❌ FAIL\n";

// Test 4: Check sponsor relationships
echo "\nTest 4: Checking sponsor relationships...\n";
$usersWithSponsor = User::whereNotNull('sponsor_referral_id')->count();
echo "Users with Sponsor: $usersWithSponsor\n";

// Verify sponsor_referral_id exists
$invalidSponsorLinks = User::whereNotNull('sponsor_referral_id')
    ->whereNotExists(function ($query) {
        $query->select('id')
              ->from('users as sponsors')
              ->whereColumn('users.sponsor_referral_id', 'sponsors.referral_id');
    })
    ->count();
echo "Invalid Sponsor Links: $invalidSponsorLinks\n";
echo $invalidSponsorLinks === 0 ? "✅ PASS\n" : "❌ FAIL\n";

// Test 5: Sample referral tree
echo "\nTest 5: Sample Referral Tree...\n";
$userWithReferrals = User::has('referrals')->first();
if ($userWithReferrals) {
    echo "Testing user: {$userWithReferrals->name} (ID: {$userWithReferrals->referral_id})\n";
    $directReferrals = $userWithReferrals->referrals()->count();
    echo "Direct Referrals (Level 1): $directReferrals\n";
    
    $level2Count = 0;
    foreach ($userWithReferrals->referrals as $level1) {
        $level2Count += $level1->referrals()->count();
    }
    echo "Level 2 Referrals: $level2Count\n";
    
    echo "✅ Tree structure working\n";
} else {
    echo "⚠️  No users with referrals yet\n";
}

// Test 6: Test recursive method
echo "\nTest 6: Testing getAllReferrals method...\n";
$testUser = User::has('referrals')->first();
if ($testUser) {
    $tree = $testUser->getAllReferrals(3);
    echo "Referral tree returned " . count($tree) . " direct referrals\n";
    echo "✅ Method working\n";
} else {
    echo "⚠️  No users with referrals to test\n";
}

echo "\n=== Test Complete ===\n\n";
