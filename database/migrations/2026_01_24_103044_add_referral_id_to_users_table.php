<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add referral_id column (5-digit unique number)
            $table->string('referral_id', 5)->unique()->nullable()->after('username');
            
            // Add sponsor_referral_id to store the referrer's referral_id
            $table->string('sponsor_referral_id', 5)->nullable()->after('sponsor_id');
        });

        // Generate referral IDs for existing users
        $users = \App\Models\User::all();
        $existingIds = [];
        
        foreach ($users as $user) {
            $referralId = $this->generateUniqueReferralId($existingIds);
            $existingIds[] = $referralId;
            $user->referral_id = $referralId;
            $user->save();
        }

        // Update sponsor_referral_id based on existing sponsor_id (username)
        foreach ($users as $user) {
            if ($user->sponsor_id) {
                $sponsor = \App\Models\User::where('username', $user->sponsor_id)->first();
                if ($sponsor && $sponsor->referral_id) {
                    $user->sponsor_referral_id = $sponsor->referral_id;
                    $user->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referral_id', 'sponsor_referral_id']);
        });
    }

    /**
     * Generate a unique 5-digit referral ID
     */
    private function generateUniqueReferralId(array $existingIds): string
    {
        do {
            $referralId = str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        } while (in_array($referralId, $existingIds) || \App\Models\User::where('referral_id', $referralId)->exists());
        
        return $referralId;
    }
};
