<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for performance optimization
        // Using raw SQL to safely add indexes only if they don't exist
        
        $indexes = [
            'users_sponsor_referral_id_index' => 'CREATE INDEX IF NOT EXISTS users_sponsor_referral_id_index ON users(sponsor_referral_id)',
            'users_referral_id_index' => 'CREATE INDEX IF NOT EXISTS users_referral_id_index ON users(referral_id)',
            'users_is_active_index' => 'CREATE INDEX IF NOT EXISTS users_is_active_index ON users(is_active)',
            'users_created_at_index' => 'CREATE INDEX IF NOT EXISTS users_created_at_index ON users(created_at)',
        ];

        foreach ($indexes as $name => $sql) {
            try {
                DB::statement($sql);
            } catch (\Exception $e) {
                // Index might already exist, continue
                \Log::info("Index {$name} might already exist: " . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes if they exist
            $indexes = ['users_sponsor_referral_id_index', 'users_referral_id_index', 'users_is_active_index', 'users_created_at_index'];
            
            foreach ($indexes as $index) {
                try {
                    $table->dropIndex($index);
                } catch (\Exception $e) {
                    // Index might not exist
                }
            }
        });
    }
};
