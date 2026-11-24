@extends('layouts.landing')

@section('content')



{{-- 01 · HERO — editable in Administration > Website Settings --}}

@php

    $heroImage = $settings['hero_image'] ?? null;

    $heroUrl = $heroImage ? asset('storage/'.$heroImage) : asset('assets/pexels-claire-dao-2150575386-34612102.jpg');

@endphp

<section class="hero" aria-labelledby="hero-title">

    <div class="hero-copy">

        <div class="hero-ribbon" aria-hidden="true"></div>

        <div class="eyebrow"><i></i>{{ $settings['hero_kicker'] ?? 'A HERITAGE WORTH KEEPING' }}</div>

        <h1 id="hero-title">{{ $settings['hero_title'] ?? 'Bold by nature.' }}<em>{{ $settings['hero_accent'] ?? 'Rooted in Batangas.' }}</em></h1>

        <p>{{ $settings['hero_description'] ?? 'Meet Kapeng Barako: a distinctive coffee with a story worth sharing.' }}</p>

        <div class="hero-actions"><a class="btn primary" href="#heritage">DISCOVER ITS HERITAGE <span>↗</span></a><a class="text-link" href="#discover">Meet Liberica ↗</a></div>

        <div class="hero-bottom"><span>01 <i></i> 08</span><a href="#heritage">SCROLL TO EXPLORE ↓</a></div>

    </div>

    <div class="hero-photo" style="--hero-photo: url('{{ $heroUrl }}')">

        <div class="hero-stamp" aria-hidden="true"><span><img src="/assets/coffee-plant.png" alt="" class="gallery-waiting-image" width="72" height="72" loading="lazy"></span><small>BOLD BY NATURE<br>ROOTED IN HERITAGE</small></div>

        <div class="vertical-label">A STORY GROWN IN BATANGAS</div>

        <div class="hero-photo-footer"><span>THE KAPENG BARAKO EXPERIENCE</span><span>EST. IN HERITAGE</span></div>

    </div>

</section>

<div class="ticker" aria-label="Coffee heritage values"><div>LIBERICA COFFEE <b aria-hidden="true"></b> BATANGAS HERITAGE <b aria-hidden="true"></b> CULTIVATION <b aria-hidden="true"></b> COMMUNITY <b aria-hidden="true"></b> LIBERICA COFFEE <b aria-hidden="true"></b> BATANGAS HERITAGE <b aria-hidden="true"></b> CULTIVATION <b aria-hidden="true"></b> COMMUNITY <b aria-hidden="true"></b></div></div>



{{-- 02 · HERITAGE --}}

<section id="heritage" class="section-shell heritage-section">

    <div class="section-number">01 / THE ROOTS</div>

    <div class="heritage-layout">

        <div><div class="eyebrow gold">A CITY SHAPED BY COFFEE</div><h2>From Lipa,<br><em>with history.</em></h2></div>

        <div class="heritage-summary"><p>In the nineteenth century, Lipa became one of the Philippines' prominent coffee-trading centers. The town's coffee prosperity contributed to its elevation to <strong>Villa de Lipa in 1887</strong>.</p><p>Later plant disease devastated its plantations—but the memory of coffee endured in Batangas culture.</p><a class="text-link" href="#references">EXPLORE THE HISTORICAL SOURCES ↗</a></div>

    </div>

    <div class="timeline"><div><strong>1800s</strong><span>Coffee cultivation and trade expand across Lipa.</span></div><div><strong>1887</strong><span>Lipa receives the title “Villa de Lipa.”</span></div><div><strong>Today</strong><span>Kapeng Barako remains a meaningful regional identity.</span></div></div>

</section>



{{-- 03 · WHAT IS LIBERICA --}}

<section id="discover" class="discover-section">

    <div class="discover-visual" role="img" aria-label="Close-up of roasted coffee beans"></div>

    <div class="discover-content"><div class="section-number">02 / GET TO KNOW THE BEAN</div><div class="eyebrow gold">MORE THAN JUST STRONG COFFEE</div><h2>Meet the <em>remarkable Liberica.</em></h2><p><i>Coffea liberica</i> is one of the world's coffee species, known for its substantial trees, comparatively large leaves and beans, and an expressive cup character.</p><p>In the Philippines it is closely associated with <strong>Kapeng Barako</strong>. Roasting, cultivation, processing, and brewing shape each cup—so flavor should never be treated as a single fixed promise.</p><div class="fact-row"><div><strong>01</strong><span>Distinct botanical species</span></div><div><strong>02</strong><span>Linked to Barako tradition</span></div><div><strong>03</strong><span>A complex coffee identity</span></div></div>
        @if($featuredEntry)
            <a class="text-link" href="{{ route('encyclopedia.entry', [$featuredEntry->topic->slug, $featuredEntry->slug]) }}">Read about {{ $featuredEntry->title }} <span aria-hidden="true">&#8594;</span></a>
        @endif
    </div>

</section>



{{-- 04 · HERITAGE COLLECTION — independent learning notes, not duplicate section links --}}
<section id="heritage-collection" class="section-shell heritage-collection" aria-labelledby="collection-title">
    <div class="collection-intro">
        <div>
            <div class="section-number">03 / THE HERITAGE COLLECTION</div>
            <div class="eyebrow gold">FOUR STORIES BEYOND THE BASICS</div>
            <h2 id="collection-title">A deeper look at <em>Barako.</em></h2>
        </div>
        <p>Explore the details behind Lipa’s coffee history, careful processing, preservation, and traditional preparation. Select “Read the story” on any card to discover something new.</p>
    </div>

    <div class="heritage-collection-grid">
        <article class="heritage-collection-card">
            <div class="heritage-collection-stage">
                <div class="heritage-collection-poster tilt-left">
                    <img src="{{ asset('assets/pexels-torrans-mayorquin-505468205-20190062.jpg') }}" alt="Coffee beans reflecting the history of Batangas coffee" loading="lazy">
                    <span class="heritage-collection-overlay"><small>01 / THE TURNING POINT</small><strong>1889</strong><em>THE COFFEE CRISIS</em></span>
                </div>
            </div>
            <div class="heritage-collection-info">
                <span class="collection-category">A CHAPTER IN HISTORY</span>
                <h3>When the boom faded</h3>
                <p>Why the decline of Lipa’s coffee trade has more than one explanation.</p>
                <details class="collection-story"><summary>READ THE STORY <span aria-hidden="true">+</span></summary>
                    <p>By the late nineteenth century, disease and pests struck Batangas coffee estates. Historical studies also examine long-term pressures such as land use, monoculture, investment, and changing competition. The story of the 1889 crisis is therefore more complex than a single sudden event.</p>
                    <a href="https://digital.library.adelaide.edu.au/server/api/core/bitstreams/f1985e71-2e97-4cd3-9c41-a8cc159b74ac/content" target="_blank" rel="noopener noreferrer">READ HISTORICAL RESEARCH ↗</a>
                </details>
            </div>
        </article>

        <article class="heritage-collection-card">
            <div class="heritage-collection-stage stage-olive">
                <div class="heritage-collection-poster tilt-right">
                    <img src="{{ asset('assets/pexels-michael-burrows-7125451.jpg') }}" alt="Hand-harvested coffee cherries before processing" loading="lazy">
                    <span class="heritage-collection-overlay"><small>02 / AFTER THE HARVEST</small><strong>WET &amp;<br>DRY</strong><em>PROCESSING MATTERS</em></span>
                </div>
            </div>
            <div class="heritage-collection-info">
                <span class="collection-category">CRAFT &amp; TECHNIQUE</span>
                <h3>Two paths from cherry</h3>
                <p>How fruit removal and drying shape the green coffee bean.</p>
                <details class="collection-story"><summary>READ THE STORY <span aria-hidden="true">+</span></summary>
                    <p>In broadly described wet processing, the fruit is removed before the coffee is fully dried; dry processing dries the cherry with its fruit intact before hulling. Each approach requires care and can influence the resulting cup. Batangas Best describes using wet and dry stages in its own production process.</p>
                    <a href="https://batangasbest.com/our-coffee" target="_blank" rel="noopener noreferrer">READ THE PRODUCER'S ACCOUNT ↗</a>
                </details>
            </div>
        </article>

        <article class="heritage-collection-card">
            <div class="heritage-collection-stage stage-earth">
                <div class="heritage-collection-poster tilt-left">
                    <img src="{{ asset('assets/pexels-anderson-martinsz-34634578.jpg') }}" alt="Coffee handling and processing representing quality preservation" loading="lazy">
                    <span class="heritage-collection-overlay"><small>03 / KEEPING QUALITY</small><strong>DRY &amp;<br>STORE</strong><em>BEFORE THE ROAST</em></span>
                </div>
            </div>
            <div class="heritage-collection-info">
                <span class="collection-category">POST-HARVEST CARE</span>
                <h3>Protecting the harvest</h3>
                <p>Why careful drying and storage matter long before roasting.</p>
                <details class="collection-story"><summary>READ THE STORY <span aria-hidden="true">+</span></summary>
                    <p>Freshly processed coffee must be dried appropriately and protected from excess moisture during storage. Poor handling can reduce quality before the beans ever reach a roaster. The Batangas Best producer account identifies drying and secure storage as distinct steps in its process.</p>
                    <a href="https://batangasbest.com/our-coffee" target="_blank" rel="noopener noreferrer">EXPLORE THE SOURCE ↗</a>
                </details>
            </div>
        </article>

        <article class="heritage-collection-card">
            <div class="heritage-collection-stage stage-bronze">
                <div class="heritage-collection-poster tilt-right">
                    <img src="{{ asset('assets/pexels-thomas-plets-1139798-2434159.jpg') }}" alt="Hot water poured over ground coffee during brewing" loading="lazy">
                    <span class="heritage-collection-overlay"><small>04 / IN THE CUP</small><strong>BREW &amp;<br>SHARE</strong><em>A FAMILY TRADITION</em></span>
                </div>
            </div>
            <div class="heritage-collection-info">
                <span class="collection-category">BREWING KNOW-HOW</span>
                <h3>The resting moment</h3>
                <p>A look at steeping, resting, and serving coffee with intention.</p>
                <details class="collection-story"><summary>READ THE STORY <span aria-hidden="true">+</span></summary>
                    <p>Traditional preparations may let ground coffee steep in hot water and settle before it is served. Batangas Best shares one family-specific method that emphasizes a resting period and a final pour. It is an example of living practice, not the only authentic way to make Barako.</p>
                    <a href="https://batangasbest.com/how-to-brew" target="_blank" rel="noopener noreferrer">EXPLORE THE FAMILY METHOD ↗</a>
                </details>
            </div>
        </article>
    </div>
    <p class="collection-source-note">Learning notes are independently written and informed by historical scholarship and the <a href="https://batangasbest.com/" target="_blank" rel="noopener noreferrer">Batangas Best</a> producer website. Images are illustrative; no product endorsement is implied.</p>
</section>


{{-- 04 · FROM BEAN TO CUP --}}

<section id="journey" class="section-shell journey-section">

    <div class="section-number">04 / THE JOURNEY</div><div class="section-heading"><div><div class="eyebrow gold">CULTIVATED WITH CARE</div><h2>From tree to <em>tradition.</em></h2></div><p>Behind every cup is a chain of agricultural and craft decisions. Explore the stages that shape the coffee experience.</p></div>

    @if($journeySteps->isNotEmpty())
        <div class="journey-carousel" data-journey-carousel aria-roledescription="carousel" aria-label="Bean to Cup steps">
            <div class="journey-carousel-window" tabindex="0" aria-label="Use arrow keys to browse Bean to Cup steps">
                <div class="journey-carousel-track">
                    @foreach($journeySteps as $step)
                        @php($journeyMediaUrl = str_starts_with($step->media_path, 'assets/') ? asset($step->media_path) : asset('storage/'.$step->media_path))
                        <article class="journey-slide" aria-roledescription="slide" aria-label="Step {{ $loop->iteration }} of {{ $journeySteps->count() }}" aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                            <div class="journey-slide-media">
                                @if($step->media_type === 'video')
                                    <video controls playsinline preload="metadata" aria-label="{{ $step->title }} process video"><source src="{{ $journeyMediaUrl }}"></video>
                                @else
                                    <img src="{{ $journeyMediaUrl }}" alt="{{ $step->media_alt ?: $step->title }}" loading="lazy">
                                @endif
                            </div>
                            <div class="journey-slide-copy">
                                <span class="journey-step-count">STEP {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} <i>/ {{ str_pad($journeySteps->count(), 2, '0', STR_PAD_LEFT) }}</i></span>
                                <span class="eyebrow gold">FROM TREE TO CUP</span>
                                <h3>{{ $step->title }}</h3>
                                <p>{{ $step->description }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
            <div class="journey-carousel-controls">
                <button type="button" class="journey-carousel-arrow" data-journey-prev aria-label="Previous step">&#8592;</button>
                <div class="journey-carousel-dots" role="group" aria-label="Choose a Bean to Cup step">
                    @foreach($journeySteps as $step)
                        <button type="button" data-journey-dot="{{ $loop->index }}" aria-label="Go to step {{ $loop->iteration }}" aria-current="{{ $loop->first ? 'step' : 'false' }}"></button>
                    @endforeach
                </div>
                <span class="journey-carousel-status" aria-live="polite"><span data-journey-current>01</span> / {{ str_pad($journeySteps->count(), 2, '0', STR_PAD_LEFT) }}</span>
                <button type="button" class="journey-carousel-arrow" data-journey-next aria-label="Next step">&#8594;</button>
            </div>
        </div>
    @endif

</section>



{{-- 05 · COFFEE CULTURE --}}

<section id="culture" class="culture-section"><div class="culture-image" aria-hidden="true"></div><div class="culture-copy"><div class="section-number">05 / CULTURE & CONNECTION</div><div class="eyebrow gold">THE BEAUTY OF SLOWING DOWN</div><h2>Make space<br>for the <em>moment.</em></h2><p>From quiet mornings to shared conversations, Kapeng Barako has a place in the everyday stories of Batangas. Its meaning is held not just in the cup, but in the people who keep its traditions alive.</p><a class="btn outline" href="#stories">EXPLORE THE STORIES ↗</a><div class="decor" aria-hidden="true"></div></div></section>



{{-- 06 · ADMIN-MANAGED EDUCATIONAL ARTICLES --}}

<section id="stories" class="section-shell stories-section"><div class="section-number">06 / KNOWLEDGE & PRESERVATION</div><div class="section-heading"><div><div class="eyebrow gold">DISCOVER • LEARN • APPRECIATE</div><h2>Stories worth <em>sharing.</em></h2></div><p>Educational introductions to Liberica, Lipa's heritage, farming, and regional coffee culture.</p></div>

    <div class="story-grid">

        @forelse($articles as $article)

            <article class="story-card" id="story-{{ $article->slug }}">

                <div class="story-card-top"><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ strtoupper($article->category) }}</span><span class="story-mark" aria-hidden="true"></span></div>

                @if($article->image_path)<img src="{{ asset('storage/'.$article->image_path) }}" alt="{{ $article->title }}" loading="lazy">@endif

                <h3>{{ $article->title }}</h3><p>{{ $article->summary }}</p>

                <details><summary>READ MORE <span aria-hidden="true">↗</span></summary><div class="story-body">{!! nl2br(e($article->body)) !!}</div></details>

            </article>

        @empty

            <p class="empty-state">Educational articles will be published here soon.</p>

        @endforelse

    </div>

</section>



{{-- 07 · PHOTOGRAPHY GALLERY --}}

<section id="gallery" class="section-shell gallery-section"><div class="section-number">07 / VISUAL JOURNAL</div><div class="section-heading"><div><div class="eyebrow gold">THE BEAUTY OF THE BEAN</div><h2>Frames of <em>heritage.</em></h2></div><p>Real moments deserve real photographs. Gallery entries can be uploaded and credited by the project team.</p></div>

    @if($gallery->isEmpty())

        <div class="gallery-placeholder">

            <img

                src="{{ asset('assets/waiting-area.png') }}"

                alt=""

                class="gallery-waiting-image"

                width="72"

                height="72"

                loading="lazy"

                aria-hidden="true"

            >

            <h3>Our visual journal is taking shape.</h3>

            <p>Authentic coffee farms, harvests, and local cultural photographs will appear here with proper credits.</p>

        </div>

    @else

        <div class="gallery-grid">@foreach($gallery as $photo)<figure><img src="{{ asset('storage/'.$photo->image_path) }}" alt="{{ $photo->title }}" loading="lazy"><figcaption><strong>{{ $photo->title }}</strong><span>{{ $photo->caption }}</span>@if($photo->image_credit)<small>Photo: {{ $photo->image_credit }}</small>@endif</figcaption></figure>@endforeach</div>

    @endif

</section>



{{-- 08 · FIND THE ROOTS — verified learning locations and approximate map markers --}}
<section id="farm-map" class="section-shell farm-map-section" aria-labelledby="farm-map-title">
    <div class="farm-map-header">
        <div class="farm-map-heading">
            <div class="section-number">08 / FIND THE ROOTS</div>
            <div class="eyebrow gold">BATANGAS ON THE MAP</div>
            <h2 id="farm-map-title">Where Barako <em>takes root.</em></h2>
        </div>
        <div class="farm-map-intro">
            <p>Discover documented Liberica cultivation and educational sites across Batangas, with context drawn from research and local initiatives.</p>
            <span class="farm-map-guide-tag">AN EDUCATIONAL LOCATION GUIDE · NOT A TOUR BOOKING SERVICE</span>
        </div>
    </div>

    <div class="farm-map-layout">
        <div class="farm-map-panel">
            <div class="farm-map-panel-head">
                <span><span class="farm-map-status-dot" aria-hidden="true"></span> BATANGAS · PHILIPPINES</span>
                <span>02 DOCUMENTED LOCATIONS</span>
            </div>
            <div class="farm-map-interactive">
                <div id="liberica-farm-map" class="farm-map-canvas" role="region" aria-label="Interactive map of documented Barako cultivation areas in Batangas"></div>
                <div id="farm-map-fallback" class="farm-map-fallback" hidden>Interactive map unavailable. Location information and external map links remain available in the adjacent cards.</div>
            </div>
            <div class="farm-map-attribution">Pins show approximate barangay areas, <strong>not farm entrances</strong>. Map data © OpenStreetMap contributors.</div>
        </div>

        <div class="farm-location-list" aria-label="Documented coffee farms and educational sites">
            <article class="farm-location-card">
                <div class="farm-location-card-top">
                    <span class="farm-location-number">01 / LIPA CITY</span>
                    <span class="farm-location-type">CULTIVATION & NURSERY</span>
                </div>
                <h3>Katy’s Farm</h3>
                <p class="farm-location-place">Tipakan, Lipa City, Batangas</p>
                <p class="farm-location-description">Liberica cultivation and nursery activity documented in research from the University of the Philippines Los Baños.</p>
                <p class="farm-location-caution">Area marker only. Visitor access has not been confirmed.</p>
                <div class="farm-location-links">
                    <a target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/search/?api=1&amp;query=Katy%27s%20Farm%20Tipakan%20Lipa%20Batangas">VIEW LOCATION <span aria-hidden="true">↗</span></a>
                    <a target="_blank" rel="noopener noreferrer" href="https://www.ukdr.uplb.edu.ph/etd-undergrad/5345/">RESEARCH SOURCE <span aria-hidden="true">↗</span></a>
                </div>
            </article>

            <article class="farm-location-card">
                <div class="farm-location-card-top">
                    <span class="farm-location-number">02 / ROSARIO</span>
                    <span class="farm-location-type">RESEARCH & DEMONSTRATION</span>
                </div>
                <h3>BARAKO Smart Demo Farm</h3>
                <p class="farm-location-place">Masaya, Rosario, Batangas</p>
                <p class="farm-location-description">Batangas State University's BARAKO initiative highlights agricultural demonstration and Liberica-related farm technology.</p>
                <p class="farm-location-caution">Area marker only. Not confirmed as a public attraction.</p>
                <div class="farm-location-links">
                    <a target="_blank" rel="noopener noreferrer" href="https://www.google.com/maps/search/?api=1&amp;query=BARAKO%20PROJECT%20SMART%20DEMO%20FARM%20Masaya%20Rosario%20Batangas">VIEW LOCATION <span aria-hidden="true">↗</span></a>
                    <a target="_blank" rel="noopener noreferrer" href="https://sustainability.batstate-u.edu.ph/wp-content/uploads/2022/11/Barako-Progress-Report.pdf">PROJECT SOURCE <span aria-hidden="true">↗</span></a>
                </div>
            </article>
        </div>
    </div>
    <p class="farm-map-note"><strong>Location note:</strong> These markers are for educational orientation and should not be treated as turn-by-turn directions. Confirm permission before planning any farm visit.</p>
</section>


{{-- 08 · SOURCES & CONTACT --}}

<section id="references" class="section-shell references-section"><div class="section-number">09 / VERIFIED INFORMATION</div><div class="section-heading"><div><div class="eyebrow gold">LEARN WITH TRUSTWORTHY SOURCES</div><h2>Explore the <em>references.</em></h2></div><p>Our historical and coffee-science content is a starting point for discovery. These sources help visitors explore further.</p></div><div class="reference-list">@forelse($references as $reference)<a href="{{ $reference->url }}" target="_blank" rel="noopener noreferrer"><span>{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span><div><strong>{{ $reference->title }}</strong><small>{{ $reference->publisher }}</small></div><span aria-hidden="true">↗</span></a>@empty<p>References will be added soon.</p>@endforelse</div></section>

<section id="contact" class="section-shell contact-section"><div class="contact-intro"><div class="eyebrow gold">LET'S KEEP THE STORY ALIVE</div><h2>A shared culture.<br><em>A living heritage.</em></h2><p>Have a question, local story, educational suggestion, or possible collaboration? Send us a message. This is an informational platform, not a retail website.</p></div><div class="contact-form"><h3>Send a message</h3>@if(session('success'))<div class="success" role="status">{{ session('success') }}</div>@endif @if($errors->any())<div class="error" role="alert">{{ $errors->first() }}</div>@endif<form action="{{ route('contact.store') }}" method="POST">@csrf<label for="visitor-name">YOUR NAME</label><input id="visitor-name" name="name" value="{{ old('name') }}" maxlength="120" required autocomplete="name"><label for="visitor-email">EMAIL ADDRESS</label><input id="visitor-email" type="email" name="email" value="{{ old('email') }}" maxlength="190" required autocomplete="email"><label for="visitor-message">MESSAGE</label><textarea id="visitor-message" name="message" minlength="10" maxlength="4000" required placeholder="What would you like to share?">{{ old('message') }}</textarea><div class="honeypot" aria-hidden="true"><label>Leave empty <input name="website" tabindex="-1" autocomplete="off"></label></div><button type="submit" class="btn primary">SEND MESSAGE ↗</button><p class="privacy-note">Your contact details are used only to respond to this message. Do not submit sensitive personal information.</p></form></div></section>

@endsection
