<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('farm_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 160);
            $table->string('place_name', 220);
            $table->string('location_type', 120)->nullable();
            $table->text('description');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('photo_path', 255)->nullable();
            $table->string('photo_alt', 240)->nullable();
            $table->string('source_url', 2048)->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        DB::table('farm_locations')->insert([
            [
                'name' => 'Katy’s Farm',
                'place_name' => 'Tipakan, Lipa City, Batangas',
                'location_type' => 'Cultivation & nursery',
                'description' => 'Liberica cultivation and nursery activity documented in research from the University of the Philippines Los Baños. This is an approximate area marker; visitor access has not been confirmed.',
                'latitude' => 13.9146000,
                'longitude' => 121.2034100,
                'photo_path' => 'assets/journey-planting.jpg',
                'photo_alt' => 'Coffee cherries and foliage representing a coffee growing area',
                'source_url' => 'https://www.ukdr.uplb.edu.ph/etd-undergrad/5345/',
                'is_published' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BARAKO Smart Demo Farm',
                'place_name' => 'Masaya, Rosario, Batangas',
                'location_type' => 'Research & demonstration',
                'description' => 'Batangas State University’s BARAKO initiative highlights agricultural demonstration and Liberica-related farm technology. This is an approximate area marker; public visitor access is not confirmed.',
                'latitude' => 13.8383000,
                'longitude' => 121.1852000,
                'photo_path' => 'assets/journey-processing.jpg',
                'photo_alt' => 'Coffee processing equipment representing an agricultural demonstration site',
                'source_url' => 'https://sustainability.batstate-u.edu.ph/wp-content/uploads/2022/11/Barako-Progress-Report.pdf',
                'is_published' => true,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_locations');
    }
};
