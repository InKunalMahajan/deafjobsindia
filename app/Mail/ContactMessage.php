<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $topic,
        public string $bodyText
    ) {}

    public function build(): self
    {
        return $this->subject('DeafJobsIndia contact: '.str_replace('_', ' ', $this->topic))
            ->replyTo($this->senderEmail, $this->senderName)
            ->text('emails.contact-message');
    }
}
