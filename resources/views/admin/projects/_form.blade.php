@csrf

<section class="admin-form-section">
    <div class="admin-section-head">
        <span class="eyebrow">01</span>
        <h2>Series Information</h2>
    </div>

    <div class="admin-form-grid">
        <label>
            <span>TITLE</span>
            <input name="title" value="{{ old('title', $project->title) }}" required>
            @error('title')<small>{{ $message }}</small>@enderror
        </label>

        <label>
            <span>CATEGORY</span>
            <select name="category" required>
                <option value="">CHOOSE CATEGORY</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->name }}" @selected(old('category', $project->category) === $category->name)>
                        {{ strtoupper($category->name) }}
                    </option>
                @endforeach
            </select>
            @error('category')<small>{{ $message }}</small>@enderror
            <a class="field-helper-link" href="{{ route('admin.categories.index') }}">CREATE NEW CATEGORY</a>
        </label>

        <label>
            <span>CLIENT</span>
            <input name="client" value="{{ old('client', $project->client) }}">
            @error('client')<small>{{ $message }}</small>@enderror
        </label>

        <label>
            <span>YEAR</span>
            <input name="year" value="{{ old('year', $project->year) }}" inputmode="numeric">
            @error('year')<small>{{ $message }}</small>@enderror
        </label>

        <label>
            <span>ORDER</span>
            <input name="order" type="number" min="0" value="{{ old('order', $project->order) }}">
            @error('order')<small>{{ $message }}</small>@enderror
        </label>

        <label>
            <span>EXTERNAL LINK</span>
            <input name="link" type="url" value="{{ old('link', $project->link) }}">
            @error('link')<small>{{ $message }}</small>@enderror
        </label>
    </div>
</section>

<section class="admin-form-section">
    <div class="admin-section-head">
        <span class="eyebrow">02</span>
        <h2>Story & Production</h2>
    </div>

    <label class="admin-field-wide">
        <span>DESCRIPTION</span>
        <textarea name="description" rows="8" required>{{ old('description', $project->description) }}</textarea>
        @error('description')<small>{{ $message }}</small>@enderror
    </label>

    <label class="admin-field-wide">
        <span>PRODUCTION / TOOLS</span>
        <textarea name="tools" rows="4" placeholder="CANON EOS R6&#10;35MM LENS&#10;LIGHTROOM">{{ old('tools', implode("\n", $project->tools ?? [])) }}</textarea>
        @error('tools')<small>{{ $message }}</small>@enderror
    </label>
</section>

<section class="admin-form-section">
    <div class="admin-section-head">
        <span class="eyebrow">03</span>
        <h2>Media</h2>
    </div>

    <div class="admin-media-grid">
        <label class="cover-upload-card">
            <span>COVER IMAGE</span>
            <strong data-cover-file-name>{{ $project->exists ? 'REPLACE COVER IMAGE' : 'CHOOSE COVER IMAGE' }}</strong>
            <small>JPG, PNG, or WEBP. Max 100MB.</small>
            <input name="cover_image" type="file" accept="image/*" @if (! $project->exists) required @endif data-cover-input>
            @error('cover_image')<small>{{ $message }}</small>@enderror
        </label>

        <div class="admin-field-wide">
            <span>GALLERY IMAGES</span>
            <label class="gallery-dropzone" for="gallery-images">
                <strong>DROP PHOTOS HERE OR CLICK TO BROWSE</strong>
                <small>UPLOAD MULTIPLE JPG, PNG, OR WEBP FILES (MAX 100MB PER FILE). CLICK X ON PREVIEW TO REMOVE BEFORE SUBMIT.</small>
                <input id="gallery-images" name="images[]" type="file" accept="image/*" multiple data-gallery-input>
            </label>
            <div class="gallery-upload-preview" data-gallery-preview></div>
            @error('images')<small>{{ $message }}</small>@enderror
            @error('images.*')<small>{{ $message }}</small>@enderror
        </div>
    </div>
</section>

@if ($project->exists)
    <div class="admin-image-preview">
        <div>
            <span>CURRENT COVER</span>
            <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}">
        </div>

        @foreach ($project->images ?? [] as $image)
            <label>
                <span>GALLERY IMAGE</span>
                <img src="{{ $project->imageUrl($image) }}" alt="{{ $project->title }} gallery">
                <span class="check-row">
                    <input name="remove_images[]" type="checkbox" value="{{ $image }}">
                    <span>REMOVE</span>
                </span>
            </label>
        @endforeach
    </div>
@endif

<label class="check-row admin-publish">
    <input name="is_published" type="checkbox" value="1" @checked(old('is_published', $project->is_published))>
    <span>PUBLISHED</span>
</label>

<div class="admin-actions">
    <a href="{{ route('admin.projects.index') }}">CANCEL</a>
    <button type="submit">{{ $submitLabel }}</button>
</div>
