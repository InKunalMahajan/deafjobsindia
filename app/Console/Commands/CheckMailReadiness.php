<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMailReadiness extends Command {
    protected $signature='mail:readiness';
    protected $description='Check production email configuration without sending a message or showing credentials';

    public function handle(): int {
        $issues=[];
        $mailer=config('mail.default');
        $from=config('mail.from.address');
        $appUrl=config('app.url');

        if(in_array($mailer,['log','array','failover'],true)) {
            $issues[]='MAIL_MAILER must use a real transport. Avoid failover to log for security emails.';
        }
        if($mailer==='smtp') {
            if(!filled(config('mail.mailers.smtp.host')) || in_array(config('mail.mailers.smtp.host'),['127.0.0.1','localhost'],true)) {
                $issues[]='Set a real MAIL_HOST.';
            }
            if(!filled(config('mail.mailers.smtp.username')) || !filled(config('mail.mailers.smtp.password'))) {
                $issues[]='SMTP credentials are missing.';
            }
        }
        if(!filter_var($from,FILTER_VALIDATE_EMAIL) || str_ends_with(strtolower($from),'@example.com')) {
            $issues[]='Set a verified MAIL_FROM_ADDRESS.';
        }
        if(!is_string($appUrl) || !str_starts_with($appUrl,'https://')) {
            $issues[]='Set APP_URL to the public HTTPS URL before production email.';
        }
        if($issues) {
            foreach($issues as $issue) $this->error($issue);
            $this->warn('Email readiness failed. No email was sent.');
            return self::FAILURE;
        }
        $this->info('Basic email configuration checks passed. No email was sent.');
        $this->warn('Real SMTP delivery, DNS (SPF/DKIM/DMARC), inbox receipt and unsubscribe still require manual verification.');
        return self::SUCCESS;
    }
}
