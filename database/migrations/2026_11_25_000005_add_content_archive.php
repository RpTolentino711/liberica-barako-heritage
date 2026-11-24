<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const TABLES = ['journey_steps', 'encyclopedia_topics', 'encyclopedia_entries'];

    public function up(): void
    {
        foreach (self::TABLES as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->timestamp('archived_at')->nullable());
        }
        DB::table('journey_steps')->where('import_status', 'archived')->where('is_published', false)
            ->update(['archived_at' => now()]);
    }

    public function down(): void
    {
        foreach (self::TABLES as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->dropColumn('archived_at'));
        }
    }
};
