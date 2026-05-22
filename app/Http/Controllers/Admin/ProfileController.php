<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProfileSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(ProfileSettings $settings): View
    {
        return view('admin.profile.edit', [
            'profile' => $settings->many($this->defaults()),
        ]);
    }

    public function update(Request $request, ProfileSettings $settings): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['required', 'string', 'max:160'],
            'bio' => ['nullable', 'string', 'max:500'],
            'email' => ['required', 'email', 'max:180'],
            'location' => ['nullable', 'string', 'max:160'],
            'availability' => ['nullable', 'string', 'max:500'],
            'social_linkedin' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_behance' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('avatar')) {
            $currentAvatar = $settings->get('avatar');

            if ($currentAvatar && str_starts_with($currentAvatar, 'profile/')) {
                Storage::disk('public')->delete($currentAvatar);
            }

            $data['avatar'] = $request->file('avatar')->store('profile', 'public');
        }

        foreach ($data as $key => $value) {
            if ($key !== 'avatar' || $request->hasFile('avatar')) {
                $settings->set($key, $value);
            }
        }

        $settings->forget();

        return back()->with('status', 'Profile berhasil diperbarui.');
    }

    private function defaults(): array
    {
        return [
            'name' => 'Gibran Studio',
            'role' => 'photographer & visual storyteller',
            'bio' => '',
            'email' => 'hello@gibranstudio.dev',
            'location' => 'Bandung, Indonesia',
            'availability' => '',
            'social_linkedin' => '#',
            'social_instagram' => '#',
            'social_behance' => '#',
            'avatar' => null,
        ];
    }
}
