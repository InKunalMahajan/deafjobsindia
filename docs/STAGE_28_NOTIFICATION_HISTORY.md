# Stage 28 — Notification history and secure opt-out

Status: draft; tests not executed by assistant.

- Job seeker alerts page displays paginated outbox delivery history scoped to the authenticated user.
- Email digests contain a signed, expiring unsubscribe confirmation link.
- Confirmation requires an explicit POST with a fresh 15-minute signed URL; link previews do not unsubscribe.
- Opt-out disables only the specified saved alert and cancels its pending/failed outbox rows.
- Tests cover history privacy, signed opt-out isolation and unsigned request rejection.

## Verify
```powershell
git fetch origin
git switch --track origin/feature/stage-28-alert-history
php artisan migrate
php artisan test --filter=StageTwentyEightNotificationTest
php artisan test
php artisan route:list --path=alerts
npm run build
```

## Remaining work
- No administrative queue monitoring UI, automated queue workers, one-click RFC 8058 unsubscribe headers or provider webhook reconciliation.
- The signed link can be forwarded; whoever possesses it can unsubscribe that alert until expiration. It does not expose personal history.
- Email dispatch can still duplicate after SMTP acceptance and before DB commit; exactly-once delivery is not guaranteed.
- Keep `JOB_ALERTS_SCHEDULE_ENABLED=false` and `JOB_ALERT_DELIVERY_ENABLED=false` until tests and production email readiness pass.
