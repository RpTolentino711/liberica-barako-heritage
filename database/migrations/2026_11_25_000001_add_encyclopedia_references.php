<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('encyclopedia_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('encyclopedia_entries')->cascadeOnDelete();
            $table->string('title', 240);
            $table->string('url', 2048)->nullable();
            $table->string('pdf_path')->nullable();
            $table->unsignedInteger('position');
        });

        Schema::table('encyclopedia_entries', function (Blueprint $table) {
            $table->boolean('featured_in_discover')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encyclopedia_references');
        Schema::table('encyclopedia_entries', fn (Blueprint $table) => $table->dropColumn('featured_in_discover'));
    }
};
