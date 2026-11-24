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
            ->where('slug', 'end-of-lipa-coffee-boom')
            ->first(['id', 'body']);
        if (!$entry) return;

        $heading = 'What followed the decline';
        $source = json_encode([
            'label' => 'City Government of Lipa - Local History',
            'url' => 'https://lipa.gov.ph/history/',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (!is_string($source)) return;
        $marker = '@@ENCYCLOPEDIA_SOURCE:'.base64_encode($source).'@@';

        $chunks = preg_split('/(?=^## .+$)/m', $entry->body, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $changed = false;
        foreach ($chunks as &$chunk) {
            $trimmed = ltrim($chunk);
            if (!preg_match('/^## '.preg_quote($heading, '/').'\s*(?:\r?\n|$)/', $trimmed)) continue;

            $lines = preg_split('/\R/', $trimmed);
            $lines = array_values(array_filter($lines, fn ($line) => !preg_match('/^@+ENCYCLOPEDIA_SOURCE:/', trim($line))));
            array_splice($lines, 1, 0, [$marker]);
            $chunk = "\n".implode("\n", $lines);
            $changed = true;
            break;
        }
        unset($chunk);

        if ($changed) {
            DB::table('encyclopedia_entries')->where('id', $entry->id)->update([
                'body' => implode('', $chunks),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Preserve the repaired source and any administrator edits.
    }
};
