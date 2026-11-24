<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $topicId = DB::table('encyclopedia_topics')
            ->where('slug', 'history-of-philippine-coffee')
            ->value('id');
        if (!$topicId) return;

        $entry = DB::table('encyclopedia_entries')
            ->where('topic_id', $topicId)
            ->where('slug', 'the-coffee-legacy-of-lipa')
            ->first(['id', 'body']);
        if (!$entry || str_contains($entry->body, '## A legacy shaped by work and trade')) return;

        $source = json_encode([
            'label' => 'City Government of Lipa - Local History',
            'url' => 'https://lipa.gov.ph/history/',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (!is_string($source)) return;

        $restoredSection = "## A legacy shaped by work and trade\n\n"
            .'@@ENCYCLOPEDIA_SOURCE:'.base64_encode($source)."@@\n\n"
            .'Coffee’s influence reached beyond the trees. A trading crop connected growers with harvesters, processors, merchants, transport, and buyers. The City Government of Lipa describes stores and bazaars during the prosperous years and records Lipa’s elevation to Villa de Lipa in 1887. These details show how agricultural production could shape a town’s public life and built environment.'
            ."\n\n"
            .'The legacy should also be read with care: prosperity did not mean that every household received the same benefit. Work was distributed across farms and homes, while wealth and land were unevenly held. Remembering both the visible prosperity and the people whose labor supported it makes the history more complete.';

        DB::table('encyclopedia_entries')->where('id', $entry->id)->update([
            'body' => rtrim($entry->body)."\n\n".$restoredSection,
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Keep restored article content and any subsequent administrator edits.
    }
};
