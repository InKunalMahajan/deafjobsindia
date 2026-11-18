# DeafJobsIndia — Stage 13 Pre-Release Verification Plan

**Status: PLAN ONLY — NO-GO.** No commands, tests, migrations, merges or deployments have been executed.

**Source branch:** `feature/stage-12b-release-quality-audit`  
**Planning branch:** `feature/stage-13-pre-release-verification-plan`

## Rules

- Preserve all jobs, employers, articles, accounts, permissions, routes and existing content.
- Use a separate disposable development/test environment and database **only after explicit approval**.
- Never run `migrate:fresh`, `db:wipe`, `migrate:reset`, `db:seed` or destructive rollback on production.
- Keep local execution, deployment and accessibility-specific enhancements on hold.
- Do not merge untested changes.

## Verification gates — in order

| Gate | Future action (not executed) | Pass condition | Failure action |
| --- | --- | --- | --- |
| 0. Source freeze | Record branch SHA and changes since Stage 05C | Reviewed diff, no unintended destructive change | Investigate and amend isolated branch |
| 1. Environment isolation | Confirm test database, secrets and filesystem separate from production | No production credentials or live data writes | STOP |
| 2. Dependency locks | Confirm composer.lock and package-lock.json are present and consistent; inspect PHP, Node and extensions | Resolved versions compatible with Laravel 13, PHPUnit, Livewire, Flux, Vite and Tailwind | STOP; repair lockfiles on isolated branch |
| 3. Static checks | `composer validate --strict`, PHP lint, Pint check, JS build checks | No syntax/configuration errors | Fix and repeat |
| 4. Route audit | `php artisan route:list` and route-name comparisons | No missing/duplicate expected routes; auth gates intact | Fix and repeat |
| 5. PHPUnit | `php artisan test` with SQLite :memory: configured in phpunit.xml | All tests pass; no skips without explanation | Fix and repeat |
| 6. MySQL rehearsal | On a disposable MySQL database, apply all migrations and test legacy/null values | Schema, indexes and records retained | STOP; prepare forward-fix, never destructive production rollback |
| 7. Assets | `npm ci` only with valid lockfile; `npm run build` | Clean build and Vite manifest | Fix and repeat |
| 8. Functional verification | Check public pages, Livewire filters, admin forms, redirects, CSRF and roles | No broken workflows or privilege escalation | Fix and repeat |
| 9. SEO/privacy | Inspect sitemap XML, robots, canonical, JobPosting/Article JSON-LD, noindex, external URLs, Markdown and private caching | Only eligible public records exposed; no private data leaks | Fix and repeat |
| 10. Operations | Review HTTPS, APP_DEBUG=false, APP_KEY, sessions, queues, logs, backup/restore and monitoring | Approved production configuration and successful restoration rehearsal | STOP |
| 11. Release authorization | Obtain explicit approval after all evidence is recorded | All gates pass; reviewer signs off | NO-GO |

## Evidence to record later

For each gate record: timestamp, commit SHA, environment (no secrets), command/check, exit code, test counts, issue links, reviewer and pass/fail decision. Do not claim a pass without evidence.

## Known blockers and special attention

1. `composer.json` requires PHP ^8.3, Laravel ^13.17 and PHPUnit ^12.5; `package.json` requires Vite ^8 and Tailwind v4. Actual resolution is not verified.
2. Confirm lockfiles exist before running `composer install` or `npm ci`. Do not substitute dependency updates without reviewing the resulting diff.
3. PHPUnit uses in-memory SQLite, which cannot replace MySQL migration validation.
4. Stage 06 migration adds four nullable job discovery fields; its `down()` drops those columns. If populated, rollback loses data. Prefer backup and forward repair.
5. Stage 10B remote JobPosting markup is intentionally withheld when eligibility cannot be verified. Confirm structured-data correctness without inventing eligibility.
6. Admin role and login regression tests from Stages 11–12B are written but not run.
7. Production secrets, HTTPS, backup recovery, build output, browser behavior and deployment readiness are unknown.

## Explicit stop conditions

Stop immediately if a command points to production, a test database contains live records, a migration would remove data, a required permission fails, a private record becomes public, a dependency cannot resolve, a test fails, or a backup cannot be restored.

## Decision

**NO-GO.** This document defines acceptance criteria only. All execution and deployment remain on hold pending a new explicit instruction.
