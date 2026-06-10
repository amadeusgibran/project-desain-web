<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_uses_profile_settings_from_database(): void
    {
        $this->seed(ProfileSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('Gibran Amadeus')
            ->assertSee('Saya memotret momen');
    }
}
