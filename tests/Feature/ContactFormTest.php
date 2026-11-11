<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    private function validData(): array
    {
        return [
            'name' => 'Test Sender',
            'email' => 'sender@example.org',
            'topic' => 'correction',
            'message' => 'Please correct the application deadline.',
            'website' => '',
        ];
    }

    public function test_contact_form_is_accessible_and_has_csrf_token(): void
    {
        $this->get('/contact')->assertOk()
            ->assertSee('Your name')->assertSee('Email address')
            ->assertSee('Your message')->assertSee('name="_token"', false);
    }

    public function test_valid_contact_message_is_sent_to_configured_recipient(): void
    {
        config(['services.contact.recipient' => 'team@example.org', 'mail.default' => 'smtp']);
        Mail::fake();
        $this->post('/contact', $this->validData())->assertRedirect(route('contact'))
            ->assertSessionHas('contact_sent', true);
        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            return $mail->hasTo('team@example.org')
                && $mail->senderEmail === 'sender@example.org';
        });
    }

    public function test_invalid_contact_message_is_rejected(): void
    {
        Mail::fake();
        $this->post('/contact', ['name' => '', 'email' => 'invalid', 'topic' => 'bad', 'message' => 'short'])
            ->assertSessionHasErrors(['name', 'email', 'topic', 'message']);
        Mail::assertNothingSent();
    }

    public function test_honeypot_does_not_send_email(): void
    {
        config(['services.contact.recipient' => 'team@example.org', 'mail.default' => 'smtp']);
        Mail::fake();
        $this->post('/contact', array_merge($this->validData(), ['website' => 'spam.example']))
            ->assertSessionHas('contact_sent', true);
        Mail::assertNothingSent();
    }

    public function test_missing_delivery_configuration_never_claims_success(): void
    {
        config(['services.contact.recipient' => null, 'mail.default' => 'log']);
        Mail::fake();
        $this->post('/contact', $this->validData())->assertSessionHasErrors('contact');
        Mail::assertNothingSent();
    }
}
