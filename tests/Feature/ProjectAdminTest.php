<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_project_detail_uses_database_project(): void
    {
        $this->seed(ProjectSeeder::class);

        $this->get('/portfolio/monolith-study')
            ->assertOk()
            ->assertSee('Monolith Study');
    }

    public function test_admin_projects_require_authentication(): void
    {
        $this->get('/admin/projects')
            ->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_open_projects_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/projects')
            ->assertOk()
            ->assertSee('Photo Series');
    }

    public function test_authenticated_admin_can_create_category(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/admin/categories', ['name' => 'Commercial'])
            ->assertRedirect();

        $this->assertDatabaseHas('categories', ['name' => 'Commercial']);
    }

    public function test_project_form_uses_category_dropdown(): void
    {
        $this->seed(CategorySeeder::class);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/projects/create')
            ->assertOk()
            ->assertSee('CHOOSE CATEGORY')
            ->assertSee('EDITORIAL');
    }
}
