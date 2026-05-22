<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project['title'] }} | {{ $profile['name'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="site-shell">
        <header class="site-header">
            <a class="brand" href="{{ route('about') }}">{{ strtoupper($profile['name']) }}</a>

            <nav class="nav" aria-label="Navigasi utama">
                <a href="{{ route('about') }}">About</a>
                <a class="active" href="{{ route('portfolio') }}">Portfolio</a>
                <a href="{{ route('contact') }}">Contact</a>
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
            <section class="detail-hero container">
                <a class="back-link" href="{{ route('portfolio') }}">&larr; Back to portfolio</a>
                <div class="detail-hero-copy">
                    <span class="eyebrow">{{ $project['category'] }}</span>
                    <h1>{{ $project['title'] }}</h1>
                </div>
            </section>

            <section class="detail-hero-media">
                <img src="{{ asset('images/'.$project['image']) }}" alt="{{ $project['title'] }}">
            </section>

            <section class="detail-content container">
                <aside class="detail-meta" aria-label="Project information">
                    <div>
                        <span class="meta-label">Client</span>
                        <strong>{{ $project['client'] }}</strong>
                    </div>
                    <div>
                        <span class="meta-label">Year</span>
                        <strong>{{ $project['year'] }}</strong>
                    </div>
                    <div>
                        <span class="meta-label">Production</span>
                        <div class="tool-list">
                            @foreach ($project['tools'] as $tool)
                                <span class="pill">{{ $tool }}</span>
                            @endforeach
                        </div>
                    </div>
                </aside>

                <article class="detail-description">
                    <span class="eyebrow">Series Story</span>
                    <p>{{ $project['description'] }}</p>
                    <p>
                        Arah visual dibuat agar mood, subjek, dan detail tetap menjadi pusat perhatian.
                        Pemilihan frame, warna, dan ritme editing menjaga cerita terasa utuh dari foto
                        pertama sampai akhir seri.
                    </p>
                </article>
            </section>

            <section class="detail-gallery container" aria-label="Project gallery">
                @foreach ($project['images'] as $image)
                    <figure>
                        <img src="{{ asset('images/'.$image) }}" alt="{{ $project['title'] }} gallery image">
                    </figure>
                @endforeach
            </section>

            <section class="project-nav container" aria-label="Project navigation">
                @if ($previousProject)
                    <a class="project-nav-card" href="{{ route('portfolio.detail', $previousProject['slug']) }}">
                        <span class="meta-label">&larr; Previous</span>
                        <strong>{{ $previousProject['title'] }}</strong>
                    </a>
                @else
                    <span></span>
                @endif

                @if ($nextProject)
                    <a class="project-nav-card next" href="{{ route('portfolio.detail', $nextProject['slug']) }}">
                        <span class="meta-label">Next &rarr;</span>
                        <strong>{{ $nextProject['title'] }}</strong>
                    </a>
                @endif
            </section>
        </main>
    </div>
</body>
</html>
