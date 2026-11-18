# Stage 23 — Integration audit and stabilization

Status: draft, changes committed, tests not executed by assistant.

## Changes
- Prevent accepting new internal applications if an employer is no longer verified.
- Require a future interview date when an employer marks an application as interview.
- Keep existing alert ownership and response behavior unchanged to avoid breaking Stage 16/20 tests.
- Add regression tests for employer de-verification and incomplete interview scheduling.

## Audit findings requiring follow-up
1. Job alert delivery sends email before writing the delivery ledger. Two concurrent workers may send duplicates. Keep the schedule disabled pending an idempotent delivery design.
2. Email verification is checked by the alert command but registration does not currently complete a verification workflow. The mail feature is not production-ready.
3. Job seeker application duplicate checking is application-level before insert. The database unique constraint protects data, but a race may produce an exception rather than a friendly error.
4. Recruiter status updates lack a history/audit log, transition policy, timezone normalization and applicant notification.
5. Private JSON export builds a full in-memory payload. Large exports require streaming and a resource limit.
6. Existing PRs 15–22 are dependent draft branches. Review and merge in dependency order after CI and browser QA.
7. User-facing ISL materials, caption quality and WCAG 2.2 AA verification remain outstanding.

## Local verification
```powershell
git fetch origin
git switch --track origin/feature/stage-23-integration-stabilization
php artisan test --filter=StageTwentyThreeIntegrationTest
php artisan test
php artisan route:list
npm ci
npm run build
php artisan jobs:send-alerts --dry-run
```

Do not enable the email scheduler or deploy until the release blockers are resolved.
