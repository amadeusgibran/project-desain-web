@extends('layouts.admin')

@section('title', $message->subject)

@section('content')
    <header class="admin-page-head">
        <div>
            <span class="eyebrow">Inbox</span>
            <h1>{{ $message->subject }}</h1>
        </div>
        <a class="admin-primary" href="{{ route('admin.messages.index') }}">Back to Inbox</a>
    </header>

    <section class="message-detail-grid">
        <article class="admin-card message-detail-card">
            <div class="message-meta-grid">
                <div>
                    <span class="meta-label">Name</span>
                    <strong>{{ $message->name }}</strong>
                </div>
                <div>
                    <span class="meta-label">Email</span>
                    <strong>{{ $message->email }}</strong>
                </div>
                <div>
                    <span class="meta-label">Sent</span>
                    <strong>{{ $message->created_at->format('d M Y H:i') }}</strong>
                </div>
                <div>
                    <span class="meta-label">Status</span>
                    <strong>{{ $message->is_read ? 'Read' : 'Unread' }}</strong>
                </div>
            </div>

            <div class="message-body">
                {{ $message->message }}
            </div>

            <div class="admin-actions">
                <form method="post" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete Message</button>
                </form>
            </div>
        </article>

        <aside class="message-nav-stack">
            @if ($previousMessage)
                <a class="project-nav-card" href="{{ route('admin.messages.show', $previousMessage) }}">
                    <span class="meta-label">&larr; Previous</span>
                    <strong>{{ $previousMessage->subject }}</strong>
                </a>
            @endif

            @if ($nextMessage)
                <a class="project-nav-card next" href="{{ route('admin.messages.show', $nextMessage) }}">
                    <span class="meta-label">Next &rarr;</span>
                    <strong>{{ $nextMessage->subject }}</strong>
                </a>
            @endif
        </aside>
    </section>
@endsection
