<?php
namespace Database\Seeders;

use App\Models\{Article, Reference, Setting, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $values = [
            'site_name'=>'LIBÉRICA',
            'hero_kicker'=>'A HERITAGE WORTH KEEPING',
            'hero_title'=>'Bold by nature.',
            'hero_accent'=>'Rooted in Batangas.',
            'hero_description'=>'Meet Kapeng Barako, the distinctive Liberica coffee woven into the stories, landscapes, and everyday rituals of Batangas.',
            'contact_email'=>'',
        ];
        foreach ($values as $key=>$value) Setting::firstOrCreate(['key'=>$key],['value'=>$value]);
        $articles = [
            ['heritage','The coffee legacy of Lipa','Lipa grew into a major coffee-trading town in the nineteenth century.','Lipa’s coffee trade reached extraordinary prominence in the nineteenth century. The City Government of Lipa describes the town’s prosperity and its elevation to Villa de Lipa on October 21, 1887. Plant disease struck its coffee plantations soon afterward, changing the region’s economic story. This history helps explain why coffee remains so deeply tied to Lipa’s identity.'],
            ['botany','What makes Liberica distinctive?','Large leaves, large beans, and an expressive character make Liberica memorable.','Coffea liberica is a coffee species native to parts of West and Central Africa and cultivated in the Philippines and other tropical countries. Compared with more familiar commercial coffees, Liberica is known for its relatively large beans, leaves, and tree stature. In the Philippines, it is strongly associated with Kapeng Barako. Exact flavors depend on cultivar, farm, processing, roast, and brewing.'],
            ['cultivation','From coffee tree to cup','Explore the stages of growing, harvesting, processing, and roasting.','Coffee cultivation begins with healthy planting material and careful site management. Ripe coffee cherries are harvested, sorted, processed, dried, hulled, roasted, and finally brewed. Each stage contributes to quality and character. Responsible farming and post-harvest practices can support local coffee communities.'],
            ['culture','The Barako coffee ritual','More than a beverage, coffee is part of social connection.','Kapeng Barako is often remembered as a strong and aromatic coffee connected to Batangueño hospitality and daily life. Recipes and serving styles vary among households and cafés. This website celebrates the experience while encouraging visitors to explore real stories from local people.'],
            ['preservation','Why this heritage matters','Keep the stories, knowledge, and appreciation alive.','Promoting Kapeng Barako means more than describing a bold cup. It also means recognizing farming knowledge, supporting accurate public information, documenting local narratives with permission, and encouraging informed appreciation for Liberica and the communities connected to it.'],
        ];
        foreach ($articles as $i=>$item) {
            [$category,$title,$summary,$body] = $item;
            Article::firstOrCreate(['slug'=>\Illuminate\Support\Str::slug($title)], [
                'title'=>$title,'category'=>$category,'summary'=>$summary,'body'=>$body,
                'is_published'=>true,'sort_order'=>$i+1,
            ]);
        }
        $references = [
            ['Local History — Lipa: Then and Now','City Government of Lipa','https://lipa.gov.ph/history/','Local coffee boom, elevation to Villa de Lipa, and later decline.'],
            ['Coffee Technoguide','Department of Agriculture — Agricultural Training Institute','https://ati2.da.gov.ph/ati-4b/content/sites/default/files/2022-11/Coffee%20Technoguide%20w%20page%20numbers.pdf','Government coffee guidance describing Liberica and Kapeng Barako.'],
            ['Liberica species profile','SICC','https://www.sicc.coffee/species/liberica/','Species characteristics and broader botanical context.'],
            ['Batangas to standardize Kapeng Barako production','Philippine News Agency','https://www.pna.gov.ph/index.php/articles/1234070','2024 report about local quality and heritage initiatives.'],
        ];
        foreach($references as [$title,$publisher,$url,$notes]) Reference::firstOrCreate(['url'=>$url],compact('title','publisher','notes')+['is_published'=>true]);
        $email=trim((string)env('ADMIN_EMAIL',''));
        $password=(string)env('ADMIN_PASSWORD','');
        if (filter_var($email,FILTER_VALIDATE_EMAIL) && strlen($password)>=12) {
            User::updateOrCreate(['email'=>$email],['name'=>'LIBÉRICA Administrator','password'=>Hash::make($password),'is_admin'=>true]);
            $this->command->info('Administrator created or updated.');
        } else {
            $this->command->warn('Admin not created: configure ADMIN_EMAIL and ADMIN_PASSWORD (12+ characters), then re-run db:seed.');
        }
    }
}
