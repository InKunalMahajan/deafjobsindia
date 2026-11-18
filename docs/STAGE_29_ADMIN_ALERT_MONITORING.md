# Stage 29 — Administrator alert monitoring and retry audit

Status: draft. No tests executed by assistant.

## Added
- Restricted admin monitoring page at `/admin/alert-deliveries` with status counts and paginated records.
- Status filter for pending, failed, exhausted, sent and cancelled.
- Admin-only retry button for failed/exhausted rows. It does not send email.
- Retry is recorded in `alert_delivery_audits` with actor and timestamp.
- Retrying opted-out or disabled alerts is blocked.
- Tests for job-seeker access denial and audit of admin retry.

## Verify on Windows
```powershell
git fetch origin
git switch --track origin/feature/stage-29-admin-alert-monitoring
php artisan migrate
php artisan test --filter=StageTwentyNineAlertMonitoringTest
php artisan test
php artisan route:list --path=admin/alert-deliveries
npm run build
```

## Safety
Keep `JOB_ALERTS_SCHEDULE_ENABLED=false` and `JOB_ALERT_DELIVERY_ENABLED=false`. A queued retry is not a sent email. Tests, SMTP configuration, migration ordering and full role review are required before deployment. Queue worker management and delivery analytics are not implemented.
