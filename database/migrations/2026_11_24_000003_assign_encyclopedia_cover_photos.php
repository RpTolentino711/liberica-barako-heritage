<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $topicPhotos = [
            'history-of-philippine-coffee' => [
                'assets/pexels-pranjall-kumar-150768-8555658.jpg',
                'assets/pexels-michael-burrows-7125451.jpg',
                'assets/liberica-coffee-beans.jpg',
            ],
            'the-science-of-liberica' => [
                'assets/pexels-michael-burrows-7125451.jpg',
                'assets/coffee_Liberica_bean.png',
                'assets/liberica-coffee-beans.jpg',
            ],
            'culture-and-heritage' => [
                'assets/pexels-pranjall-kumar-150768-8555658.jpg',
                'assets/pexels-michael-burrows-7125451.jpg',
                'assets/pexels-pranjall-kumar-150768-8555658.jpg',
            ],
            'diseases' => [
                'assets/pexels-michael-burrows-7125451.jpg',
                'assets/pexels-torrans-mayorquin-505468205-20190062.jpg',
                'assets/pexels-pranjall-kumar-150768-8555658.jpg',
            ],
        ];

        foreach ($topicPhotos as $topicSlug => $photos) {
            $topicId = DB::table('encyclopedia_topics')->where('slug', $topicSlug)->value('id');
            if (!$topicId) continue;

            $entries = DB::table('encyclopedia_entries')
                ->where('topic_id', $topicId)
                ->whereNull('image_path')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(['id']);

            foreach ($entries as $index => $entry) {
                DB::table('encyclopedia_entries')->where('id', $entry->id)->update([
                    'image_path' => $photos[$index % count($photos)],
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Keep images that may have been customized after this migration ran.
    }
};
