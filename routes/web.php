<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProjectController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

$profile = [
    'name' => 'Gibran Studio',
    'role' => 'photographer & visual storyteller',
    'email' => 'hello@gibranstudio.dev',
    'location' => 'Bandung, Indonesia',
    'availability' => 'Menerima sesi portrait, editorial, produk, dan dokumentasi visual untuk brand maupun personal.',
];

Route::get('/', function () use ($profile) {
    return view('portfolio', [
        'page' => 'about',
        'profile' => $profile,
        'projects' => Project::published()->orderBy('order')->get(),
    ]);
})->name('about');

Route::get('/portfolio', function () use ($profile) {
    return view('portfolio', [
        'page' => 'portfolio',
        'profile' => $profile,
        'projects' => Project::published()->orderBy('order')->get(),
    ]);
})->name('portfolio');

Route::get('/portfolio/{slug}', function (string $slug) use ($profile) {
    $projects = Project::published()->orderBy('order')->get();
    $index = $projects->search(fn (Project $project) => $project->slug === $slug);

    abort_if($index === false, 404);

    return view('portfolio-detail', [
        'profile' => $profile,
        'project' => $projects[$index],
        'previousProject' => $index > 0 ? $projects[$index - 1] : null,
        'nextProject' => $index < $projects->count() - 1 ? $projects[$index + 1] : null,
    ]);
})->name('portfolio.detail');

Route::get('/contact', fn () => view('portfolio', [
    'page' => 'contact',
    'profile' => $profile,
    'projects' => Project::published()->orderBy('order')->get(),
]))->name('contact');

Route::get('/profile/ai-insight', function () use ($profile) {
    return response()->json([
        'source' => 'Studio profile context',
        'summary' => "{$profile['name']} cocok diposisikan sebagai {$profile['role']} dengan pendekatan editorial, tenang, dan kuat secara komposisi.",
        'suggestion' => 'Tonjolkan seri foto terbaik di halaman pertama, lalu arahkan pengunjung ke koleksi portfolio dan kontak.',
    ]);
})->name('profile.ai');

Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.store');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/projects/reorder', [ProjectController::class, 'reorder'])->name('projects.reorder');
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
    Route::redirect('/', '/admin/projects')->name('dashboard');
});
