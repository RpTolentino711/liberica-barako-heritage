<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private function additions(): array
    {
        return [
            'history-of-philippine-coffee' => [
                'when-coffee-first-reached-lipa' => [
                    'heading' => 'From first planting to a coffee town',
                    'text' => <<<'TEXT'
The City Government of Lipa places the beginning of wider local cultivation later in the story: its history describes a push to plant coffee in 1808, followed by further expansion under local leaders in 1832 and extensive plantations by 1859. This sequence helps distinguish an uncertain first introduction from the better documented growth of a coffee economy. Planting, adoption, and commercial expansion happened over generations rather than on one single date.

When comparing origin stories, ask what kind of record each source uses and when it was written. A municipal history can preserve local chronology and memory, while older government reports may reveal what contemporary officials did or did not document. Treating those sources together gives a richer account than presenting one date as settled fact.
TEXT,
                    'source_label' => 'City Government of Lipa — Local History',
                    'source_url' => 'https://lipa.gov.ph/history/',
                ],
                'the-coffee-legacy-of-lipa' => [
                    'heading' => 'A legacy shaped by work and trade',
                    'text' => <<<'TEXT'
Coffee’s influence reached beyond the trees. A trading crop connected growers with harvesters, processors, merchants, transport, and buyers. The City Government of Lipa describes stores and bazaars during the prosperous years and records Lipa’s elevation to Villa de Lipa in 1887. These details show how agricultural production could shape a town’s public life and built environment.

The legacy should also be read with care: prosperity did not mean that every household received the same benefit. Work was distributed across farms and homes, while wealth and land were unevenly held. Remembering both the visible prosperity and the people whose labor supported it makes the history more complete.
TEXT,
                    'source_label' => 'City Government of Lipa — Local History',
                    'source_url' => 'https://lipa.gov.ph/history/',
                ],
                'lipa-nineteenth-century-coffee-boom' => [
                    'heading' => 'Caracolillo and the town’s growing reputation',
                    'text' => <<<'TEXT'
The municipal history identifies Lipa’s coffee as caracolillo, or peaberry, and notes strong demand between 1870 and 1880. It reports that both export volume and average value per metric ton rose during those years. This gives one measurable view of the boom: it was not only a story remembered in family accounts, but also a period when local coffee entered wider trade.

The same source connects the town’s prosperity with its recognition as Villa de Lipa in 1887. That title is a useful historical marker, but it should not be treated as a complete measure of ordinary residents’ lives. Reading trade, civic recognition, and labor together helps explain why coffee became such a lasting part of Lipa’s local story.
TEXT,
                    'source_label' => 'City Government of Lipa — Local History',
                    'source_url' => 'https://lipa.gov.ph/history/',
                ],
                'end-of-lipa-coffee-boom' => [
                    'heading' => 'What followed the decline',
                    'text' => <<<'TEXT'
Lipa’s municipal history describes abaca becoming an important source of income after the coffee plantations were damaged, followed over time by a more diverse mix of crops such as coconut, banana, coffee, rice, corn, sugarcane, and vegetables. This transition shows that the end of the boom did not mean the end of agriculture or of coffee knowledge; households and communities adapted their livelihoods.

The local term “bagombong” is part of the historical account, but an old local name alone cannot identify a pathogen according to modern plant science. Historians and plant specialists answer different questions: historical sources describe what communities recorded, while a scientific diagnosis requires evidence about the organism. Keeping that distinction clear respects both local memory and careful research.
TEXT,
                    'source_label' => 'City Government of Lipa — Local History',
                    'source_url' => 'https://lipa.gov.ph/history/',
                ],
            ],
            'the-science-of-liberica' => [
                'what-makes-liberica-distinctive' => [
                    'heading' => 'Recognizing the species beyond its name',
                    'text' => <<<'TEXT'
Botanical identity is based on the plant, not only on the name used for a drink or product. Kew’s Plants of the World Online accepts Coffea liberica as a species and records its native range in West Africa, from Sierra Leone to southwestern Nigeria. The species is now introduced in many places, including the Philippines. In Batangas, Kapeng Barako is closely associated with Liberica, as described in Department of Agriculture training materials.

Common names can vary between communities and markets, and the word “Barako” may be used as a cultural or product name. When the exact species or planting material is not documented, it is better to describe the source accurately than to assume that every coffee sold under one name has identical botanical identity.
TEXT,
                    'source_label' => 'Kew Science — Plants of the World Online: Coffea liberica',
                    'source_url' => 'https://powo.science.kew.org/taxon/urn:lsid:ipni.org:names:77368785-1',
                ],
                'what-is-coffea-liberica' => [
                    'heading' => 'Species, variety, and local name',
                    'text' => <<<'TEXT'
The name Coffea liberica refers to a botanical species. “Kapeng Barako” is a familiar Philippine name strongly connected to Liberica in Batangas and in Department of Agriculture materials, but a common name is not a botanical key by itself. This distinction is useful when reading labels, comparing planting material, or documenting a farm: the local name carries cultural meaning, while a scientific name identifies a taxonomic classification.

Plant classification is based on published botanical work and may be revised as evidence develops. Kew’s database records accepted names and synonyms, which is why older books can use names or groupings that differ from current references. A careful article names the authority it follows and avoids presenting one common name as a guarantee of species, flavor, or quality.
TEXT,
                    'source_label' => 'Kew Science — Plants of the World Online: Coffea liberica',
                    'source_url' => 'https://powo.science.kew.org/taxon/urn:lsid:ipni.org:names:77368785-1',
                ],
                'meet-the-liberica-coffee-tree' => [
                    'heading' => 'Flowers, fruit, and seeds',
                    'text' => <<<'TEXT'
Singapore’s National Parks Board describes Liberica as a tree that can reach about 18 metres in its environment. It records broad, leathery leaves with wavy margins, fragrant white flowers, and cherry-like fruit typically containing two seeds. These features offer useful clues for observing a plant, but a single characteristic should not be used to identify an unknown tree without considering its other features and planting history.

The fruit around a coffee seed is important to production. After harvest, the cherry’s outer layers must be removed through a chosen processing method, and the seeds must be dried and stored with care. Tree size also matters to farm layout and harvest access. Actual growth depends on climate, soil, shade, pruning, and other site conditions, so a botanical profile is a reference point rather than a promise of how every Batangas tree will grow.
TEXT,
                    'source_label' => 'National Parks Board Singapore — Coffea liberica profile',
                    'source_url' => 'https://www.nparks.gov.sg/florafaunaweb/flora/2/8/2820',
                ],
                'from-coffee-tree-to-cup' => [
                    'heading' => 'Each stage affects the final cup',
                    'text' => <<<'TEXT'
Coffee quality is built across several linked steps. The Philippine Department of Agriculture’s Coffee Technoguide covers crop management, harvesting, and processing, including the movement from fruit to cleaned green coffee. Picking ripe cherries separately helps producers keep immature fruit from being mixed with fully mature harvest. Sorting and clean handling make later processing more consistent.

After the fruit is harvested, producers choose a dry, washed, or other locally suitable method. The method changes the sequence of pulping, fermentation, washing, and drying, but every approach depends on clean equipment and attentive handling. Hulling removes the remaining parchment or dried fruit layers. Roasting and brewing happen later, and can reveal or change qualities established earlier. No single stage alone determines the character of every Liberica coffee.
TEXT,
                    'source_label' => 'Department of Agriculture — Coffee Technoguide',
                    'source_url' => 'https://ati2.da.gov.ph/ati-4b/content/sites/default/files/2022-11/Coffee%20Technoguide%20w%20page%20numbers.pdf',
                ],
                'from-cherry-to-green-bean' => [
                    'heading' => 'Drying, sorting, and storage matter',
                    'text' => <<<'TEXT'
Once cherries have been pulped or dried whole, the coffee still needs careful drying before it is hulled and stored. Drying should be even and protected from dirt, animals, and unwanted moisture. The Department of Agriculture’s 2025 guidance describes drying parchment or dried cherries and notes that huller calibration matters because poorly adjusted equipment can break beans and reduce quality.

After hulling, producers can sort out damaged or inconsistent beans and keep lots identified by harvest and process. These records help a farmer compare results between seasons and communicate clearly with buyers. Processing choices depend on weather, labor, equipment, and the desired product. A method that works well in one farm’s conditions may not be the best option for another, so local trials and good record keeping are valuable.
TEXT,
                    'source_label' => 'Department of Agriculture — Coffee Processing Facility Guidance',
                    'source_url' => 'https://www.da.gov.ph/wp-content/uploads/2025/08/mo40_s2025.pdf',
                ],
            ],
            'culture-and-heritage' => [
                'the-barako-coffee-ritual' => [
                    'heading' => 'A living practice, not one fixed recipe',
                    'text' => <<<'TEXT'
Coffee customs are learned and adapted in homes, farms, workplaces, and cafés. People may remember a parent or grandparent’s preferred roast, the cup used for visitors, or the time of day when coffee was shared. These memories can be meaningful without being identical across Batangas. A responsible description makes room for differences in household practice, taste, and access to coffee.

When documenting a recipe or personal story, record who shared it and the context in which it is used, with that person’s permission. Oral history can preserve details that a public record does not contain, but an interview is one person’s account rather than a rule for everyone. Together, many carefully credited accounts can show how Kapeng Barako continues to change while remaining connected to place.
TEXT,
                    'source_label' => 'Buenaflor (2025) — Kapeng Barako and Batangueño Identity',
                    'source_url' => 'https://jabedu.com/index.php/jabedu/article/view/49',
                ],
                'kapeng-barako-batangas-identity' => [
                    'heading' => 'Identity is carried by many voices',
                    'text' => <<<'TEXT'
A 2025 qualitative study of Kapeng Barako uses historical analysis, interviews, and cultural documentation to examine how coffee is connected with Batangueño identity. Its approach is useful because heritage is not only a date or a product description: it also includes the memories and interpretations of people who grow, prepare, sell, study, and drink coffee.

The study is one contribution to a larger conversation, not a statement that every resident feels the same way. Local identity changes across generations and communities. Recording different perspectives, naming contributors when they agree, and preserving the words people actually use helps an encyclopedia represent that variety with respect. It also allows future readers to ask how the role of coffee changes as farms, markets, and daily habits change.
TEXT,
                    'source_label' => 'Buenaflor (2025) — Kapeng Barako and Batangueño Identity',
                    'source_url' => 'https://jabedu.com/index.php/jabedu/article/view/49',
                ],
                'coffee-work-and-everyday-exchange' => [
                    'heading' => 'The people behind each harvest',
                    'text' => <<<'TEXT'
Coffee depends on coordinated work over a long season: maintaining trees, watching for flowers and fruit, picking ripe cherries, sorting, processing, drying, hulling, roasting, and preparing the drink. A coffee-growing community holds practical knowledge at each step. Some of that knowledge is technical, while some is learned through observation, family practice, and experience with a particular farm.

The Philippine Coffee Technoguide separates field management, harvesting, and processing into distinct parts of production. That structure helps show why quality and livelihood are linked across the chain. Recognizing growers and workers as knowledge holders gives a fuller account than focusing only on the final cup. It also encourages visitors and students to ask who did the work and how those skills are passed along.
TEXT,
                    'source_label' => 'Department of Agriculture — Coffee Technoguide',
                    'source_url' => 'https://ati2.da.gov.ph/ati-4b/content/sites/default/files/2022-11/Coffee%20Technoguide%20w%20page%20numbers.pdf',
                ],
                'why-this-heritage-matters' => [
                    'heading' => 'Heritage can support the next generation',
                    'text' => <<<'TEXT'
Heritage work can connect historical memory with practical support for today’s growers. In 2024, the Philippine News Agency reported that Batangas partners were preparing a code of practice for Kapeng Barako production and working toward collective mark registration. The report described farmers, provincial offices, schools, historians, and national agencies taking part in that effort.

Such initiatives show how heritage can be used to strengthen shared identity and product knowledge. Standards and labels are most useful when local producers help shape them and when claims can be checked. They do not replace the stories of individual farms or households. Education, accurate sourcing, fair credit, and the continued participation of coffee-growing communities all help heritage remain a living practice.
TEXT,
                    'source_label' => 'Philippine News Agency — Batangas to standardize Kapeng Barako production',
                    'source_url' => 'https://www.pna.gov.ph/index.php/articles/1234070',
                ],
                'keeping-coffee-heritage-alive' => [
                    'heading' => 'Preserve knowledge with care',
                    'text' => <<<'TEXT'
Preservation can include oral histories, photographs of farms, records of processing methods, and lessons from local coffee organizations. Each type of record has its own context. A photograph should identify its place and contributor when known; an interview should keep the speaker’s words and permission clear; and an agricultural instruction should name its source and date so readers can tell whether it may need updating.

Current efforts to describe Kapeng Barako production involve growers and local institutions, as reported by the Philippine News Agency. Their participation matters because a heritage project should help communities represent their own knowledge. A public encyclopedia can contribute by crediting sources, inviting corrections, and making new information accessible to students and farmers while respecting the people who share it.
TEXT,
                    'source_label' => 'Philippine News Agency — Batangas to standardize Kapeng Barako production',
                    'source_url' => 'https://www.pna.gov.ph/index.php/articles/1234070',
                ],
            ],
            'diseases' => [
                'coffee-leaf-rust-signs-and-science' => [
                    'heading' => 'Why leaf loss affects the tree',
                    'text' => <<<'TEXT'
Leaves support a coffee tree’s growth by capturing light and supplying energy for new shoots and fruit. When severe rust infection leads to premature leaf drop, the tree loses part of that productive canopy. The result can extend beyond the visible spots: a weakened tree may have fewer healthy leaves to support later growth, and repeated stress can affect the next crop. The effect varies with the tree, weather, crop load, and how widespread the disease is.

This is one reason that monitoring should include the whole plant and not only the most orange-looking leaf. Record whether leaves are falling, whether branches are becoming bare, and whether fruit development appears affected. These observations help an agricultural adviser assess severity and decide whether the pattern matches rust or another problem.
TEXT,
                    'source_label' => 'DA Bureau of Plant Industry — Coffee Production Guide',
                    'source_url' => 'https://library.buplant.da.gov.ph/images/1641945499COFFEE.pdf',
                ],
                'coffee-berry-borer-insect-pest' => [
                    'heading' => 'Harvest sanitation breaks the hiding places',
                    'text' => <<<'TEXT'
Coffee berry borers can persist in cherries left on the tree or on the ground after harvest. Philippine eAgri coffee guidance therefore emphasizes regular picking and collecting remaining fruit after the main harvest. This practice is often called sanitation or “stripping” the remaining cherries: it reduces fruit that could shelter the pest between crops. The amount and timing of labor needed depend on the size and condition of the farm.

Use traps as a way to observe pest activity, not as proof that damage is absent when few insects are caught. Inspect cherries and beans as well, keep harvest notes, and ask a local agriculturist to interpret the findings. A combined record of trap catches, fruit damage, and leftover cherries is more useful for planning than relying on one signal alone.
TEXT,
                    'source_label' => 'Philippine eAgri — Coffee growing guide',
                    'source_url' => 'https://eagri.e.gov.ph/commodities/coffee',
                ],
                'monitoring-coffee-farm-health' => [
                    'heading' => 'Keep a season-by-season farm record',
                    'text' => <<<'TEXT'
Monitoring becomes more useful when observations are repeated over time. Keep a simple notebook or digital record with the date, plot or row, variety if known, recent weather, symptoms observed, and any action taken. Photograph the same plant or marked area again during later visits. Comparing records can reveal whether a problem is isolated, seasonal, or spreading, and can help an extension worker understand what changed.

Separate what was observed from what is suspected. For example, “orange powder on the underside of several leaves” is an observation; “leaf rust” is a diagnosis to confirm. This habit reduces guesswork and makes follow-up more effective. The Department of Agriculture’s guidance includes monitoring within routine farm management, alongside crop care and harvest work.
TEXT,
                    'source_label' => 'Department of Agriculture — Coffee Technoguide',
                    'source_url' => 'https://ati2.da.gov.ph/ati-4b/content/sites/default/files/2022-11/Coffee%20Technoguide%20w%20page%20numbers.pdf',
                ],
                'how-coffee-leaf-rust-spreads' => [
                    'heading' => 'Weather and movement both matter',
                    'text' => <<<'TEXT'
The Hawaiʻi Department of Agriculture notes that coffee leaf rust spores need free water on a leaf to germinate and that wind, rain, or contact with people, animals, and objects can move spores. This explains why wet weather and movement through a planting can matter, but it does not mean every wet period will cause an outbreak. Plant susceptibility, existing infection, canopy conditions, and local weather all interact.

For farm observations, note recent rain or prolonged dew and where the first symptoms appear. When practical, avoid handling visibly infected foliage and then moving directly through healthy blocks with wet hands or tools. Ask a local plant-health adviser what sanitation steps fit the farm; advice developed for a different region may not transfer directly to Batangas.
TEXT,
                    'source_label' => 'Hawaiʻi Department of Agriculture — Coffee Leaf Rust',
                    'source_url' => 'https://hdoa.hawaii.gov/pi/files/2021/10/NPA-20-03-Coffee-leaf-rust-10-2021.pdf',
                ],
                'coffee-leaf-spots-and-diagnosis' => [
                    'heading' => 'A useful sample for an adviser',
                    'text' => <<<'TEXT'
When asking for help, bring photographs of both leaf surfaces and a few leaves showing different stages of the problem. Include a wider photograph of the affected plant and where it sits in the plot. Note the date first seen, whether symptoms followed rain or another event, and whether nearby trees show the same pattern. This context makes it easier to distinguish a localized injury from a problem affecting a larger area.

If a plant-health office asks for a physical sample, follow its instructions for selecting and transporting it. Do not mail or carry potentially infected plant material across borders or to another farm without official guidance. A photo and a clear farm record are often the safest starting point for a first consultation.
TEXT,
                    'source_label' => 'DA Bureau of Plant Industry — Coffee Production Guide',
                    'source_url' => 'https://library.buplant.da.gov.ph/images/1641945499COFFEE.pdf',
                ],
                'coffee-dieback-thread-blight-and-other-problems' => [
                    'heading' => 'Separate the symptom from its cause',
                    'text' => <<<'TEXT'
Branch death, leaf loss, and visible fungal growth are signs to investigate, not complete diagnoses. Similar symptoms may result from disease, insects, water stress, nutrient problems, injury, or a combination of factors. The Bureau of Plant Industry coffee guide discusses dieback and thread blight among several coffee problems, underscoring that different causes can require different responses.

Before pruning heavily or applying a treatment, mark affected branches, photograph the pattern, and ask a qualified adviser to inspect the plant when possible. If removal of plant material is recommended, ask how it should be handled locally. Correct identification helps avoid removing healthy growth or applying an unsuitable product.
TEXT,
                    'source_label' => 'DA Bureau of Plant Industry — Coffee Production Guide',
                    'source_url' => 'https://library.buplant.da.gov.ph/images/1641945499COFFEE.pdf',
                ],
                'coffee-farm-health-check' => [
                    'heading' => 'Turn observations into a farm plan',
                    'text' => <<<'TEXT'
At the end of each walk, summarize which areas need another check and which questions need an adviser. A small map of rows or blocks can help locate repeat observations. Keep notes about harvest dates, pruning, weather, and any previous diagnosis with the same record. Over time, these details help distinguish a recurring seasonal pattern from a new problem.

Use the record to discuss a specific next step with the municipal agriculturist or plant-health office. Ask what evidence supports the diagnosis, what non-chemical practices may help, and how results should be monitored. If a pesticide is recommended, confirm it is currently registered for coffee and follow its label and local guidance; do not rely on copied rates from an old article.
TEXT,
                    'source_label' => 'Department of Agriculture — Coffee Technoguide',
                    'source_url' => 'https://ati2.da.gov.ph/ati-4b/content/sites/default/files/2022-11/Coffee%20Technoguide%20w%20page%20numbers.pdf',
                ],
            ],
        ];
    }

    public function up(): void
    {
        foreach ($this->additions() as $topicSlug => $entries) {
            $topicId = DB::table('encyclopedia_topics')->where('slug', $topicSlug)->value('id');
            if (!$topicId) continue;

            foreach ($entries as $entrySlug => $addition) {
                $entry = DB::table('encyclopedia_entries')
                    ->where('topic_id', $topicId)
                    ->where('slug', $entrySlug)
                    ->first(['id', 'body', 'source_label', 'source_url']);
                if (!$entry) continue;

                $marker = '## '.$addition['heading'];
                if (str_contains($entry->body, $marker)) continue;

                DB::table('encyclopedia_entries')->where('id', $entry->id)->update([
                    'body' => rtrim($entry->body)."\n\n".$marker."\n\n".trim($addition['text']),
                    'source_label' => $entry->source_label ?: $addition['source_label'],
                    'source_url' => $entry->source_url ?: $addition['source_url'],
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Keep article expansions and any admin edits when rolling back.
    }
};
