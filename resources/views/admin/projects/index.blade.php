@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <header class="admin-page-head">
        <div>
            <span class="eyebrow">Portfolio</span>
            <h1>Photo Series</h1>
        </div>
        <a class="admin-primary" href="{{ route('admin.projects.create') }}">Add Project</a>
    </header>

    <section class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-projects">
                    @forelse ($projects as $project)
                        <tr data-id="{{ $project->id }}">
                            <td class="drag-handle">{{ $project->order }}</td>
                            <td>
                                <img class="admin-thumb" src="{{ $project->cover_image_url }}" alt="{{ $project->title }}">
                            </td>
                            <td>
                                <strong>{{ $project->title }}</strong>
                                <small>{{ $project->client }}</small>
                            </td>
                            <td>{{ $project->category }}</td>
                            <td>
                                <span class="status-pill {{ $project->is_published ? 'published' : '' }}">
                                    {{ $project->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('portfolio.detail', $project->slug) }}" target="_blank" rel="noreferrer">View</a>
                                    <a href="{{ route('admin.projects.edit', $project) }}">Edit</a>
                                    <form method="post" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada project.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        const sortableProjects = document.querySelector('#sortable-projects');

        if (sortableProjects && window.Sortable) {
            new Sortable(sortableProjects, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: async () => {
                    const projects = [...sortableProjects.querySelectorAll('tr[data-id]')].map((row, index) => ({
                        id: row.dataset.id,
                        order: index + 1,
                    }));

                    await fetch('{{ route('admin.projects.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ projects }),
                    });
                },
            });
        }
    </script>
@endsection
