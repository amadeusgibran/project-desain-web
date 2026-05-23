<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PortfolioChatAssistant
{
    public function __construct(private readonly ProfileSettings $profileSettings)
    {
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $history
     */
    public function reply(string $message, array $history, array $profileDefaults): string
    {
        $apiKey = config('services.openai.key');

        if (! $apiKey) {
            return $this->fallbackReply();
        }

        $model = config('services.openai.model', 'gemini-2.5-flash');

        try {
            if (str_contains($model, 'gemini')) {
                $response = Http::acceptJson()
                    ->timeout(25)
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                        'contents' => $this->inputMessages($message, $history),
                        'systemInstruction' => [
                            'parts' => [
                                ['text' => $this->instructions($profileDefaults)],
                            ],
                        ],
                        'generationConfig' => [
                            'maxOutputTokens' => 450,
                        ],
                    ]);
            } else {
                $response = Http::withToken($apiKey)
                    ->acceptJson()
                    ->timeout(25)
                    ->post('https://api.openai.com/v1/responses', [
                        'model' => $model,
                        'instructions' => $this->instructions($profileDefaults),
                        'input' => $this->inputMessagesLegacy($message, $history),
                        'max_output_tokens' => 450,
                    ]);
            }

            if ($response->failed()) {
                Log::warning('Portfolio chat request failed.', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return $this->fallbackReply();
            }

            return $this->extractText($response->json()) ?: $this->fallbackReply();
        } catch (ConnectionException $exception) {
            Log::warning('Portfolio chat connection failed.', [
                'message' => $exception->getMessage(),
            ]);

            return $this->fallbackReply();
        }
    }

    private function instructions(array $profileDefaults): string
    {
        $profile = $this->profileSettings->many($profileDefaults);
        $projects = Project::published()
            ->orderBy('order')
            ->get(['title', 'slug', 'category', 'description', 'client', 'year', 'tools', 'link']);

        $projectContext = $projects->map(function (Project $project): string {
            $tools = collect($project->tools ?? [])->filter()->implode(', ');
            $parts = [
                "Judul: {$project->title}",
                "Kategori: {$project->category}",
                "Deskripsi: {$project->description}",
                $project->client ? "Client: {$project->client}" : null,
                $project->year ? "Tahun: {$project->year}" : null,
                $tools ? "Produksi/tools: {$tools}" : null,
                "URL detail: /portfolio/{$project->slug}",
                $project->link ? "External link: {$project->link}" : null,
            ];

            return '- '.collect($parts)->filter()->implode("\n  ");
        })->implode("\n");

        return <<<PROMPT
Kamu adalah chatbot portfolio untuk {$profile['name']}.
Bahasa utama: Indonesia yang natural, ringkas, dan profesional.
Fokus jawaban: profile, layanan fotografi, availability, project portfolio, dan cara menghubungi studio.
Kalau pertanyaan di luar konteks portfolio/fotografi, jawab singkat lalu arahkan kembali ke portfolio, layanan, atau halaman contact.
Jangan mengarang harga, jadwal kosong spesifik, atau detail project yang tidak ada di konteks.
Jika user tertarik kerja sama, arahkan ke halaman Contact dan email {$profile['email']}.

Profile:
- Nama: {$profile['name']}
- Role: {$profile['role']}
- Bio: {$profile['bio']}
- Lokasi: {$profile['location']}
- Availability: {$profile['availability']}
- Email: {$profile['email']}

Published portfolio projects:
{$projectContext}
PROMPT;
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $history
     */
    private function inputMessages(string $message, array $history): array
    {
        $messages = collect($history)
            ->take(-8)
            ->map(fn (array $item): array => [
                'role' => $item['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [
                    ['text' => $item['content']]
                ],
            ])
            ->push([
                'role' => 'user',
                'parts' => [
                    ['text' => $message]
                ],
            ]);

        return $messages->values()->all();
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $history
     */
    private function inputMessagesLegacy(string $message, array $history): array
    {
        $messages = collect($history)
            ->take(-8)
            ->map(fn (array $item): array => [
                'role' => $item['role'],
                'content' => $item['content'],
            ])
            ->push([
                'role' => 'user',
                'content' => $message,
            ]);

        return $messages->values()->all();
    }

    private function extractText(array $payload): ?string
    {
        if (isset($payload['candidates'][0]['content']['parts'][0]['text'])) {
            return trim($payload['candidates'][0]['content']['parts'][0]['text']);
        }

        if (is_string($payload['output_text'] ?? null)) {
            return trim($payload['output_text']);
        }

        foreach (($payload['output'] ?? []) as $output) {
            if (($output['type'] ?? null) !== 'message') {
                continue;
            }

            foreach (($output['content'] ?? []) as $content) {
                $text = Arr::get($content, 'text');

                if (is_string($text) && trim($text) !== '') {
                    return trim($text);
                }
            }
        }

        return null;
    }

    private function fallbackReply(): string
    {
        return 'Assistant belum terhubung penuh ke AI saat ini. Untuk sementara, saya bisa bantu arahkan: lihat halaman Portfolio untuk seri foto terbaru, atau buka Contact untuk membahas sesi portrait, editorial, arsitektur, dan dokumentasi brand.';
    }
}
