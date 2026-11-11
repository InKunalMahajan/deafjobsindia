# Stage 9 — Contact and privacy deployment checklist

## Contact form
- GET /contact, POST /contact (CSRF and throttle:3,1)
- Server validation: name, email, topic, message, honeypot
- Mail sent synchronously to configured recipient; never log full user messages by default
- Missing or log/array mailer returns an explicit error rather than a false success
- Mail delivery failures are reported to the application exception handler

## Required environment configuration
CONTACT_RECIPIENT=your-monitored-mailbox@example.org
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...

Use a real monitored mailbox. Do not commit credentials. Configure SPF/DKIM/DMARC for your domain.
Do not use log/array mailers in production for real contact delivery.

## Test
php artisan optimize:clear
php artisan test --filter=ContactFormTest
php artisan test
npm run build

## Before public launch
- Legal review: final privacy notice, lawful purpose, data controller details, actual providers, log/mail retention and deletion procedures
- Verify successful delivery and reply handling from a real mailbox
- Verify throttle, CSRF, honeypot and abuse monitoring
- Test keyboard, screen reader, form errors, mobile and 200% zoom
- Confirm no CVs or sensitive documents are collected
- Confirm Contact and Privacy text is accurate for deployed services

The Privacy page is a provisional architecture and must not be presented as a legally reviewed policy.
