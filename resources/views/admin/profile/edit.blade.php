@extends('layouts.admin')

@section('title', 'Profile Settings')

@section('content')
    <header class="admin-page-head">
        <div>
            <span class="eyebrow">Studio</span>
            <h1>PROFILE SETTINGS</h1>
        </div>
    </header>

    <form class="admin-card admin-form" method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <section class="admin-form-section">
            <div class="admin-section-head">
                <span class="eyebrow">01</span>
                <h2>Identity</h2>
            </div>

            <div class="admin-form-grid">
                <label>
                    <span>NAME</span>
                    <input name="name" value="{{ old('name', $profile['name']) }}" required>
                    @error('name')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    <span>ROLE</span>
                    <input name="role" value="{{ old('role', $profile['role']) }}" required>
                    @error('role')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    <span>EMAIL CONTACT</span>
                    <input name="email" type="email" value="{{ old('email', $profile['email']) }}" required>
                    @error('email')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    <span>LOCATION</span>
                    <input name="location" value="{{ old('location', $profile['location']) }}">
                    @error('location')<small>{{ $message }}</small>@enderror
                </label>
            </div>

            <label class="admin-field-wide">
                <span>BIO</span>
                <textarea name="bio" rows="4">{{ old('bio', $profile['bio']) }}</textarea>
                @error('bio')<small>{{ $message }}</small>@enderror
            </label>

            <label class="admin-field-wide">
                <span>AVAILABILITY</span>
                <textarea name="availability" rows="4">{{ old('availability', $profile['availability']) }}</textarea>
                @error('availability')<small>{{ $message }}</small>@enderror
            </label>
        </section>

        <section class="admin-form-section">
            <div class="admin-section-head">
                <span class="eyebrow">02</span>
                <h2>Avatar & Socials</h2>
            </div>

            <div class="profile-settings-grid">
                <label class="cover-upload-card">
                    <span>AVATAR</span>
                    <strong data-cover-file-name>CHOOSE AVATAR</strong>
                    <small>JPG, PNG, or WEBP. Max 4MB.</small>
                    <input name="avatar" type="file" accept="image/*" data-cover-input>
                    @error('avatar')<small>{{ $message }}</small>@enderror
                </label>

                @if (! empty($profile['avatar']))
                    <div class="profile-avatar-preview">
                        <span>CURRENT AVATAR</span>
                        <img src="{{ Storage::url($profile['avatar']) }}" alt="{{ $profile['name'] }}">
                    </div>
                @endif
            </div>

            <div class="admin-form-grid">
                <label>
                    <span>LINKEDIN URL</span>
                    <input name="social_linkedin" value="{{ old('social_linkedin', $profile['social_linkedin']) }}">
                    @error('social_linkedin')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    <span>INSTAGRAM URL</span>
                    <input name="social_instagram" value="{{ old('social_instagram', $profile['social_instagram']) }}">
                    @error('social_instagram')<small>{{ $message }}</small>@enderror
                </label>

                <label>
                    <span>BEHANCE URL</span>
                    <input name="social_behance" value="{{ old('social_behance', $profile['social_behance']) }}">
                    @error('social_behance')<small>{{ $message }}</small>@enderror
                </label>
            </div>
        </section>

        <div class="admin-actions">
            <button type="submit">SAVE PROFILE</button>
        </div>
    </form>
@endsection
