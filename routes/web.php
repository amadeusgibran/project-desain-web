<?php

use Composer\InstalledVersions;
use Illuminate\Support\Facades\Route;

$profile = [
    'name' => 'Gibran Studio',
    'role' => 'photographer & visual storyteller',
    'email' => 'hello@gibranstudio.dev',
    'location' => 'Bandung, Indonesia',
    'availability' => 'Menerima sesi portrait, editorial, produk, dan dokumentasi visual untuk brand maupun personal.',
];

$projects = [
    [
        'title' => 'Monolith Study',
        'slug' => 'monolith-study',
        'category' => 'Editorial',
        'image' => 'portfolio_photography_gallery.png',
        'description' => 'Monolith Study adalah seri fotografi editorial yang membaca bentuk arsitektur sebagai ruang diam. Frame dibuat dengan garis tegas, kontras lembut, dan tone monokrom untuk membangun atmosfer kontemplatif. Fokus seri ini adalah menangkap hubungan antara cahaya, material, dan skala manusia.',
        'client' => 'Monolith Residence',
        'year' => '2026',
        'tools' => ['Canon EOS R6', '35mm Lens', 'Lightroom', 'Photoshop'],
        'images' => [
            'portfolio_photography_gallery.png',
            'contact_details.png',
            'about_me_3d_character.png',
        ],
        'link' => 'https://example.com/monolith-study',
    ],
    [
        'title' => 'Void & Volume',
        'slug' => 'void-volume',
        'category' => 'Architecture',
        'image' => 'contact_details.png',
        'description' => 'Void & Volume adalah dokumentasi visual ruang interior dengan pendekatan tenang dan presisi. Setiap foto menekankan pertemuan antara bayangan, tekstur, dan volume ruangan. Seri ini dibuat untuk membantu studio desain menampilkan karakter ruang tanpa kehilangan nuansa naturalnya.',
        'client' => 'Volume House',
        'year' => '2026',
        'tools' => ['Sony A7 IV', '24-70mm Lens', 'Natural Light', 'Lightroom'],
        'images' => [
            'contact_details.png',
            'portfolio_photography_gallery.png',
            'about_me_3d_character.png',
        ],
        'link' => 'https://example.com/void-volume',
    ],
    [
        'title' => 'Helix Series',
        'slug' => 'helix-series',
        'category' => 'Portrait',
        'image' => 'about_me_3d_character.png',
        'description' => 'Helix Series adalah sesi portrait konseptual yang mengeksplorasi gestur, siluet, dan identitas personal. Pengambilan gambar diarahkan untuk terasa intim, minimal, dan kuat secara karakter. Hasilnya digunakan sebagai materi profil kreatif, editorial personal, dan visual campaign.',
        'client' => 'Personal Commission',
        'year' => '2026',
        'tools' => ['Studio Light', '85mm Lens', 'Color Grading', 'Retouching'],
        'images' => [
            'about_me_3d_character.png',
            'portfolio_photography_gallery.png',
            'contact_details.png',
        ],
        'link' => 'https://example.com/helix-series',
    ],
];

Route::get('/', fn () => view('portfolio', [
    'page' => 'about',
    'profile' => $profile,
    'projects' => $projects,
]))->name('about');

Route::get('/portfolio', fn () => view('portfolio', [
    'page' => 'portfolio',
    'profile' => $profile,
    'projects' => $projects,
]))->name('portfolio');

Route::get('/portfolio/{slug}', function (string $slug) use ($profile, $projects) {
    $index = collect($projects)->search(fn ($project) => $project['slug'] === $slug);

    abort_if($index === false, 404);

    return view('portfolio-detail', [
        'profile' => $profile,
        'project' => $projects[$index],
        'previousProject' => $index > 0 ? $projects[$index - 1] : null,
        'nextProject' => $index < count($projects) - 1 ? $projects[$index + 1] : null,
    ]);
})->name('portfolio.detail');

Route::get('/contact', fn () => view('portfolio', [
    'page' => 'contact',
    'profile' => $profile,
    'projects' => $projects,
]))->name('contact');

Route::get('/profile/ai-insight', function () use ($profile) {
    $boostInstalled = InstalledVersions::isInstalled('laravel/boost');

    return response()->json([
        'source' => $boostInstalled ? 'Studio profile context' : 'Local profile assistant',
        'summary' => "{$profile['name']} cocok diposisikan sebagai {$profile['role']} dengan pendekatan editorial, tenang, dan kuat secara komposisi.",
        'suggestion' => 'Tonjolkan seri foto terbaik di halaman pertama, lalu arahkan pengunjung ke koleksi portfolio dan kontak.',
    ]);
})->name('profile.ai');
