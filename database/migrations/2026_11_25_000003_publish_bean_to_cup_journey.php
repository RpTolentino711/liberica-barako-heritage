<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journey_steps', function (Blueprint $table) {
            foreach (['source', 'creator', 'license', 'media_note', 'import_status'] as $field) {
                $table->string($field)->nullable();
            }
            foreach (['source_url', 'photo_url', 'license_url'] as $field) {
                $table->string($field, 2048)->nullable();
            }
        });

        DB::transaction(function () {
            // Match the original seed content so editor-customized records survive.
            $originalSteps = [
                [
                    'title' => 'Growing',
                    'description' => 'A coffee journey begins with healthy planting material and a suitable growing site. Farmers care for young trees, manage shade and soil, and observe water and plant health so the coffee cherries can develop well.',
                    'media_path' => 'assets/pexels-carlostorres-18505518.jpg',
                    'media_type' => 'image',
                    'media_alt' => 'Coffee cherries growing among green leaves',
                    'sort_order' => 1,
                    'is_published' => true,
                ],
                [
                    'title' => 'Harvesting',
                    'description' => 'When cherries ripen, they are picked using a method suited to the farm and harvest. Sorting out unripe, damaged, or foreign material helps keep lots consistent and prepares the fruit for careful processing.',
                    'media_path' => 'assets/pexels-michael-burrows-7125451.jpg',
                    'media_type' => 'image',
                    'media_alt' => 'A hand selecting ripe coffee cherries from a branch',
                    'sort_order' => 2,
                    'is_published' => true,
                ],
                [
                    'title' => 'Processing and drying',
                    'description' => 'Producers remove the fruit layers using a process suited to local conditions, then dry the beans carefully. Clean equipment, even drying, and protected storage help preserve quality before hulling and roasting.',
                    'media_path' => 'assets/pexels-anderson-martinsz-34634578.jpg',
                    'media_type' => 'image',
                    'media_alt' => 'Coffee beans being processed with farm equipment',
                    'sort_order' => 3,
                    'is_published' => true,
                ],
                [
                    'title' => 'Roasting and brewing',
                    'description' => 'After hulling and sorting, green beans are roasted to develop aroma and flavor. Grind size, water, equipment, and brewing time then shape the cup. Tasting helps roasters and brewers compare results and refine their method.',
                    'media_path' => 'assets/pexels-thomas-plets-1139798-2434159.jpg',
                    'media_type' => 'image',
                    'media_alt' => 'Fresh coffee being brewed in a glass cup',
                    'sort_order' => 4,
                    'is_published' => true,
                ],
            ];
            foreach ($originalSteps as $step) {
                DB::table('journey_steps')->where($step)->update(['is_published' => false, 'import_status' => 'archived']);
            }

            foreach (require resource_path('data/bean-to-cup.php') as $index => $step) {
                DB::table('journey_steps')->insert($step + [
                    'license' => 'Pexels License',
                    'license_url' => 'https://www.pexels.com/license/',
                    'media_note' => $index === 0 ? 'Video still, cropped to fit.' : 'Cropped to fit.',
                    'sort_order' => $index + 1,
                    'is_published' => true,
                    'import_status' => 'imported',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        DB::transaction(function () {
            DB::table('journey_steps')->where('import_status', 'imported')->delete();
            DB::table('journey_steps')->where('import_status', 'archived')->update(['is_published' => true]);
        });
        Schema::table('journey_steps', function (Blueprint $table) {
            $table->dropColumn(['source', 'source_url', 'creator', 'photo_url', 'license', 'license_url', 'media_note', 'import_status']);
        });
    }
};
