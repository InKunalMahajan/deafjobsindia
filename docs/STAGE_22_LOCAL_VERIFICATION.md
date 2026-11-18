# Local verification (Windows 11 PowerShell)

Use this branch only after saving any local work.

```powershell
git fetch origin
git switch --track origin/feature/stage-22-release-hardening
composer validate --strict
composer audit
php artisan migrate:status
php artisan route:list
php artisan test --filter=StageTwentyTwoSecurityTest
php artisan test
npm ci
npm run build
php artisan jobs:send-alerts --dry-run
```

If the branch exists locally, use `git switch feature/stage-22-release-hardening`.
Never run `migrate:fresh`, `db:wipe` or delete your production data.
Do not enable the job alert schedule or use a production mail provider until privacy and email verification gates are met.
