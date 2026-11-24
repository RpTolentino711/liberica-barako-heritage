<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $roadmap = 'https://www.da.gov.ph/wp-content/uploads/2023/05/Philippine-Coffee-Industry-Roadmap.pdf';
        $credits = [
            ['Growing', 'pexels-carlostorres-18505518.jpg', 'Philippine Coffee Industry Roadmap', $roadmap.'#page=55', '18505518', 'Carlos Torres'],
            ['Harvesting', 'pexels-michael-burrows-7125451.jpg', 'Philippine Coffee Industry Roadmap', $roadmap.'#page=56', '7125451', 'Michael Burrows'],
            ['Processing and drying', 'pexels-anderson-martinsz-34634578.jpg', 'Philippine Coffee Industry Roadmap', $roadmap.'#page=56', '34634578', null],
            ['Roasting and brewing', 'pexels-thomas-plets-1139798-2434159.jpg', 'National Science Teaching Association · From Bean to Cup', 'https://www.nsta.org/bean-cup', '2434159', null],
        ];

        DB::transaction(function () use ($credits) {
            foreach ($credits as [$title, $file, $source, $url, $photoId, $creator]) {
                $row = DB::table('journey_steps')->where('import_status', 'archived')
                    ->where('title', $title)->where('media_path', 'assets/'.$file);
                (clone $row)->whereNull('source')->whereNull('source_url')
                    ->update(['source' => $source, 'source_url' => $url]);
                (clone $row)->whereNull('photo_url')->whereNull('creator')
                    ->update(['photo_url' => 'https://www.pexels.com/photo/'.$photoId.'/', 'creator' => $creator]);
                (clone $row)->whereNull('license')->whereNull('license_url')
                    ->update(['license' => 'Pexels License', 'license_url' => 'https://www.pexels.com/license/']);
            }
        });
    }

    public function down(): void
    {
        // Credits are editable content. Keep them on rollback to preserve subsequent edits.
    }
};
