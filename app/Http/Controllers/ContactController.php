<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        if ($request->filled('website')) {
            return redirect()
                ->route('contact')
                ->with('contact_status', 'Pesan berhasil dikirim. Saya akan membalas secepatnya.');
        }

        ContactMessage::create($request->safe()->except('website'));

        return redirect()
            ->route('contact')
            ->with('contact_status', 'Pesan berhasil dikirim. Saya akan membalas secepatnya.');
    }
}
