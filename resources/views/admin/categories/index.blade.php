@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <header class="admin-page-head">
        <div>
            <span class="eyebrow">Portfolio</span>
            <h1>Categories</h1>
        </div>
    </header>

    <section class="admin-grid-two">
        <form class="admin-card admin-form" method="post" action="{{ route('admin.categories.store') }}">
            @csrf
            <label>
                <span>Category Name</span>
                <input name="name" value="{{ old('name') }}" placeholder="Commercial" required>
                @error('name')<small>{{ $message }}</small>@enderror
            </label>
            <div class="admin-actions">
                <button type="submit">Create Category</button>
            </div>
        </form>

        <section class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td>{{ $category->slug }}</td>
                                <td>
                                    <div class="table-actions">
                                        <form method="post" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Belum ada category.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection
