<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $profile['name'] }} | Portfolio</title>
    <script>
        window.avatarModelUrl = "{{ asset('3d/avatar.glb') }}";
    </script>
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
                    <strong>PORTFOLIO CHAT</strong>
                    <small data-ai-source>Profile and project context</small>
                </div>
            </div>
            <div class="ai-body" data-ai-body>
                <div class="ai-messages" data-ai-messages aria-live="polite"></div>
                <form class="ai-chat-form" data-ai-form>
                    <label class="sr-only" for="ai-message">Tulis pertanyaan untuk portfolio assistant</label>
                    <textarea id="ai-message" data-ai-input rows="2" maxlength="1000" placeholder="Tanya soal portfolio, layanan, atau cara booking..." required></textarea>
                    <button type="submit" data-ai-send>Send</button>
                </form>
            </div>
        </aside>

        <main>
            @if ($page === 'about')
                <section class="hero container">
                    <div>
                        <h1 class="hero-title">I am a photographer &amp; <span>graphic desainer</span></h1>
                    </div>
                    <div class="character-stage" aria-label="Visual portrait interaktif">
                        <img src="{{ ! empty($profile['avatar']) ? Storage::url($profile['avatar']) : asset('images/about_me_3d_character.png') }}" alt="">
                        <canvas id="character-canvas"></canvas>
                        <div class="drag-note">DRAG TO EXPLORE</div>
                    </div>
                    <div class="hero-line"></div>
                </section>

                <section class="section-band">
                    @php
                        $featuredProject = $projects->first();
                    @endphp

                    <div class="container about-grid">
                        <p class="eyebrow">The Philosophy</p>
                        <h2 class="statement">Finding quiet emotion through light, texture, and composition.</h2>
                        <p class="copy">
                            {{ $profile['bio'] }}
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

                    @if ($featuredProject)
                        <div class="container featured-project">
                            <div class="featured-copy">
                                <span class="eyebrow">Latest Project</span>
                                <h2>{{ $featuredProject->title }}</h2>
                                <p>
                                    Seri foto terbaru yang mengeksplorasi cahaya, ruang, dan gesture visual dengan
                                    komposisi minimal serta tone editorial.
                                </p>
                                <a class="portfolio-button" href="{{ route('portfolio') }}">See Portfolio</a>
                            </div>

                            <article class="featured-card">
                                <div class="featured-media">
                                    <img src="{{ $featuredProject->cover_image_url }}" alt="{{ $featuredProject->title }}">
                                    <span class="pill">{{ $featuredProject->category }}</span>
                                </div>
                                <div class="featured-title-row">
                                    <h3>{{ strtoupper($featuredProject->title) }}</h3>
                                    <a class="detail-link" href="{{ route('portfolio.detail', $featuredProject->slug) }}">View Detail</a>
                                </div>
                            </article>
                        </div>
                    @endif
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
                        <a class="work-card group" href="{{ route('portfolio.detail', $project->slug) }}" data-category="{{ $project->category }}">
                            <div class="work-media">
                                <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}">
                                <div class="work-overlay">
                                    <span class="pill">{{ $project->category }}</span>
                                </div>
                            </div>
                            <div class="work-title-row">
                                <h2>{{ strtoupper($project->title) }}</h2>
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
                            <a href="{{ $profile['social_linkedin'] ?: '#' }}">LinkedIn</a>
                            <a href="{{ $profile['social_instagram'] ?: '#' }}">Instagram</a>
                            <a href="{{ $profile['social_behance'] ?: '#' }}">Behance</a>
                        </div>

                        <div class="availability">
                            <span class="eyebrow">Availability</span>
                            <p>{{ $profile['availability'] }}</p>
                        </div>
                    </aside>

                    @if (session('contact_status'))
                        <div class="contact-success">
                            {{ session('contact_status') }}
                        </div>
                    @endif

                    <form class="contact-form" action="{{ route('contact.store') }}" method="post">
                        @csrf
                        <div class="honeypot-field" aria-hidden="true">
                            <label for="website">Website</label>
                            <input id="website" name="website" tabindex="-1" autocomplete="off" value="{{ old('website') }}">
                        </div>
                        <div class="field">
                            <label for="name">Name</label>
                            <input id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')<small>{{ $message }}</small>@enderror
                        </div>
                        <div class="field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                            @error('email')<small>{{ $message }}</small>@enderror
                        </div>
                        <div class="field full">
                            <label for="subject">Inquiry Type</label>
                            <select id="subject" name="subject" required>
                                <option value="">Select inquiry</option>
                                @foreach (['Portrait Session', 'Editorial Photography', 'Brand Documentation'] as $subject)
                                    <option value="{{ $subject }}" @selected(old('subject') === $subject)>{{ $subject }}</option>
                                @endforeach
                            </select>
                            @error('subject')<small>{{ $message }}</small>@enderror
                        </div>
                        <div class="field full">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required>{{ old('message') }}</textarea>
                            @error('message')<small>{{ $message }}</small>@enderror
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
