# DeafJobsIndia Stage 30 — Integration, security and release stabilization

## Audit status
Source-based review of selected integration paths on branch `feature/stage-30-integration-security-stabilization`. **Not a full security penetration test or completed release certification.** PHP tests, browser tests and deployment checks have not been executed by the assistant.

## Confirmed fixes
1. Alert staging now excludes jobs already reserved by pending, failed, exhausted or sent outbox batches. This reduces overlapping digest duplication before the delivery ledger is committed.
2. Signed unsubscribe now cancels exhausted outbox batches in addition to pending and failed.
3. Admin retry requires a verified job-seeker recipient and a still-active opted-in alert.
4. Added tests for unsubscribe cancellation, admin retry authorization, verified recipient eligibility and disabled delivery.

## Audit matrix and required verification
| Area | Review | Remaining validation |
| --- | --- | --- |
| Public jobs/news | Routes and shared layout reviewed in earlier UI audit | Browser navigation, search filters, pagination, broken links |
| Job seeker | Alerts/history/opt-out code reviewed | Signup, Google auth, profile, resume privacy, applications |
| Employer | Auth/login UI reviewed | Employer ownership, verification revocation, recruiter actions |
| Admin | Notification monitoring, RBAC gates reviewed | All CRUD permissions, role isolation, CSRF, retries |
| Auth | Password reset and verification paths from earlier stages | Signed link expiry, reset throttling, session revocation, SMTP |
| Email | Staging, outbox, retry and opt-out reviewed | Worker crash, SMTP acceptance ambiguity, shared locks |
| UI/UX | Targeted source review in previous branch | Real devices, zoom, keyboard, screen reader, contrast |
| Deployment | Disabled delivery defaults confirmed | HTTPS, secrets, mail DNS, DB backup, scheduler, rollback |

## Production blockers
- Earlier stages are a chain of dependent **draft** PRs. Merge and deployment sequencing has not been validated.
- Exactly-once email delivery is not guaranteed: SMTP may accept a message before a process crashes.
- No evidence of a full green test suite, successful migration on production-like MySQL, or successful browser accessibility checks.
- Real SMTP readiness remains incomplete. Keep `JOB_ALERTS_SCHEDULE_ENABLED=false` and `JOB_ALERT_DELIVERY_ENABLED=false`.
- No security scan, dependency audit, automated browser suite, load test, backup restore drill, or staging smoke test has been completed.

## Windows verification
```powershell
git fetch origin
git switch --track origin/feature/stage-30-integration-security-stabilization
composer install
php artisan migrate
php artisan optimize:clear
php artisan test --filter=StageThirtyIntegrationSecurityTest
php artisan test
php artisan route:list
composer audit
npm audit
npm run build
php artisan mail:readiness
php artisan jobs:send-alerts --dry-run
php artisan jobs:deliver-alert-outbox --dry-run
```
Use `git switch feature/stage-30-integration-security-stabilization` if the branch exists locally. Do not run `migrate:fresh` on data you need to keep.

## Release gate
Release remains **NO-GO** until migrations, full automated tests, manual browser/security checks, mail readiness, dependency audits and rollback rehearsal are verified.
