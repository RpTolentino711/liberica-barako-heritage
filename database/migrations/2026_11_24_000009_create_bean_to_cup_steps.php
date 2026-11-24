<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('journey_steps', function (Blueprint $table) {
            $table->id();
            $table->string('title', 140);
            $table->text('description');
            $table->string('media_path', 255)->nullable();
            $table->string('media_type', 20)->default('image');
            $table->string('media_alt', 240)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        $now = now();
        DB::table('journey_steps')->insert([
            [
                'title' => 'Growing',
                'description' => 'A coffee journey begins with healthy planting material and a suitable growing site. Farmers care for young trees, manage shade and soil, and observe water and plant health so the coffee cherries can develop well.',
                'media_path' => 'assets/pexels-carlostorres-18505518.jpg',
                'media_type' => 'image',
                'media_alt' => 'Coffee cherries growing among green leaves',
                'sort_order' => 1,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Harvesting',
                'description' => 'When cherries ripen, they are picked using a method suited to the farm and harvest. Sorting out unripe, damaged, or foreign material helps keep lots consistent and prepares the fruit for careful processing.',
                'media_path' => 'assets/pexels-michael-burrows-7125451.jpg',
                'media_type' => 'image',
                'media_alt' => 'A hand selecting ripe coffee cherries from a branch',
                'sort_order' => 2,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Processing and drying',
                'description' => 'Producers remove the fruit layers using a process suited to local conditions, then dry the beans carefully. Clean equipment, even drying, and protected storage help preserve quality before hulling and roasting.',
                'media_path' => 'assets/pexels-anderson-martinsz-34634578.jpg',
                'media_type' => 'image',
                'media_alt' => 'Coffee beans being processed with farm equipment',
                'sort_order' => 3,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Roasting and brewing',
                'description' => 'After hulling and sorting, green beans are roasted to develop aroma and flavor. Grind size, water, equipment, and brewing time then shape the cup. Tasting helps roasters and brewers compare results and refine their method.',
                'media_path' => 'assets/pexels-thomas-plets-1139798-2434159.jpg',
                'media_type' => 'image',
                'media_alt' => 'Fresh coffee being brewed in a glass cup',
                'sort_order' => 4,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('journey_steps');
    }
};
