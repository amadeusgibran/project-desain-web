<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PortfolioChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_chat_returns_fallback_without_openai_key(): void
    {
        config(['services.openai.key' => null]);

        $this->postJson('/profile/chat', [
            'message' => 'Apa layanan yang tersedia?',
        ])
            ->assertOk()
            ->assertJsonPath('reply', fn (string $reply): bool => str_contains($reply, 'Assistant belum terhubung'));
    }

    public function test_profile_chat_validates_message(): void
    {
        $this->postJson('/profile/chat', [
            'message' => '',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('message');
    }

    public function test_profile_chat_returns_openai_reply(): void
    {
        config([
            'services.openai.key' => 'test-key',
            'services.openai.model' => 'gpt-5.4-mini',
        ]);

        Project::create([
            'title' => 'Monolith Study',
            'slug' => 'monolith-study',
            'category' => 'Editorial',
            'description' => 'Seri editorial tentang cahaya dan arsitektur.',
            'client' => 'Monolith Residence',
            'year' => '2026',
            'tools' => ['Canon EOS R6'],
            'order' => 1,
            'is_published' => true,
        ]);

        Http::fake([
            'api.openai.com/v1/responses' => Http::response([
                'output' => [
                    [
                        'type' => 'message',
                        'content' => [
                            [
                                'type' => 'output_text',
                                'text' => 'Gibran Studio tersedia untuk portrait, editorial, arsitektur, dan dokumentasi brand.',
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $this->postJson('/profile/chat', [
            'message' => 'Layanan apa saja yang tersedia?',
            'history' => [
                ['role' => 'user', 'content' => 'Halo'],
                ['role' => 'assistant', 'content' => 'Halo, ada yang bisa dibantu?'],
            ],
        ])
            ->assertOk()
            ->assertJsonPath('reply', 'Gibran Studio tersedia untuk portrait, editorial, arsitektur, dan dokumentasi brand.');

        Http::assertSent(fn ($request): bool => $request->url() === 'https://api.openai.com/v1/responses'
            && $request['model'] === 'gpt-5.4-mini'
            && str_contains($request['instructions'], 'Monolith Study')
            && $request['input'][0]['role'] === 'user'
            && $request['input'][2]['content'] === 'Layanan apa saja yang tersedia?');
    }

    public function test_profile_chat_only_uses_published_projects_for_context(): void
    {
        config([
            'services.openai.key' => 'test-key',
            'services.openai.model' => 'gpt-5.4-mini',
        ]);

        Project::create([
            'title' => 'Published Series',
            'slug' => 'published-series',
            'category' => 'Portrait',
            'description' => 'Project yang tampil di portfolio.',
            'order' => 1,
            'is_published' => true,
        ]);

        Project::create([
            'title' => 'Draft Series',
            'slug' => 'draft-series',
            'category' => 'Editorial',
            'description' => 'Project draft.',
            'order' => 2,
            'is_published' => false,
        ]);

        Http::fake([
            'api.openai.com/v1/responses' => Http::response([
                'output_text' => 'Project yang tersedia adalah Published Series.',
            ]),
        ]);

        $this->postJson('/profile/chat', [
            'message' => 'Ada project apa?',
        ])->assertOk();

        Http::assertSent(fn ($request): bool => str_contains($request['instructions'], 'Published Series')
            && ! str_contains($request['instructions'], 'Draft Series'));
    }

    public function test_profile_chat_returns_gemini_reply(): void
    {
        config([
            'services.openai.key' => 'test-gemini-key',
            'services.openai.model' => 'gemini-2.5-flash',
        ]);

        Project::create([
            'title' => 'Monolith Study',
            'slug' => 'monolith-study',
            'category' => 'Editorial',
            'description' => 'Seri editorial tentang cahaya dan arsitektur.',
            'client' => 'Monolith Residence',
            'year' => '2026',
            'tools' => ['Canon EOS R6'],
            'order' => 1,
            'is_published' => true,
        ]);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Gibran Studio tersedia untuk portrait, editorial, arsitektur, dan dokumentasi brand.'],
                            ],
                            'role' => 'model',
                        ],
                    ],
                ],
            ]),
        ]);

        $this->postJson('/profile/chat', [
            'message' => 'Layanan apa saja yang tersedia?',
            'history' => [
                ['role' => 'user', 'content' => 'Halo'],
                ['role' => 'assistant', 'content' => 'Halo, ada yang bisa dibantu?'],
            ],
        ])
            ->assertOk()
            ->assertJsonPath('reply', 'Gibran Studio tersedia untuk portrait, editorial, arsitektur, dan dokumentasi brand.');

        Http::assertSent(fn ($request): bool => str_contains($request->url(), 'generativelanguage.googleapis.com')
            && str_contains($request->url(), 'key=test-gemini-key')
            && $request['contents'][0]['role'] === 'user'
            && $request['contents'][1]['role'] === 'model'
            && $request['contents'][2]['parts'][0]['text'] === 'Layanan apa saja yang tersedia?'
            && str_contains($request['systemInstruction']['parts'][0]['text'], 'Monolith Study'));
    }
}

