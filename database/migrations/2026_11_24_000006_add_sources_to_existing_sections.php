<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private function sources(): array
    {
        $lipa = ['City Government of Lipa - Local History', 'https://lipa.gov.ph/history/'];
        $kew = ['Kew Science - Plants of the World Online: Coffea liberica', 'https://powo.science.kew.org/taxon/urn:lsid:ipni.org:names:77368785-1'];
        $daGuide = ['Department of Agriculture - Coffee Technoguide', 'https://ati2.da.gov.ph/ati-4b/content/sites/default/files/2022-11/Coffee%20Technoguide%20w%20page%20numbers.pdf'];
        $bpi = ['DA Bureau of Plant Industry - Coffee Production Guide', 'https://library.buplant.da.gov.ph/images/1641945499COFFEE.pdf'];
        $pna = ['Philippine News Agency - Batangas Kapeng Barako report', 'https://www.pna.gov.ph/index.php/articles/1234070'];

        return [
            'history-of-philippine-coffee' => [
                'when-coffee-first-reached-lipa' => ['From first planting to a coffee town' => $lipa],
                'the-coffee-legacy-of-lipa' => ['A legacy shaped by work and trade' => $lipa],
                'lipa-nineteenth-century-coffee-boom' => ['Caracolillo and the town reputation' => $lipa],
                'end-of-lipa-coffee-boom' => ['What followed the decline' => $lipa],
            ],
            'the-science-of-liberica' => [
                'what-makes-liberica-distinctive' => ['Recognizing the species beyond its name' => $kew],
                'what-is-coffea-liberica' => ['Species, variety, and local name' => $kew],
                'meet-the-liberica-coffee-tree' => ['Flowers, fruit, and seeds' => ['National Parks Board Singapore - Coffea liberica profile', 'https://www.nparks.gov.sg/florafaunaweb/flora/2/8/2820']],
                'from-coffee-tree-to-cup' => ['Each stage affects the final cup' => $daGuide],
                'from-cherry-to-green-bean' => ['Drying, sorting, and storage matter' => ['Department of Agriculture - Coffee Processing Facility Guidance', 'https://www.da.gov.ph/wp-content/uploads/2025/08/mo40_s2025.pdf']],
            ],
            'culture-and-heritage' => [
                'the-barako-coffee-ritual' => ['A living practice, not one fixed recipe' => ['Buenaflor (2025) - Kapeng Barako and Batangueño Identity', 'https://jabedu.com/index.php/jabedu/article/view/49']],
                'kapeng-barako-batangas-identity' => ['Identity is carried by many voices' => ['Buenaflor (2025) - Kapeng Barako and Batangueño Identity', 'https://jabedu.com/index.php/jabedu/article/view/49']],
                'coffee-work-and-everyday-exchange' => ['The people behind each harvest' => $daGuide],
                'why-this-heritage-matters' => ['Heritage can support the next generation' => $pna],
                'keeping-coffee-heritage-alive' => ['Preserve knowledge with care' => $pna],
            ],
            'diseases' => [
                'coffee-leaf-rust-signs-and-science' => ['Why leaf loss affects the tree' => $bpi],
                'coffee-berry-borer-insect-pest' => ['Harvest sanitation breaks hiding places' => ['Philippine eAgri - Coffee growing guide', 'https://eagri.e.gov.ph/commodities/coffee']],
                'monitoring-coffee-farm-health' => ['Keep a season-by-season farm record' => $daGuide],
                'how-coffee-leaf-rust-spreads' => ['Weather and movement both matter' => ['Hawaii Department of Agriculture - Coffee Leaf Rust', 'https://hdoa.hawaii.gov/pi/files/2021/10/NPA-20-03-Coffee-leaf-rust-10-2021.pdf']],
                'coffee-leaf-spots-and-diagnosis' => ['A useful sample for an adviser' => $bpi],
                'coffee-dieback-thread-blight-and-other-problems' => ['Separate the symptom from its cause' => $bpi],
                'coffee-farm-health-check' => ['Turn observations into a farm plan' => $daGuide],
            ],
        ];
    }

    public function up(): void
    {
        foreach ($this->sources() as $topicSlug => $entries) {
            $topicId = DB::table('encyclopedia_topics')->where('slug', $topicSlug)->value('id');
            if (!$topicId) continue;

            foreach ($entries as $entrySlug => $sections) {
                $entry = DB::table('encyclopedia_entries')->where('topic_id', $topicId)->where('slug', $entrySlug)->first(['id', 'body']);
                if (!$entry) continue;

                $chunks = preg_split('/(?=^## .+$)/m', $entry->body, -1, PREG_SPLIT_NO_EMPTY) ?: [];
                $changed = false;
                foreach ($sections as $heading => [$label, $url]) {
                    foreach ($chunks as &$chunk) {
                        $lines = preg_split('/\R/', ltrim($chunk), 2);
                        if (($lines[0] ?? '') !== '## '.$heading || preg_match('/^@@ENCYCLOPEDIA_SOURCE:/m', $chunk)) continue;

                        $json = json_encode(['label' => $label, 'url' => $url], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                        if (!is_string($json)) continue;
                        $marker = '@@ENCYCLOPEDIA_SOURCE:'.base64_encode($json).'@@';
                        $chunk = preg_replace('/^(## '.preg_quote($heading, '/').')\R/', '$1' . "\n\n" . $marker . "\n", $chunk, 1) ?? $chunk;
                        $changed = true;
                        break;
                    }
                    unset($chunk);
                }

                if ($changed) {
                    DB::table('encyclopedia_entries')->where('id', $entry->id)->update([
                        'body' => implode('', $chunks),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Preserve citations and any later administrator edits.
    }
};
