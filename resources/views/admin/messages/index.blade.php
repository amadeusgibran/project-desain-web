@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
    <header class="admin-page-head">
        <div>
            <span class="eyebrow">Inbox</span>
            <h1>Messages</h1>
        </div>
    </header>

    <nav class="admin-filter-tabs" aria-label="Message filters">
        @foreach (['all' => 'All', 'unread' => 'Unread', 'read' => 'Read'] as $key => $label)
            <a class="{{ $filter === $key ? 'active' : '' }}" href="{{ route('admin.messages.index', ['filter' => $key]) }}">
                {{ $label }} <span>{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </nav>

    <form class="admin-card" method="post" action="{{ route('admin.messages.bulk') }}">
        @csrf
        <div class="bulk-actions">
            <select name="action" required>
                <option value="">Bulk action</option>
                <option value="mark_read">Mark as read</option>
                <option value="delete">Delete</option>
            </select>
            <button type="submit">Apply</button>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" data-check-all></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Sent</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr>
                            <td><input name="messages[]" type="checkbox" value="{{ $message->id }}" data-message-check></td>
                            <td><strong>{{ $message->name }}</strong></td>
                            <td>{{ $message->email }}</td>
                            <td>{{ $message->subject }}</td>
                            <td>{{ $message->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <span class="status-pill {{ $message->is_read ? 'published' : '' }}">
                                    {{ $message->is_read ? 'Read' : 'Unread' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.messages.show', $message) }}">Open</a>
                                    <button form="delete-message-{{ $message->id }}" type="submit">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Belum ada pesan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    @foreach ($messages as $message)
        <form id="delete-message-{{ $message->id }}" method="post" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <div class="admin-pagination">
        {{ $messages->links() }}
    </div>
@endsection
