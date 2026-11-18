# DeafJobsIndia — Stage 22 production readiness checklist

Status: DRAFT / NOT RELEASED. This document does not certify production readiness.

## 1. Code and dependencies
- [ ] Merge Stage 15 through Stage 22 PRs in dependency order after conflict resolution and review.
- [ ] Update and commit composer.lock for Socialite and other dependencies.
- [ ] Run composer validate --strict and composer audit.
- [ ] Run npm audit and review each reported vulnerability.
- [ ] Run php artisan test, php artisan route:list, php artisan config:cache, php artisan view:cache, npm ci and npm run build.
- [ ] Run Laravel Pint and review its changes.

## 2. Authentication and privacy (blocking)
- [ ] Add tested password reset, email verification and account recovery for seeker/employer roles.
- [ ] Verify Google OAuth callback, state protection and account linking.
- [ ] Review all role policies and multi-company employer membership authorization.
- [ ] Add secure self-service account deletion or documented verified-request process.
- [ ] Audit resume file scanning, storage, file name safety, access controls and backup retention.
- [ ] Test data exports for large accounts and ensure sensitive information cannot leak through logs.
- [ ] Review privacy notices, consent, recruiter retention and Indian privacy compliance with qualified counsel.

## 3. Recruitment integrity (blocking)
- [ ] Verify company ownership before approving employer.
- [ ] Ensure only authorized admin users publish verified internal jobs.
- [ ] Test moderation and anti-scam reports.
- [ ] Test applicant consent and access revocation, candidate confidentiality and interview time zones.
- [ ] Confirm external applications never claim to have been sent by DeafJobsIndia.

## 4. Email delivery (blocking)
- [ ] Keep JOB_ALERTS_SCHEDULE_ENABLED=false until verified email and SMTP work.
- [ ] Configure a real mail provider; do not use log mailer in production.
- [ ] Add queueing, retry, dead-letter handling, delivery idempotency and one-click unsubscribe where applicable.
- [ ] Test scheduler under concurrent workers and delivery failures.
- [ ] Confirm email frequency, opt-out, verification and matching logic.

## 5. Accessibility and UI
- [ ] Test at 320px, 375px, 768px, 1024px and desktop widths.
- [ ] Keyboard-only: tab order, skip link, focus visible, menus, dialogs and form errors.
- [ ] Screen reader: NVDA + Firefox/Chrome and VoiceOver + Safari.
- [ ] Check 200%/400% zoom, contrast, text spacing, reduced motion and 44px targets where practical.
- [ ] Add human-reviewed ISL explanations with accurate captions and text transcripts.
- [ ] Complete manual WCAG 2.2 AA audit with Deaf participants.

## 6. Production operations
- [ ] Set APP_ENV=production, APP_DEBUG=false and HTTPS-only APP_URL.
- [ ] Configure secure session cookies, trusted proxies and correct CORS/CSRF.
- [ ] Back up database and uploaded resumes; perform a test restore.
- [ ] Use php artisan migrate --force only after reviewing migrations and backups.
- [ ] Set up cron, queue worker, monitoring, alerting and uptime checks.
- [ ] Confirm writable storage, log rotation and least-privilege database credentials.
- [ ] Verify sitemap, robots, SEO and private-page cache headers.
- [ ] Run a full regression smoke test, get release approval and document rollback.

## Release decision
Do NOT deploy or mark complete until all blocking items are verified.
