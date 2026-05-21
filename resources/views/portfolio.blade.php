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
                    <small data-ai-source>Laravel Boost context</small>
                </div>
            </div>
            <div class="ai-body">
                <blockquote data-ai-summary>Tekan refresh untuk membaca insight profile.</blockquote>
                <p data-ai-suggestion>AI resmi Laravel dipasang lewat Boost. Laravel AI SDK runtime butuh PHP 8.3.</p>
                <button type="button" data-ai-refresh>Refresh Insight</button>
            </div>
        </aside>

        <main>
            @if ($page === 'about')
                <section class="hero container">
                    <div>
                        <h1 class="hero-title">I am a creative developer &amp; <span>3D maker</span></h1>
                    </div>
                    <div class="character-stage" aria-label="Karakter 3D yang bisa dirotasi">
                        <img src="{{ asset('images/about_me_3d_character.png') }}" alt="">
                        <canvas id="character-canvas"></canvas>
                        <div class="drag-note">DRAG TO ROTATE</div>
                    </div>
                    <div class="hero-line"></div>
                </section>

                <section class="section-band">
                    <div class="container about-grid">
                        <p class="eyebrow">The Philosophy</p>
                        <h2 class="statement">Balancing technical precision with visceral visual storytelling.</h2>
                        <p class="copy">
                            Saya membangun interface Laravel yang terasa rapi, cepat, dan punya karakter visual.
                            Kode, tipografi, dan gerak 3D dipakai sebagai satu bahasa desain.
                        </p>
                        <p></p>
                        <p class="copy">
                            Portfolio ini memakai Blade, Vite, Three.js, dan Laravel Boost sebagai konteks AI resmi
                            untuk membantu profil terasa lebih adaptif.
                        </p>
                        <p class="copy">
                            Fokus saya: website personal, landing page editorial, visual interaktif, dan sistem web
                            yang tetap mudah dikembangkan.
                        </p>
                    </div>

                    <div class="container feature-grid">
                        <div class="feature-image">
                            <img src="{{ asset('images/portfolio_photography_gallery.png') }}" alt="Portfolio visual generatif">
                            <span class="image-chip">Photography</span>
                        </div>
                        <div class="side-stack">
                            <article class="mini-project">
                                <span class="eyebrow">01. Interactive</span>
                                <h3>Generative Systems</h3>
                            </article>
                            <article class="mini-project dark">
                                <span class="eyebrow">02. Architecture</span>
                                <h3>Digital Structures</h3>
                            </article>
                        </div>
                    </div>
                </section>
            @elseif ($page === 'portfolio')
                <section class="portfolio-hero container">
                    <h1>Capturing the <em>silent</em> narratives of contemporary architecture.</h1>
                    <p>Based in {{ $profile['location'] }}, exploring the intersection of code, form, and visual geometry through a minimalist lens.</p>
                </section>

                <section class="work-grid container">
                    @foreach ($projects as $index => $project)
                        <article class="work-card {{ $project['wide'] ? 'wide' : '' }} {{ $index === 1 ? 'tall' : '' }}">
                            <div class="work-media">
                                <img src="{{ asset('images/'.$project['image']) }}" alt="{{ $project['title'] }}">
                            </div>
                            <div class="work-title-row">
                                <h2>{{ strtoupper($project['title']) }}</h2>
                                <span class="pill">{{ $project['category'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </section>
            @else
                <section class="contact-hero container">
                    <h1>Let's Connect</h1>
                    <p>I am currently accepting new projects and creative collaborations. Whether you have a brief or just want to start a conversation, I would love to hear from you.</p>
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
                                <option>Brand Identity</option>
                                <option>Laravel Portfolio</option>
                                <option>Interactive 3D Website</option>
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
