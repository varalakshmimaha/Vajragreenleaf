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
            $table->string('mobile')->unique()->after('email');
            $table->string('username')->unique()->nullable()->after('mobile');
            $table->string('sponsor_id')->nullable()->after('username');
            $table->text('address')->nullable()->after('password');
            $table->string('state')->nullable()->after('address');
            $table->string('city')->nullable()->after('state');
            $table->string('pincode')->nullable()->after('city');
            // 'is_active' and 'role' might already exist from previous migrations, but let's check or assume they are there as per previous `view_file`.
            // The prompt mentioned 'Status (Active / Inactive)' for sponsor key, but for the user 'is_active' exists.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mobile', 'username', 'sponsor_id', 'address', 'state', 'city', 'pincode']);
        });
    }
};
