<?php

namespace App\Services;

use App\Models\ProfileSetting;
use Illuminate\Support\Facades\Cache;

class ProfileSettings
{
    private const CACHE_KEY = 'profile_settings';

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        ProfileSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget(self::CACHE_KEY);
    }

    public function many(array $defaults): array
    {
        $settings = $this->all();

        return collect($defaults)
            ->mapWithKeys(fn (mixed $default, string $key) => [$key => $settings[$key] ?? $default])
            ->all();
    }

    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addHour(), function () {
            return ProfileSetting::query()
                ->pluck('value', 'key')
                ->all();
        });
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
