<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Main sections table
        if (!Schema::hasTable('sections')) {
            Schema::create('sections', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Internal name for admin
                $table->string('slug')->unique();
                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();
                $table->text('description')->nullable();
                $table->longText('content')->nullable(); // Rich text content

                // Media
                $table->string('image')->nullable();
                $table->string('image_position')->default('left'); // left, right, top, bottom, background, none
                $table->json('gallery')->nullable(); // Array of images
                $table->string('video_url')->nullable();

                // Layout & Design
                $table->string('layout')->default('default'); // default, cards, grid, slider, tabs, accordion, hero, cta, features, testimonials, pricing, team, gallery, contact, custom
                $table->string('columns')->default('1'); // 1, 2, 3, 4, 6
                $table->string('container_width')->default('container'); // full, container, narrow
                $table->string('background_type')->default('color'); // color, gradient, image, video
                $table->string('background_color')->nullable();
                $table->string('background_gradient')->nullable();
                $table->string('background_image')->nullable();
                $table->string('background_overlay')->nullable(); // Overlay color with opacity

                // Text Styling
                $table->string('text_color')->nullable();
                $table->string('title_size')->default('text-4xl'); // text-2xl, text-3xl, text-4xl, text-5xl, text-6xl
                $table->string('title_alignment')->default('center'); // left, center, right
                $table->string('content_alignment')->default('center'); // left, center, right

                // Spacing
                $table->string('padding_top')->default('py-16'); // py-8, py-12, py-16, py-20, py-24, py-32
                $table->string('padding_bottom')->default('py-16');
                $table->string('margin_top')->default('');
                $table->string('margin_bottom')->default('');

                // Animation
                $table->string('animation_type')->default('none'); // none, fade, slide-up, slide-down, slide-left, slide-right, zoom, bounce
                $table->string('animation_delay')->default('0'); // 0, 100, 200, 300, 500
                $table->string('animation_duration')->default('500'); // 300, 500, 700, 1000

                // Cards/Items styling (for grid/card layouts)
                $table->json('items')->nullable(); // Array of items for cards, features, etc.
                $table->string('card_style')->default('default'); // default, bordered, shadow, minimal, gradient
                $table->string('card_hover')->default('none'); // none, lift, scale, glow, border
                $table->boolean('card_rounded')->default(true);

                // Button/CTA
                $table->string('button_text')->nullable();
                $table->string('button_url')->nullable();
                $table->string('button_style')->default('primary'); // primary, secondary, outline, ghost
                $table->string('button_size')->default('md'); // sm, md, lg
                $table->string('secondary_button_text')->nullable();
                $table->string('secondary_button_url')->nullable();

                // Custom CSS/JS
                $table->text('custom_css')->nullable();
                $table->text('custom_js')->nullable();
                $table->string('custom_class')->nullable();
                $table->string('custom_id')->nullable();

                // Theme Integration
                $table->boolean('use_theme_colors')->default(true);

                // Status
                $table->integer('order')->default(0);
                $table->boolean('is_reusable')->default(true); // Can be used in multiple pages
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Update existing page_sections table to add new columns
        if (Schema::hasTable('page_sections')) {
            Schema::table('page_sections', function (Blueprint $table) {
                // Add section_id if it doesn't exist
                if (!Schema::hasColumn('page_sections', 'section_id')) {
                    $table->foreignId('section_id')->nullable()->after('page_id')->constrained()->onDelete('cascade');
                }
                // Add overrides if it doesn't exist
                if (!Schema::hasColumn('page_sections', 'overrides')) {
                    $table->json('overrides')->nullable()->after('is_active');
                }
            });
        }
    }

    public function down(): void
    {
        // Remove added columns from page_sections
        if (Schema::hasTable('page_sections')) {
            Schema::table('page_sections', function (Blueprint $table) {
                if (Schema::hasColumn('page_sections', 'section_id')) {
                    $table->dropForeign(['section_id']);
                    $table->dropColumn('section_id');
                }
                if (Schema::hasColumn('page_sections', 'overrides')) {
                    $table->dropColumn('overrides');
                }
            });
        }

        Schema::dropIfExists('sections');
    }
};
