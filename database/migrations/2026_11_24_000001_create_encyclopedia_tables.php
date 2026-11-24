<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('encyclopedia_topics', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('description', 500)->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('encyclopedia_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('encyclopedia_topics')->cascadeOnDelete();
            $table->string('title', 180);
            $table->string('slug', 190);
            $table->string('summary', 500);
            $table->longText('body');
            $table->string('image_path')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['topic_id', 'slug']);
        });

        $now = now();
        DB::table('encyclopedia_topics')->insert([
            ['name' => 'History of Philippine Coffee', 'slug' => 'history-of-philippine-coffee', 'description' => 'The history of coffee in the Philippines, with special attention to Batangas and Lipa.', 'is_published' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'The Science of Liberica', 'slug' => 'the-science-of-liberica', 'description' => 'Botany, cultivation, processing, and the science behind Coffea liberica.', 'is_published' => true, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Culture and Heritage', 'slug' => 'culture-and-heritage', 'description' => 'The people, traditions, language, and coffee practices connected to Batangas.', 'is_published' => true, 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Diseases', 'slug' => 'diseases', 'description' => 'Coffee plant diseases, identification, prevention, and responsible management.', 'is_published' => true, 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $topicIds = DB::table('encyclopedia_topics')->pluck('id', 'slug');
        $categoryTopics = [
            'heritage' => 'history-of-philippine-coffee',
            'botany' => 'the-science-of-liberica',
            'cultivation' => 'the-science-of-liberica',
            'culture' => 'culture-and-heritage',
            'preservation' => 'culture-and-heritage',
        ];
        $articles = DB::table('articles')->where('is_published', true)->orderBy('sort_order')->orderBy('id')->get();
        foreach ($articles as $article) {
            $topicSlug = $categoryTopics[$article->category] ?? null;
            if (!$topicSlug) continue;

            DB::table('encyclopedia_entries')->insert([
                'topic_id' => $topicIds[$topicSlug],
                'title' => $article->title,
                'slug' => $article->slug,
                'summary' => $article->summary,
                'body' => $article->body,
                'image_path' => null,
                'is_published' => true,
                'sort_order' => $article->sort_order,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('encyclopedia_entries');
        Schema::dropIfExists('encyclopedia_topics');
    }
};
