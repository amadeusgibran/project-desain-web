<?php

use Composer\InstalledVersions;
use Illuminate\Support\Facades\Route;

$profile = [
    'name' => 'Gibran Studio',
    'role' => 'creative developer & visual storyteller',
    'email' => 'hello@gibranstudio.dev',
    'location' => 'Bandung, Indonesia',
    'availability' => 'Menerima project web, portfolio, dan eksperimen visual berbasis Laravel.',
];

$projects = [
    [
        'title' => 'Monolith Study',
        'category' => 'Branding',
        'image' => 'portfolio_photography_gallery.png',
        'wide' => true,
    ],
    [
        'title' => 'Void & Volume',
        'category' => 'UI Direction',
        'image' => 'contact_details.png',
        'wide' => false,
    ],
    [
        'title' => 'Helix Series',
        'category' => 'Photography',
        'image' => 'about_me_3d_character.png',
        'wide' => false,
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

Route::get('/contact', fn () => view('portfolio', [
    'page' => 'contact',
    'profile' => $profile,
    'projects' => $projects,
]))->name('contact');

Route::get('/profile/ai-insight', function () use ($profile) {
    $boostInstalled = InstalledVersions::isInstalled('laravel/boost');

    return response()->json([
        'source' => $boostInstalled ? 'Laravel Boost AI context' : 'Local profile assistant',
        'summary' => "{$profile['name']} cocok diposisikan sebagai {$profile['role']} yang menggabungkan Blade, Three.js, dan visual 3D minimalis.",
        'suggestion' => 'Tonjolkan project interaktif di halaman pertama, lalu arahkan pengunjung ke portfolio dan kontak.',
    ]);
})->name('profile.ai');
