<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::transaction(function () {
            $topic = DB::table('encyclopedia_topics')->where('slug', 'history-of-philippine-coffee')->first();
            if (!$topic || DB::table('encyclopedia_entries')->where('topic_id', $topic->id)->where('slug', 'kapeng-barako-in-lipa')->exists()) {
                return;
            }
            $id = DB::table('encyclopedia_entries')->insertGetId([
                'topic_id' => $topic->id,
                'title' => 'Kapeng Barako in Lipa',
                'slug' => 'kapeng-barako-in-lipa',
                'summary' => 'Meet the coffee tree behind the name barako and explore its place in the changing coffee fields of Lipa, Batangas.',
                'body' => <<<'ARTICLE'
## What is Liberica?
Liberica is a species of coffee tree. Botanists call it Coffea liberica. Kew's plant records place its native range in West Africa, from Sierra Leone to southwestern Nigeria. The Philippines is among the places where people introduced the tree. A coffee can therefore have roots in one part of the world and become part of everyday life in another.

## Why is it called barako?
In the Philippines, barako, also spelled baraco, is a local name for Liberica coffee. The Philippine Coffee Board describes the cup as especially robust. When someone speaks of kapeng barako in Batangas, the name connects the drink to a kind of coffee that people in the region know and grow. The botanical name is useful when comparing plants across countries. The local name tells us how people encounter that plant at home.

## How does it fit into Lipa's coffee history?
Lipa was already a coffee trading town in the nineteenth century. Identifying the trees behind that trade takes care. Historical research on Lipa suggests that much of the earlier crop was probably Arabica. A record that simply mentions coffee tells us something about trade but leaves the species an open question.

As coffee rust damaged plantations, growers faced a practical choice about what to plant next. Around 1900, some growers in Lipa were experimenting with Liberica and Robusta. Those trials give Liberica a specific place in the city's history. They also help explain why the history of growing coffee in Lipa reaches further back than the evidence for the particular tree now associated with kapeng barako.
ARTICLE,
                'is_published' => false,
                'featured_in_discover' => true,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $sources = [
                ['Royal Botanic Gardens, Kew — Coffea liberica', 'https://powo.science.kew.org/taxon/urn%3Alsid%3Aipni.org%3Anames%3A77368785-1'],
                ['Philippine Coffee Board — Philippine Coffee', 'https://philcoffeeboard.com/philippine-coffee/'],
                ['Demythologising the History of Coffee in Lipa, Batangas in the 19th Century', 'https://www.academia.edu/12326071/Demythologising_the_History_of_Coffee_in_Lipa_Batangas_in_the_19th_Century'],
            ];
            foreach ($sources as $position => [$title, $url]) {
                DB::table('encyclopedia_references')->insert(compact('title', 'url', 'position') + ['entry_id' => $id]);
            }
        });
    }

    public function down(): void
    {
        DB::table('encyclopedia_entries')->where('featured_in_discover', true)->delete();
    }
};
