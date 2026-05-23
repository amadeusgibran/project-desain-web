<?php

use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Models\Project;
use App\Services\PortfolioChatAssistant;
use App\Services\ProfileSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$profileDefaults = [
    'name' => 'Gibran Studio',
    'role' => 'photographer & visual storyteller',
    'bio' => 'Saya memotret momen, ruang, dan karakter dengan pendekatan editorial yang bersih.',
    'email' => 'hello@gibranstudio.dev',
    'location' => 'Bandung, Indonesia',
    'availability' => 'Menerima sesi portrait, editorial, produk, dan dokumentasi visual untuk brand maupun personal.',
    'social_linkedin' => '#',
    'social_instagram' => '#',
    'social_behance' => '#',
    'avatar' => null,
];

Route::get('/', function () use ($profileDefaults) {
    return view('portfolio', [
        'page' => 'about',
        'profile' => app(ProfileSettings::class)->many($profileDefaults),
        'projects' => Project::published()->orderBy('order')->get(),
    ]);
})->name('about');

Route::get('/portfolio', function () use ($profileDefaults) {
    return view('portfolio', [
        'page' => 'portfolio',
        'profile' => app(ProfileSettings::class)->many($profileDefaults),
        'projects' => Project::published()->orderBy('order')->get(),
    ]);
})->name('portfolio');

Route::get('/portfolio/{slug}', function (string $slug) use ($profileDefaults) {
    $projects = Project::published()->orderBy('order')->get();
    $index = $projects->search(fn (Project $project) => $project->slug === $slug);

    abort_if($index === false, 404);

    return view('portfolio-detail', [
        'profile' => app(ProfileSettings::class)->many($profileDefaults),
        'project' => $projects[$index],
        'previousProject' => $index > 0 ? $projects[$index - 1] : null,
        'nextProject' => $index < $projects->count() - 1 ? $projects[$index + 1] : null,
    ]);
})->name('portfolio.detail');

Route::get('/contact', function () use ($profileDefaults) {
    return view('portfolio', [
        'page' => 'contact',
        'profile' => app(ProfileSettings::class)->many($profileDefaults),
        'projects' => Project::published()->orderBy('order')->get(),
    ]);
})->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/profile/ai-insight', function () use ($profileDefaults) {
    $profile = app(ProfileSettings::class)->many($profileDefaults);

    return response()->json([
        'source' => 'Studio profile context',
        'summary' => "{$profile['name']} cocok diposisikan sebagai {$profile['role']} dengan pendekatan editorial, tenang, dan kuat secara komposisi.",
        'suggestion' => 'Tonjolkan seri foto terbaik di halaman pertama, lalu arahkan pengunjung ke koleksi portfolio dan kontak.',
    ]);
})->name('profile.ai');

Route::post('/profile/chat', function (Request $request, PortfolioChatAssistant $assistant) use ($profileDefaults) {
    $validated = $request->validate([
        'message' => ['required', 'string', 'max:1000'],
        'history' => ['sometimes', 'array', 'max:8'],
        'history.*.role' => ['required_with:history', 'string', 'in:user,assistant'],
        'history.*.content' => ['required_with:history', 'string', 'max:1000'],
    ]);

    return response()->json([
        'reply' => $assistant->reply(
            $validated['message'],
            $validated['history'] ?? [],
            $profileDefaults
        ),
    ]);
})->name('profile.chat');

Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.store');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/messages/bulk', [MessageController::class, 'bulk'])->name('messages.bulk');
    Route::resource('messages', MessageController::class)->only(['index', 'show', 'destroy']);
    Route::post('/projects/reorder', [ProjectController::class, 'reorder'])->name('projects.reorder');
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
    Route::redirect('/', '/admin/projects')->name('dashboard');
});
