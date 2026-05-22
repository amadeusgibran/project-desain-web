<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');

        $messages = ContactMessage::query()
            ->when($filter === 'unread', fn ($query) => $query->unread())
            ->when($filter === 'read', fn ($query) => $query->read())
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.messages.index', [
            'messages' => $messages,
            'filter' => $filter,
            'counts' => [
                'all' => ContactMessage::count(),
                'unread' => ContactMessage::unread()->count(),
                'read' => ContactMessage::read()->count(),
            ],
        ]);
    }

    public function show(ContactMessage $message): View
    {
        if (! $message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return view('admin.messages.show', [
            'message' => $message->fresh(),
            'previousMessage' => ContactMessage::where('id', '<', $message->id)->latest('id')->first(),
            'nextMessage' => ContactMessage::where('id', '>', $message->id)->oldest('id')->first(),
        ]);
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('status', 'Pesan berhasil dihapus.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:mark_read,delete'],
            'messages' => ['required', 'array'],
            'messages.*' => ['integer', 'exists:contact_messages,id'],
        ]);

        $messages = ContactMessage::whereIn('id', $data['messages']);

        if ($data['action'] === 'delete') {
            $messages->delete();
            $status = 'Pesan terpilih berhasil dihapus.';
        } else {
            $messages->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
            $status = 'Pesan terpilih ditandai sudah dibaca.';
        }

        return back()->with('status', $status);
    }
}
