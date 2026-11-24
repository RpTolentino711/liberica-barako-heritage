<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private function entries(): array
    {
        return [
            [
                'title' => 'How coffee leaf rust spreads through a farm',
                'slug' => 'how-coffee-leaf-rust-spreads',
                'summary' => 'The disease cycle explains why wet leaves, regular observation, and early advice matter.',
                'body' => <<<'TEXT'
## From spore to leaf
Coffee leaf rust is caused by Hemileia vastatrix, a fungus that grows in living coffee leaf tissue. Spores landing on a leaf need liquid water, such as rain or heavy dew, to germinate. The fungus enters through pores on the lower leaf surface. The infection is not immediately visible: yellowish marks may take several weeks to develop into the characteristic orange, powdery patches on the underside.

## Repeating cycles
As an infected patch matures, it produces more spores. Rain splash and wind can move spores to other leaves, while contaminated tools, clothing, or contact with plants may also contribute to movement. Warm, humid, wet conditions can favor infection, but the exact timing and severity vary with the site, plant, and weather. A single observation cannot predict how a particular Batangas farm will be affected.

## Why early observation helps
Look at both sides of leaves and check several plants across the plot, especially after periods of wet weather. Note where symptoms first appear and whether they are increasing. Avoid moving among visibly affected plants and healthy blocks with wet foliage when practical; clean tools and follow local plant-health advice. These steps support monitoring, but they do not replace a diagnosis by an agricultural professional.

## Reading the evidence
Hawaii's Department of Agriculture describes the rust's moisture-dependent infection and visible symptoms. Philippine production guidance documents the disease in coffee and emphasizes monitoring and sound crop management. Recommendations developed for another island or climate should not be copied as a treatment schedule for Batangas.
TEXT,
                'source_label' => 'Hawaiʻi Department of Agriculture — Coffee Leaf Rust; DA Bureau of Plant Industry — Coffee Production Guide',
                'source_url' => 'https://hdoa.hawaii.gov/pi/files/2021/10/NPA-20-03-Coffee-leaf-rust-10-2021.pdf',
                'image' => 'assets/pexels-torrans-mayorquin-505468205-20190062.jpg',
                'sort' => 0,
            ],
            [
                'title' => 'When leaf spots look alike',
                'slug' => 'coffee-leaf-spots-and-diagnosis',
                'summary' => 'Yellowing and leaf spots can have several causes, so check the pattern before naming a disease.',
                'body' => <<<'TEXT'
## Compare both sides of the leaf
Rust often produces yellow to orange powdery patches on the underside of a leaf, with yellowish areas visible above. Other problems can create spots or yellowing without that orange powder. The Department of Agriculture's coffee guide also describes Cercospora leaf spot, which may show brown lesions with darker margins. Similar looking damage can result from insects, nutrient imbalance, drought, waterlogging, sun exposure, or physical injury.

## Check the pattern, not just one mark
Observe several leaves at different heights and on more than one tree. Record whether the marks are mostly on older or younger leaves, whether they occur along edges or between veins, and whether fruit or stems are also affected. Photograph a few examples in good light, including the upper and lower leaf surfaces. Note recent weather and any farm activities that may help explain when symptoms began.

## Avoid a guess-based treatment
One discolored leaf is not enough to identify a pathogen. A municipal agriculturist, extension worker, or plant-health professional can help assess the symptoms and decide whether laboratory confirmation is needed. Bring clear photos and your observations. Do not apply a fungicide or fertilizer simply because it was used for a different looking problem; an incorrect treatment can waste money and obscure the cause.

## Use local guidance
The Philippine Bureau of Plant Industry coffee production guide describes common symptoms, while the Department of Agriculture's Coffee Technoguide includes farm monitoring as part of crop management. Use these references to support a conversation with a local adviser, not as a substitute for site-specific diagnosis.
TEXT,
                'source_label' => 'DA Bureau of Plant Industry — Coffee Production Guide',
                'source_url' => 'https://library.buplant.da.gov.ph/images/1641945499COFFEE.pdf',
                'image' => 'assets/pexels-michael-burrows-7125451.jpg',
                'sort' => 1,
            ],
            [
                'title' => 'Dieback, thread blight, and other coffee problems',
                'slug' => 'coffee-dieback-thread-blight-and-other-problems',
                'summary' => 'Coffee plants can be affected by different diseases and growing stresses that require different responses.',
                'body' => <<<'TEXT'
## Dieback is a symptom with several possible causes
Dieback describes branches or shoots drying and dying back; it is not one diagnosis by itself. The Philippine Bureau of Plant Industry guide notes that coffee dieback may be physiological or associated with pathogens. Crop load, nutrient status, water stress, injury, and infection can all be relevant. The visible result alone cannot show which factor is responsible, so record where the damage starts and how it progresses.

## Thread blight in wet conditions
The same guide describes thread blight as a fungal problem that can occur in humid or rainy conditions. Fine fungal strands may develop on affected branches and spread around plant parts. If a grower sees web-like growth, dead leaves held against branches, or damage spreading through a damp section, photograph the pattern and ask a local crop adviser to confirm the cause. Many unrelated organisms can look like threads or mould in a photograph.

## Keep observations specific
Record whether affected branches are isolated or widespread, whether nearby trees show similar symptoms, and whether the problem follows a low, wet area or a particular planting row. Note recent pruning, storm damage, shade, drainage, and fertilizer applications. These details help an adviser separate a disease from environmental stress or management injury.

## Choose a response after diagnosis
Pruning, sanitation, nutrition, drainage, and plant protection can each matter in particular situations, but there is no single response for every kind of dieback or blight. Ask the municipal agriculturist or plant-health office to identify the likely cause before treating the farm. Follow current Philippine product registrations and label directions whenever a regulated crop-protection product is recommended.
TEXT,
                'source_label' => 'DA Bureau of Plant Industry — Coffee Production Guide',
                'source_url' => 'https://library.buplant.da.gov.ph/images/1641945499COFFEE.pdf',
                'image' => 'assets/pexels-pranjall-kumar-150768-8555658.jpg',
                'sort' => 2,
            ],
            [
                'title' => 'A practical coffee farm health check',
                'slug' => 'coffee-farm-health-check',
                'summary' => 'A repeatable observation routine helps growers notice changes and share useful information with advisers.',
                'body' => <<<'TEXT'
## Walk the whole planting
Check more than the easiest row or the most visibly affected tree. Walk different parts of the farm and observe leaves, shoots, trunks, flowers, and cherries. Look at the upper and lower leaf surfaces. Mark the location of unusual symptoms and whether they appear on one plant, a small group, or a wider area.

## Keep a simple record
Write down the date, location, coffee type or variety if known, plant age if known, affected plant parts, and recent weather. Take clear photographs from a consistent distance and include a close view of the symptom. Repeat the same observations later so changes can be compared. Keep the notes factual: describe what you see rather than deciding the cause from a single clue.

## Connect scouting with routine care
The Department of Agriculture's Coffee Technoguide includes regular monitoring for pests and diseases alongside weeding, fertilization, pruning, and harvesting. The right timing and practice depend on the farm. Use soil tests and local recommendations to guide nutrition, maintain access for inspection, and remove plant material only when a qualified adviser recommends a specific sanitation step.

## Ask for help early
If symptoms spread, affect many plants, or threaten a harvest, contact the municipal agriculturist or relevant plant-health office. Share the notes and photographs, and ask what confirmation is needed before taking action. Avoid copying chemical rates from an old publication or an online post. Product registrations and labels can change, and a local adviser can help identify the current, lawful option if treatment is needed.
TEXT,
                'source_label' => 'Department of Agriculture — Coffee Technoguide',
                'source_url' => 'https://ati2.da.gov.ph/ati-4b/content/sites/default/files/2022-11/Coffee%20Technoguide%20w%20page%20numbers.pdf',
                'image' => 'assets/pexels-pranjall-kumar-150768-8555658.jpg',
                'sort' => 3,
            ],
        ];
    }

    public function up(): void
    {
        $topicId = DB::table('encyclopedia_topics')->where('slug', 'diseases')->value('id');
        if (!$topicId) return;

        $sortOrder = (int) DB::table('encyclopedia_entries')->where('topic_id', $topicId)->max('sort_order') + 1;
        $now = now();
        foreach ($this->entries() as $entry) {
            if (DB::table('encyclopedia_entries')->where('topic_id', $topicId)->where('slug', $entry['slug'])->exists()) continue;
            DB::table('encyclopedia_entries')->insert([
                'topic_id' => $topicId,
                'title' => $entry['title'],
                'slug' => $entry['slug'],
                'summary' => $entry['summary'],
                'body' => $entry['body'],
                'image_path' => $entry['image'],
                'source_label' => $entry['source_label'],
                'source_url' => $entry['source_url'],
                'is_published' => true,
                'sort_order' => $sortOrder + $entry['sort'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Keep published educational content intact if this migration is rolled back.
    }
};
