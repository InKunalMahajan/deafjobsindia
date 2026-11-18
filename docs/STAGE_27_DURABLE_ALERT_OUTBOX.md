# Stage 27 — Durable job alert outbox

Status: DRAFT. Tests have not been executed by assistant.

## Architecture
- `jobs:send-alerts` now **stages** matching alert batches into `job_alert_outbox` instead of sending email directly.
- Each batch stores alert ID, user ID, a deterministic hash of the job IDs, status, attempts, next retry time and error summary.
- `jobs:deliver-alert-outbox` sends staged digests only when `JOB_ALERT_DELIVERY_ENABLED=true` and the account remains verified and opted in.
- Failed sends retry with bounded exponential backoff, up to five attempts. Exhausted rows remain available for manual review.
- Each row has a cache lock to reduce concurrent sends.
- Existing per-job delivery ledger is updated after sending. Status is sent, failed, exhausted or cancelled.

## Safe local development
Keep both flags disabled:
```dotenv
JOB_ALERTS_SCHEDULE_ENABLED=false
JOB_ALERT_DELIVERY_ENABLED=false
MAIL_MAILER=log
```
Use:
```powershell
php artisan migrate
php artisan jobs:send-alerts --dry-run
php artisan jobs:deliver-alert-outbox --dry-run
php artisan test --filter=StageTwentySevenOutboxTest
php artisan test
npm run build
```

## Critical limitations
- SMTP success followed by a crash before ledger commit can still duplicate a message. Exactly-once delivery is not guaranteed without provider idempotency.
- Per-alert staging is deduplicated by current job IDs, but job changes and overlapping batches require additional coverage.
- Delivery is synchronous in the command; Laravel queued jobs, worker monitoring, one-click unsubscribe and notification history UI remain future work.
- Cache locks require a shared atomic lock-capable backend across workers.
- Job alert schedule is still disabled by default. Never enable either flag without a security review, real SMTP/DNS verification and a tested operations runbook.
- The migration must run before the staging command; rolling back the migration while a scheduler is active is unsafe.
