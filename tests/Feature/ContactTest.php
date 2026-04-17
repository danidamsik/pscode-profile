<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_submit_contact_message(): void
    {
        $response = $this->from('/#kontak')->post(route('contacts.store'), [
            'name' => 'Andi Prasetyo',
            'email' => 'andi@example.com',
            'message' => 'Saya ingin konsultasi pembuatan website company profile.',
        ]);

        $response->assertRedirect('/#kontak');
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('contacts', [
            'name' => 'Andi Prasetyo',
            'email' => 'andi@example.com',
            'message' => 'Saya ingin konsultasi pembuatan website company profile.',
            'status' => 'unread',
        ]);
    }

    public function test_contact_message_requires_name_email_and_message(): void
    {
        $response = $this->from('/#kontak')->post(route('contacts.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'message' => '',
        ]);

        $response->assertRedirect('/#kontak');
        $response->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertSame(0, Contact::count());
    }
}
