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
            ->assertSee('Gibran Studio')
            ->assertSee('Saya memotret momen');
    }

    public function test_admin_can_update_profile_settings(): void
    {
        $this->seed(ProfileSeeder::class);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put('/admin/profile', [
                'name' => 'Studio Baru',
                'role' => 'editorial photographer',
                'bio' => 'Bio baru untuk halaman about.',
                'email' => 'studio@example.com',
                'location' => 'Jakarta, Indonesia',
                'availability' => 'Available for editorial work.',
                'social_linkedin' => '#',
                'social_instagram' => 'https://instagram.com/studio',
                'social_behance' => '#',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('profile_settings', [
            'key' => 'name',
            'value' => 'Studio Baru',
        ]);
    }
}
