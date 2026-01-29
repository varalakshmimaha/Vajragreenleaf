<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('banner_title')->nullable()->after('banner_image');
            $table->string('banner_subtitle')->nullable()->after('banner_title');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->string('banner_title')->nullable()->after('featured_image');
            $table->string('banner_subtitle')->nullable()->after('banner_title');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->string('banner_image')->nullable()->after('content');
            $table->string('banner_title')->nullable()->after('banner_image');
            $table->string('banner_subtitle')->nullable()->after('banner_title');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['banner_title', 'banner_subtitle']);
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['banner_title', 'banner_subtitle']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['banner_image', 'banner_title', 'banner_subtitle']);
        });
    }
};
