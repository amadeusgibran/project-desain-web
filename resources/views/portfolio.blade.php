<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $profile['name'] }} | Portfolio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="site-shell">
        <header class="site-header">
            <a class="brand" href="{{ route('about') }}">{{ strtoupper($profile['name']) }}</a>

            <nav class="nav" aria-label="Navigasi utama">
                <a class="{{ $page === 'about' ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                <a class="{{ $page === 'portfolio' ? 'active' : '' }}" href="{{ route('portfolio') }}">Portfolio</a>
                <a class="{{ $page === 'contact' ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                <button class="ai-launch" type="button" data-ai-toggle aria-label="Buka AI assistant">
                    AI Assistant
                </button>
            </nav>
        </header>

        <aside class="ai-panel" data-ai-panel>
            <div class="ai-card-head">
                <div class="ai-badge">AI</div>
                <div>
                    <strong>PROFILE ASSISTANT</strong>
                    <small data-ai-source>Studio profile context</small>
                </div>
            </div>
            <div class="ai-body">
                <blockquote data-ai-summary>Tekan refresh untuk membaca insight profile.</blockquote>
                <p data-ai-suggestion>Insight singkat tentang arah visual dan positioning portfolio.</p>
                <button type="button" data-ai-refresh>Refresh Insight</button>
            </div>
        </aside>

        <main>
            @if ($page === 'about')
                <section class="hero container">
                    <div>
                        <h1 class="hero-title">I am a photographer &amp; <span>visual storyteller</span></h1>
                    </div>
                    <div class="character-stage" aria-label="Visual portrait interaktif">
                        <img src="{{ asset('images/about_me_3d_character.png') }}" alt="">
                        <canvas id="character-canvas"></canvas>
                        <div class="drag-note">DRAG TO EXPLORE</div>
                    </div>
                    <div class="hero-line"></div>
                </section>

                <section class="section-band">
                    @php
                        $featuredProject = $projects[0];
                    @endphp

                    <div class="container about-grid">
                        <p class="eyebrow">The Philosophy</p>
                        <h2 class="statement">Finding quiet emotion through light, texture, and composition.</h2>
                        <p class="copy">
                            Saya memotret momen, ruang, dan karakter dengan pendekatan editorial yang bersih.
                            Setiap frame diarahkan agar terasa tenang, jujur, dan punya narasi visual.
                        </p>
                        <p></p>
                        <p class="copy">
                            Karya saya berangkat dari observasi cahaya, ritme bentuk, dan detail kecil yang sering
                            lewat begitu saja di tengah keseharian.
                        </p>
                        <p class="copy">
                            Fokus saya: portrait, editorial, arsitektur, dokumentasi brand, dan visual campaign
                            yang terasa personal tanpa kehilangan presisi.
                        </p>
                    </div>

                    <div class="container featured-project">
                        <div class="featured-copy">
                            <span class="eyebrow">Latest Project</span>
                            <h2>{{ $featuredProject['title'] }}</h2>
                            <p>
                                Seri foto terbaru yang mengeksplorasi cahaya, ruang, dan gesture visual dengan
                                komposisi minimal serta tone editorial.
                            </p>
                            <a class="portfolio-button" href="{{ route('portfolio') }}">See Portfolio</a>
                        </div>

                        <article class="featured-card">
                            <div class="featured-media">
                                <img src="{{ asset('images/'.$featuredProject['image']) }}" alt="{{ $featuredProject['title'] }}">
                                <span class="pill">{{ $featuredProject['category'] }}</span>
                            </div>
                            <div class="featured-title-row">
                                <h3>{{ strtoupper($featuredProject['title']) }}</h3>
                                <a class="detail-link" href="{{ route('portfolio.detail', $featuredProject['slug']) }}">View Detail</a>
                            </div>
                        </article>
                    </div>
                </section>
            @elseif ($page === 'portfolio')
                <section class="portfolio-hero container">
                    <h1>Capturing the <em>silent</em> narratives between people, spaces, and light.</h1>
                    <p>Based in {{ $profile['location'] }}, creating portrait, editorial, architecture, and brand photography with a calm visual language.</p>
                </section>

                @php
                    $categories = collect($projects)->pluck('category')->unique()->values();
                @endphp

                <section class="work-filter container" aria-label="Filter portfolio">
                    <button class="filter-tab active" type="button" data-filter="all">All</button>
                    @foreach ($categories as $category)
                        <button class="filter-tab" type="button" data-filter="{{ $category }}">{{ $category }}</button>
                    @endforeach
                </section>

                <section class="work-grid container">
                    @foreach ($projects as $project)
                        <a class="work-card group" href="{{ route('portfolio.detail', $project['slug']) }}" data-category="{{ $project['category'] }}">
                            <div class="work-media">
                                <img src="{{ asset('images/'.$project['image']) }}" alt="{{ $project['title'] }}">
                                <div class="work-overlay">
                                    <span class="pill">{{ $project['category'] }}</span>
                                </div>
                            </div>
                            <div class="work-title-row">
                                <h2>{{ strtoupper($project['title']) }}</h2>
                                <span class="detail-link">View Detail</span>
                            </div>
                        </a>
                    @endforeach
                </section>
            @else
                <section class="contact-hero container">
                    <h1>Let's Connect</h1>
                    <p>I am currently accepting photo sessions, editorial assignments, and brand documentation. Share your brief, mood, location, or visual direction.</p>
                </section>

                <section class="contact-layout container">
                    <aside class="contact-sidebar">
                        <span class="eyebrow">Email</span>
                        <h2>{{ $profile['email'] }}</h2>

                        <span class="eyebrow">Social</span>
                        <div class="social-list">
                            <a href="#">LinkedIn</a>
                            <a href="#">Instagram</a>
                            <a href="#">Behance</a>
                        </div>

                        <div class="availability">
                            <span class="eyebrow">Availability</span>
                            <p>{{ $profile['availability'] }}</p>
                        </div>
                    </aside>

                    <form class="contact-form" action="#" method="post">
                        @csrf
                        <div class="field">
                            <label for="name">Name</label>
                            <input id="name" name="name" value="John Doe">
                        </div>
                        <div class="field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="john@example.com">
                        </div>
                        <div class="field full">
                            <label for="type">Inquiry Type</label>
                            <select id="type" name="type">
                                <option>Portrait Session</option>
                                <option>Editorial Photography</option>
                                <option>Brand Documentation</option>
                            </select>
                        </div>
                        <div class="field full">
                            <label for="message">Message</label>
                            <textarea id="message" name="message">Tell me about your project...</textarea>
                        </div>
                        <div class="send-row">
                            <button class="send-button" type="submit">SEND MESSAGE -></button>
                        </div>
                    </form>
                </section>
            @endif
        </main>
    </div>
</body>
</html>
