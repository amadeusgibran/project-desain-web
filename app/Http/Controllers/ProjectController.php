<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::query()->orderBy('order')->orderByDesc('created_at')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'project' => new Project([
                'year' => now()->year,
                'order' => (Project::max('order') ?? 0) + 1,
                'is_published' => true,
            ]),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $data = $this->projectData($request);
        $data['cover_image'] = $request->file('cover_image')->store('projects', 'public');
        $data['images'] = $this->storeGallery($request);

        Project::create($data);

        return redirect()
            ->route('admin.projects.index')
            ->with('status', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', [
            'project' => $project,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $data = $this->projectData($request);

        if ($request->hasFile('cover_image')) {
            $this->deleteUploadedFile($project->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('projects', 'public');
        }

        $images = collect($project->images ?? [])
            ->reject(fn (string $image) => in_array($image, $request->input('remove_images', []), true))
            ->values()
            ->all();

        foreach ($request->input('remove_images', []) as $image) {
            $this->deleteUploadedFile($image);
        }

        $data['images'] = array_values(array_merge($images, $this->storeGallery($request)));

        $project->update($data);

        return redirect()
            ->route('admin.projects.index')
            ->with('status', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->deleteUploadedFile($project->cover_image);

        foreach ($project->images ?? [] as $image) {
            $this->deleteUploadedFile($image);
        }

        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('status', 'Project berhasil dihapus.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'projects' => ['required', 'array'],
            'projects.*.id' => ['required', 'integer', 'exists:projects,id'],
            'projects.*.order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($data['projects'] as $item) {
            Project::whereKey($item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['status' => 'ok']);
    }

    private function projectData(Request $request): array
    {
        return [
            'title' => $request->string('title')->toString(),
            'slug' => $this->uniqueSlug($request->string('title')->toString(), $request->route('project')),
            'category' => $request->string('category')->toString(),
            'description' => $request->string('description')->toString(),
            'client' => $request->string('client')->toString() ?: null,
            'year' => $request->string('year')->toString() ?: null,
            'tools' => $this->splitLines($request->string('tools')->toString()),
            'link' => $request->string('link')->toString() ?: null,
            'order' => (int) ($request->input('order') ?? 0),
            'is_published' => $request->boolean('is_published'),
        ];
    }

    private function splitLines(string $value): array
    {
        return collect(preg_split('/[\r\n,]+/', $value))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    private function uniqueSlug(string $title, ?Project $project = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $count = 2;

        while (Project::where('slug', $slug)
            ->when($project, fn ($query) => $query->whereKeyNot($project->id))
            ->exists()) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }

    private function storeGallery(Request $request): array
    {
        if (! $request->hasFile('images')) {
            return [];
        }

        return collect($request->file('images'))
            ->map(fn ($image) => $image->store('projects', 'public'))
            ->all();
    }

    private function deleteUploadedFile(?string $path): void
    {
        if ($path && str_starts_with($path, 'projects/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
