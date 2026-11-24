<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private function entries(): array
    {
        return [
            [
                'topic' => 'history-of-philippine-coffee', 'title' => 'When did coffee first reach Lipa?',
                'slug' => 'when-coffee-first-reached-lipa', 'sort' => 0,
                'summary' => 'Local histories preserve more than one account of how coffee first took root around Lipa.',
                'body' => <<<'TEXT'
The exact first planting date is not settled by a single surviving record. The City Government of Lipa’s history page reports that Bureau of Plant Industry records place coffee seeds in Barrio Pinagtong-ulan in the 1740s, brought from Mexico by a Franciscan friar. The same account also describes a local story about Capitan Francisco Mantuano planting seedlings in his backyard in 1784 and sharing harvested seed with nearby barrios.

An early United States Census report on the Philippine Islands observed that official provincial and parish archives did not record the first introduction into Batangas, while popular accounts described coffee spreading through natural growth. Modern historical scholarship has likewise cautioned readers against treating a single dramatic origin story as fully proven.

These accounts are not interchangeable evidence: one is a later municipal summary, another an older census observation, and others preserve local memory. Read together, they show that coffee became established over time and that the documentary record is incomplete. The responsible conclusion is that Lipa’s coffee heritage is well documented, while the precise first planting date remains uncertain.
TEXT,
                'source_label' => 'City Government of Lipa — Local History', 'source_url' => 'https://lipa.gov.ph/history/',
            ],
            [
                'topic' => 'history-of-philippine-coffee', 'title' => 'Lipa’s nineteenth-century coffee boom',
                'slug' => 'lipa-nineteenth-century-coffee-boom', 'sort' => 1,
                'summary' => 'Coffee reshaped Lipa’s trade and civic life during the nineteenth century.',
                'body' => <<<'TEXT'
The City Government of Lipa describes coffee cultivation expanding under local leaders in the early nineteenth century. By the second half of the century, coffee had become a major source of trade and income for the town. The city’s historical account says that Lipa coffee known as caracolillo, or peaberry, was in demand and that export volume and unit values rose during the 1870s.

The boom was visible beyond the farms. Coffee wealth supported merchants, stores, transport, and civic life. On October 21, 1887, Lipa was elevated to Villa de Lipa by Queen Regent Maria Cristina. The municipal history connects that honor with the prosperity associated with coffee, though it also describes the social inequalities that accompanied wealth.

“Golden age” is a useful shorthand, but it can hide the work behind a commodity: cultivation, harvesting, sorting, transport, finance, and household labor. The town’s coffee story is best understood both as an agricultural achievement and as a period whose benefits were not shared equally by everyone.
TEXT,
                'source_label' => 'City Government of Lipa — Local History', 'source_url' => 'https://lipa.gov.ph/history/',
            ],
            [
                'topic' => 'history-of-philippine-coffee', 'title' => 'The end of the boom and the lessons of history',
                'slug' => 'end-of-lipa-coffee-boom', 'sort' => 2,
                'summary' => 'Coffee production declined, but the reasons deserve careful reading rather than a single-cause explanation.',
                'body' => <<<'TEXT'
Lipa’s municipal history says a destructive plant problem known locally as “bagombong” reached the plantations in the late 1880s and was followed by a sharp decline in coffee production. The historical name does not, by itself, provide a modern laboratory identification of the organism involved, so it should not automatically be equated with a specific present-day disease.

Research on nineteenth-century Lipa has revisited stories about the town’s wealth, coffee monopoly, and the timing of the blight. The wider history also includes epidemics, changing trade conditions, political upheaval, and wars that disrupted economic life. Coffee did not vanish from Batangas, but it ceased to dominate Lipa’s economy as it once had.

This distinction matters: local memory preserves how communities experienced a crisis, while historical research compares accounts and asks what records can actually support. The decline is part of the heritage, too. It explains why conserving coffee knowledge today involves more than celebrating past prosperity; it also means understanding vulnerability, crop health, livelihoods, and adaptation.
TEXT,
                'source_label' => 'University of Adelaide — Demythologising the history of coffee in Lipa', 'source_url' => 'https://digital.library.adelaide.edu.au/items/9186a96e-1ad5-4707-ab21-cd851ee6640f/full',
            ],
            [
                'topic' => 'the-science-of-liberica', 'title' => 'What is Coffea liberica?',
                'slug' => 'what-is-coffea-liberica', 'sort' => 0,
                'summary' => 'Liberica is a coffee species with West African origins and a long history of cultivation in Southeast Asia, including the Philippines.',
                'body' => <<<'TEXT'
Coffea liberica belongs to the coffee genus Coffea and the botanical family Rubiaceae. Kew’s Plants of the World Online accepts C. liberica as a species and places its native range in West Africa, from Sierra Leone to southwestern Nigeria. The species is now cultivated in many tropical regions, including the Philippines.

In Batangas, Liberica is closely associated with the name Kapeng Barako. Philippine agricultural guides describe Liberica as a locally important coffee and distinguish it from Arabica and Robusta. Common names, commercial labels, and botanical classifications do not always line up perfectly, so a careful description names the species when that information is known.

Taxonomy also changes as botanists study plants more closely. Some agricultural references group “Excelsa” among familiar coffee types, while current taxonomic work may treat excelsa as Coffea dewevrei rather than as a Liberica variety. For that reason, this encyclopedia uses “Liberica” for C. liberica and notes when older or local sources use a different grouping.
TEXT,
                'source_label' => 'Kew Science — Plants of the World Online: Coffea liberica', 'source_url' => 'https://powo.science.kew.org/taxon/urn:lsid:ipni.org:names:77368785-1',
            ],
            [
                'topic' => 'the-science-of-liberica', 'title' => 'Meet the Liberica coffee tree',
                'slug' => 'meet-the-liberica-coffee-tree', 'sort' => 1,
                'summary' => 'Large leaves, fragrant flowers, and sizeable cherries make Liberica visually distinctive.',
                'body' => <<<'TEXT'
Liberica is a tree, not a small shrub. Singapore’s National Parks Board describes Coffea liberica as capable of reaching about 18 metres in its environment. Philippine agricultural guidance gives a lower cultivated-plant height, illustrating how plant size depends on site conditions and farm management.

Its leaves are broad, leathery, and elliptic, often with a wavy edge. The white flowers are fragrant and grow in clusters. The cherry-like fruit is relatively large; a mature fruit commonly contains two seeds, which are the “beans” used for coffee. Each of these features can help with observation, but appearance alone is not a reliable way to identify every cultivar or diagnose a plant problem.

In a farm or garden, the tree’s mature size affects spacing, pruning, shade, harvesting, and access. Liberica is sometimes described as hardy, but that does not mean it is immune to stress or pests. Soil, water, weather, planting material, and care all influence how an individual tree grows. Local agricultural recommendations should be matched to the site rather than copied from a different climate.
TEXT,
                'source_label' => 'National Parks Board Singapore — Coffea liberica profile', 'source_url' => 'https://www.nparks.gov.sg/florafaunaweb/flora/2/8/2820',
            ],
            [
                'topic' => 'the-science-of-liberica', 'title' => 'From coffee cherry to green bean',
                'slug' => 'from-cherry-to-green-bean', 'sort' => 2,
                'summary' => 'Harvest timing and post-harvest handling shape the raw coffee that will later be roasted.',
                'body' => <<<'TEXT'
Coffee begins as a fruit. Inside the ripe cherry are seeds surrounded by fruit layers. Philippine Department of Agriculture training material recommends selectively picking ripe red cherries and keeping harvested fruit clean and off the ground. Mixing damaged, overripe, and sound cherries can make quality harder to control.

In a washed process, the outer pulp is removed, the beans are fermented to loosen remaining mucilage, then washed and dried. In a dry process, cherries are dried more fully before the outer fruit layers are removed. Semi-dry methods combine steps from both approaches. Each method needs clean equipment, careful timing, and even drying; poor handling can cause defects or encourage unwanted microbial growth.

After drying, hulling removes the remaining coverings. Beans are cleaned and sorted before roasting. Roasting creates and changes aroma compounds through heat, while grind size and brewing method affect how those compounds reach the cup. Processing is not a single recipe: producers choose methods based on climate, equipment, labor, and the result they are aiming for.
TEXT,
                'source_label' => 'Department of Agriculture — Coffee Technoguide', 'source_url' => 'https://ati2.da.gov.ph/ati-4b/content/sites/default/files/2022-11/Coffee%20Technoguide%20w%20page%20numbers.pdf',
            ],
            [
                'topic' => 'culture-and-heritage', 'title' => 'Kapeng Barako and Batangueño identity',
                'slug' => 'kapeng-barako-batangas-identity', 'sort' => 0,
                'summary' => 'Coffee in Batangas carries agricultural history and meanings shaped by local people.',
                'body' => <<<'TEXT'
Kapeng Barako is more than a drink name in Batangas. It is connected to the region’s coffee-growing history, family memories, local work, and pride in place. A 2025 qualitative study by a University of Batangas researcher draws on interviews, oral histories, and cultural documentation to examine how residents connect coffee with hospitality, community, and Batangueño identity.

Those meanings are living and varied. A family’s way of preparing coffee may differ from a neighbor’s; farmers, roasters, sellers, and drinkers may use “Barako” in different contexts. One account cannot speak for every Batangueño. Listening to local voices and identifying who shared a story are important parts of documenting heritage respectfully.

Coffee heritage also includes practical knowledge: how cherries are selected, how beans are dried, how roasting is judged, and how a cup is served. Recording these practices—with permission and proper credit—helps future readers understand that culture is not frozen in the past. It continues as people adapt traditions to new farms, tools, markets, and generations.
TEXT,
                'source_label' => 'Buenaflor (2025), Journal for the Agriculture, Biotechnology and Education', 'source_url' => 'https://jabedu.com/index.php/jabedu/article/view/49',
            ],
            [
                'topic' => 'culture-and-heritage', 'title' => 'Coffee, work, and everyday exchange',
                'slug' => 'coffee-work-and-everyday-exchange', 'sort' => 1,
                'summary' => 'A cup of coffee connects farm work, processing skill, trade, and shared time.',
                'body' => <<<'TEXT'
The history of coffee is also a history of people doing work. Trees must be tended over seasons; ripe fruit is picked by hand or with other harvesting systems; cherries are sorted, processed, dried, and stored; and roasted coffee reaches homes and cafés through local trade. Each step uses knowledge that is easy to overlook when the final product is treated as a simple commodity.

In Lipa, coffee commerce shaped the streets, shops, and civic life described in the city’s historical account. Today, community coffee gatherings and research activities continue that conversation in new forms. They can connect growers, educators, small enterprises, students, and visitors.

Hospitality is often mentioned in local accounts of Kapeng Barako, but there is no single required ritual. How coffee is prepared and offered can vary by household, occasion, and personal taste. A respectful encyclopedia describes documented practices while leaving room for that variation instead of presenting one custom as universal.
TEXT,
                'source_label' => 'City Government of Lipa — Local History', 'source_url' => 'https://lipa.gov.ph/history/',
            ],
            [
                'topic' => 'culture-and-heritage', 'title' => 'Keeping coffee heritage alive',
                'slug' => 'keeping-coffee-heritage-alive', 'sort' => 2,
                'summary' => 'Heritage preservation works best when accurate records, local participation, and living farms support one another.',
                'body' => <<<'TEXT'
Preserving Kapeng Barako heritage means caring for both knowledge and the people who keep it alive. Oral histories can record memories that are absent from formal archives. Farm visits and photographs can document trees and practices, but people and places should be identified only with their consent. Photographers and contributors deserve clear credit.

Accuracy matters, too. Historical dates should be connected to the records that support them; scientific claims should identify their source; and commercial descriptions should not promise the same flavor or quality from every crop. Local communities should be able to review how their stories and traditions are represented.

Conservation is not only about looking backward. It includes helping new growers learn cultivation and post-harvest skills, encouraging students to ask questions, and making reliable local information available to the public. A heritage website can contribute by linking readers to sources, updating content when stronger evidence appears, and treating Batangas coffee as a living agricultural and cultural practice.
TEXT,
                'source_label' => 'Buenaflor (2025), Journal for the Agriculture, Biotechnology and Education', 'source_url' => 'https://jabedu.com/index.php/jabedu/article/view/49',
            ],
            [
                'topic' => 'diseases', 'title' => 'Coffee leaf rust: signs and science',
                'slug' => 'coffee-leaf-rust-signs-and-science', 'sort' => 0,
                'summary' => 'Leaf rust is a fungal disease that can weaken coffee trees by damaging foliage.',
                'body' => <<<'TEXT'
Coffee leaf rust is caused by the fungus Hemileia vastatrix. Philippine Department of Agriculture materials describe early yellowish, translucent spots on the underside of leaves. As infection develops, yellow to orange powdery spores may appear beneath the leaf. Severe disease can contribute to leaf drop, leaving branches with less foliage to support growth and fruit.

These signs are useful clues, not a complete diagnosis. Nutrient problems, other diseases, insects, and environmental stress can also discolor or damage leaves. Check several leaves and plants, record where symptoms appear, and seek help from a municipal agriculturist or plant-health professional when the cause is uncertain.

Varieties differ in their susceptibility, and research in the Philippines is studying ways to detect resistance in local coffee. No single measure protects every farm. Healthy planting material, regular observation, appropriate pruning and nutrition, and locally relevant advice all form part of responsible crop care. Use only products registered for the crop and follow current label instructions if chemical control is recommended by a qualified local adviser.
TEXT,
                'source_label' => 'Department of Agriculture — Coffee production guide', 'source_url' => 'https://library.buplant.da.gov.ph/images/1641945499COFFEE.pdf',
            ],
            [
                'topic' => 'diseases', 'title' => 'Coffee berry borer: an insect pest, not a disease',
                'slug' => 'coffee-berry-borer-insect-pest', 'sort' => 1,
                'summary' => 'The berry borer damages coffee cherries and beans; understanding its life cycle helps guide monitoring.',
                'body' => <<<'TEXT'
The coffee berry borer, Hypothenemus hampei, is a small beetle—not a plant disease. The female enters a coffee cherry and lays eggs; developing insects feed inside the fruit and can damage the bean. A small entry hole near the tip of a cherry is one sign to inspect, although a hole alone should be checked alongside other evidence.

Unharvested cherries left on trees or on the ground can allow the pest to persist between harvests. The Philippine government’s eAgri coffee guidance emphasizes regular picking and collecting remaining fruit after harvest as cultural practices. Traps can help monitor activity, but they are only one part of a broader management plan.

Farm response should be based on monitoring, harvest timing, sanitation, and local conditions. Avoid copying pesticide rates from old guides or another country: registrations and labels change. Ask the municipal agriculturist or the Bureau of Plant Industry for current recommendations, and use only products registered for coffee exactly as their labels direct.
TEXT,
                'source_label' => 'Philippine eAgri — Coffee growing guide', 'source_url' => 'https://eagri.e.gov.ph/commodities/coffee',
            ],
            [
                'topic' => 'diseases', 'title' => 'Monitoring a coffee farm responsibly',
                'slug' => 'monitoring-coffee-farm-health', 'sort' => 2,
                'summary' => 'A simple observation routine helps growers notice changes early and seek the right advice.',
                'body' => <<<'TEXT'
Farm monitoring starts with careful observation. Walk different parts of the plot and look at leaves, stems, roots where visible, flowers, and cherries. Record the date, location, plant age, affected plant parts, and whether symptoms are spreading. Photos taken from the same angle over time can help describe change to an agricultural technician.

Separate three kinds of problems in your notes: diseases caused by pathogens, insect or mite pests, and non-living stresses such as drought, waterlogging, nutrient imbalance, or mechanical injury. They can look similar, and several can occur together. Do not diagnose a whole farm from one leaf or one internet photo.

Integrated pest management combines prevention, monitoring, cultural practices, and other controls chosen for the specific problem. Philippine research is developing detection methods for resistance to coffee leaf rust and coffee white stem borer, while national guidance also stresses good agricultural and post-harvest practices. For a suspected outbreak, contact the municipal agriculturist or plant-health office before applying a treatment. Use current Philippine registrations and product labels; never guess a dose.
TEXT,
                'source_label' => 'DOST-PCAARRD — Philippine coffee pest and disease resistance research', 'source_url' => 'https://www.pcaarrd.dost.gov.ph/index.php/quick-information-dispatch-qid-articles/early-detection-of-resistance-to-coffee-white-stem-borer-and-leaf-rust-in-philippine-coffee-varieties-made-possible-via-lamp-technology',
            ],
        ];
    }

    public function up(): void
    {
        Schema::table('encyclopedia_entries', function (Blueprint $table) {
            $table->string('source_label', 240)->nullable()->after('image_path');
            $table->string('source_url', 2048)->nullable()->after('source_label');
        });

        $topics = DB::table('encyclopedia_topics')->pluck('id', 'slug');
        $now = now();
        foreach ($this->entries() as $entry) {
            $topicId = $topics[$entry['topic']] ?? null;
            if (!$topicId || DB::table('encyclopedia_entries')->where('topic_id', $topicId)->where('slug', $entry['slug'])->exists()) {
                continue;
            }

            DB::table('encyclopedia_entries')->insert([
                'topic_id' => $topicId,
                'title' => $entry['title'],
                'slug' => $entry['slug'],
                'summary' => $entry['summary'],
                'body' => $entry['body'],
                'source_label' => $entry['source_label'],
                'source_url' => $entry['source_url'],
                'is_published' => true,
                'sort_order' => $entry['sort'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('encyclopedia_entries')->whereIn('slug', array_column($this->entries(), 'slug'))->delete();
        Schema::table('encyclopedia_entries', function (Blueprint $table) {
            $table->dropColumn(['source_label', 'source_url']);
        });
    }
};
