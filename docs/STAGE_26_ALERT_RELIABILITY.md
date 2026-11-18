# Stage 26 — Job alert delivery reliability

Status: draft; tests have not been run by assistant.

## Changes
- The digest command now acquires a per-alert atomic cache lock before checking and sending matches. Concurrent workers using the same shared lock backend will skip a locked alert.
- The command reports failed digests and returns a nonzero exit code if an email or ledger write fails.
- Dry-run remains read-only and does not acquire locks or send emails.
- Added regression coverage: opted-out alerts never send messages.
- Existing authenticated alert settings already provide an opt-out route; emails link to those settings.

## Important limitations
- This is *not exactly-once delivery*. If SMTP accepts an email and the process crashes before recording the ledger, a retry can still duplicate it.
- Lock TTL is 300 seconds; if a send lasts longer, another worker may acquire the lock. Set provider timeouts appropriately before production.
- Atomic locks require a shared cache store (database, Redis, etc.) across all workers. Do not use isolated array/file caches for a multi-server deployment.
- A cache or database outage may stop delivery; treat as a failed run, not a successful send.
- A user must sign in to unsubscribe via the existing settings link. One-click signed unsubscribe, audit log, queued jobs, retry backoff, dead-letter monitoring and delivery statuses are not implemented.
- The scheduler remains disabled: `JOB_ALERTS_SCHEDULE_ENABLED=false`.
- Do not enable real email until SMTP, DNS and Stage 25 checks are completed.

## Windows verification
```powershell
git fetch origin
git switch --track origin/feature/stage-26-alert-reliability
php artisan test --filter=JobAlertEmailTest
php artisan test
php artisan jobs:send-alerts --dry-run
npm run build
```

## Next hardening step
Build a durable outbox with unique delivery IDs, pending/sent/failed state, provider idempotency where supported, queued retries with exponential backoff, and opt-out tokens. Test worker crashes and concurrent runs before enabling scheduled delivery.
