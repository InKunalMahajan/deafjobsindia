<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobAlertDigest extends Mailable {
    use Queueable,SerializesModels;

    public function __construct(
        public string $recipientName,
        public array $jobs,
        public string $settingsUrl
    ) {}

    public function build(): static {
        return $this->subject('Your DeafJobsIndia job matches')
            ->view('emails.job-alert-digest');
    }
}
