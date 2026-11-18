# Stage 25 — Account security and production email configuration

Status: DRAFT. No real email was sent and tests have not been run by the assistant.

## Code changes
- Google-only accounts cannot request or complete password resets via password broker. They must sign in with Google.
- Google login rejects mismatched email claims for a previously linked Google ID.
- Password reset rotates remember token and deletes prior sessions for database-backed session storage.
- `php artisan mail:readiness` checks basic mail transport, sender and HTTPS URL without exposing credentials or sending messages.
- Tests cover Google-only reset, session revocation and mail readiness failure.

## Set up production mail (never commit secrets)
Use the real verified sender and provider settings in the private server `.env`:
```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-real-domain.example
MAIL_MAILER=smtp
MAIL_HOST=your-provider-smtp-host
MAIL_PORT=587
MAIL_SCHEME=tls
MAIL_USERNAME=your-provider-username
MAIL_PASSWORD=your-provider-secret
MAIL_FROM_ADDRESS=verified-sender@your-real-domain.example
MAIL_FROM_NAME="DeafJobsIndia"
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
JOB_ALERTS_SCHEDULE_ENABLED=false
```
These values are placeholders, not working credentials. Do not copy placeholder addresses to production.

## Verify
```powershell
git fetch origin
git switch --track origin/feature/stage-25-auth-email-hardening
php artisan config:clear
php artisan mail:readiness
php artisan test --filter=StageTwentyFiveAuthHardeningTest
php artisan test
npm run build
```

## Outstanding blockers
- Confirm SPF, DKIM and DMARC, verified sender identity and live inbox delivery.
- Review rate limits, verification expiration, bounce handling and recovery abuse prevention.
- Password reset revocation covers database sessions only; other session drivers need an equivalent strategy.
- Email verification is not yet enforced for every sensitive operation.
- Add notifications and audit trail for password changes, suspicious logins and employer recruiter actions.
- Audit Google OAuth identity claim handling and session fixation across all login methods.
- Keep job alert scheduler disabled pending concurrency-safe delivery and verified SMTP.
- Do not deploy or enable mail until manual checks and the full test suite pass.
