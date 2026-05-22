<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_stores_message(): void
    {
        $this->post('/contact', [
            'name' => 'Gibran',
            'email' => 'gibran@example.com',
            'subject' => 'Portrait Session',
            'message' => 'Saya ingin booking sesi portrait editorial.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'gibran@example.com',
            'is_read' => false,
        ]);
    }

    public function test_honeypot_does_not_store_message(): void
    {
        $this->post('/contact', [
            'name' => 'Spam',
            'email' => 'spam@example.com',
            'subject' => 'Portrait Session',
            'message' => 'Spam message content.',
            'website' => 'https://spam.test',
        ])->assertRedirect('/contact');

        $this->assertDatabaseMissing('contact_messages', [
            'email' => 'spam@example.com',
        ]);
    }

    public function test_opening_admin_message_marks_it_read(): void
    {
        $user = User::factory()->create();
        $message = ContactMessage::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'subject' => 'Brand Documentation',
            'message' => 'Need visual documentation.',
        ]);

        $this->actingAs($user)
            ->get("/admin/messages/{$message->id}")
            ->assertOk();

        $this->assertTrue($message->fresh()->is_read);
    }

    public function test_bulk_mark_messages_as_read(): void
    {
        $user = User::factory()->create();
        $message = ContactMessage::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'subject' => 'Editorial Photography',
            'message' => 'Need editorial photos.',
        ]);

        $this->actingAs($user)
            ->post('/admin/messages/bulk', [
                'action' => 'mark_read',
                'messages' => [$message->id],
            ])
            ->assertRedirect();

        $this->assertTrue($message->fresh()->is_read);
    }
}
