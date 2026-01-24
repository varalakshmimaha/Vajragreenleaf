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
        Schema::table('products', function (Blueprint $table) {
            $table->longText('key_benefits')->nullable()->after('description');
            $table->longText('directions')->nullable()->after('key_benefits');
            $table->longText('actions_indications')->nullable()->after('directions');
            $table->longText('method_of_use')->nullable()->after('actions_indications');
            $table->longText('dosage')->nullable()->after('method_of_use');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['key_benefits', 'directions', 'actions_indications', 'method_of_use', 'dosage']);
        });
    }
};
