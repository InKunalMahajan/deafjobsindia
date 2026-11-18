# DeafJobsIndia — Stage 12 Release Readiness Checklist

Status: **BLOCKED — source audit only; no tests executed or deployment approved.**

Branch: `feature/stage-12b-release-quality-audit`  
Baseline: `feature/stage-12-release-readiness-audit`

## Scope and preservation rules

- Laravel 13, Blade, Livewire, Flux UI, Tailwind CSS v4, MySQL.
- Preserve existing job listings, employers, articles, accounts, URLs and role permissions.
- Do not merge untested changes.
- Do not run database migrations against production without a verified backup and a separate maintenance approval.
- Do not run `migrate:fresh`, `db:wipe`, `migrate:reset` or seeders on production.
- Local execution, deployment and accessibility-specific enhancements remain on hold.

## Source review summary

1. Dependencies: `composer.json` requires PHP ^8.3, Laravel ^13.17, PHPUnit ^12.5, Livewire ^3.7 || ^4.0, Flux ^2.0. `package.json` requires Vite ^8 and Tailwind v4. Exact installed versions and lockfile resolution have **not** been verified.
2. Tests: `phpunit.xml` selects SQLite in-memory, array sessions/cache and sync queues. Tests are written but **not executed**.
3. Schema: Stage 06 migration `2026_11_18_000003_add_job_discovery_fields.php` adds four nullable salary/experience columns and indexes. Its `down()` removes those columns; rolling back after values are entered will lose them.
4. Admin: Authentication and route-level gates exist. Job/article publishing, employer verification, application guidance review and user administration have separate permissions.
5. Public: Published scopes protect drafts and scheduled content. Expired jobs are excluded from active listings/sitemap. SEO includes dynamic robots, sitemap and structured data with eligibility limits.
6. Privacy: Employer contact email is intended to remain private. Admin pages receive no-store cache headers. Source URLs require HTTPS in admin validation.
7. Assets: The repository declares Vite and Flux. Built assets and browser rendering have **not** been verified.

## Future verification sequence (not executed)

1. Review exact dependency locks and PHP extension requirements. Resolve dependency conflicts on a disposable development environment.
2. Run `composer validate --strict` and inspect the resolved dependency tree.
3. Run `php artisan route:list` and `php artisan about` after dependencies are installed.
4. On an isolated SQLite testing database only, run `php artisan test`; inspect all Stage 06–11 feature tests and repair failures.
5. Run `npm ci` and `npm run build` only in a development/test environment.
6. Run a migration rehearsal against a disposable copy of the schema; verify columns, indexes, null legacy values, and rollback consequences. Do not roll back production data.
7. Check public and admin navigation, forms, permissions, redirects, CSRF, pagination, Markdown, links, sitemap XML, robots and JSON-LD.
8. Verify responsive black-and-white pages and the existing design system in a browser.
9. Verify production settings without exposing secrets: `APP_ENV=production`, `APP_DEBUG=false`, HTTPS `APP_URL`, correct `APP_KEY`, database credentials, secure sessions, queue/cache drivers, mail transport and storage permissions.
10. Confirm a tested backup and restoration plan, maintenance window, rollback/forward-fix plan, monitoring and responsible approver.
11. Only after the above checks pass, request explicit merge and deployment approval.

## Release gates

| Gate | Current status |
| --- | --- |
| Source audit | Reviewed; not exhaustive |
| Composer / npm resolution | Not verified |
| PHP / PHPUnit | Not run |
| Migrations on isolated copy | Not run |
| Browser / UI | On hold |
| Accessibility-specific work | On hold |
| Production secrets and HTTPS | Not verified |
| Backup / restore rehearsal | Not verified |
| Merge | Blocked |
| Deployment | Blocked |

**No release approval is implied by this document.**

## Stage 12B quality audit (source only)

### Confirmed changes

- User administration: make the self-demotion guard explicitly apply to a Super Admin editing their own account. Existing role gates are unchanged.
- Add four tests in `tests/Feature/StageTwelveBReleaseAuditTest.php` for self-demotion, allowed self-edit, editor authorization and guest-only login. Tests **not run**.
- No migrations, dependency changes, record updates, route changes or deployment.

### Remaining blockers and checks

- **Dependencies:** `composer.json` declares Laravel ^13.17, PHP ^8.3 and PHPUnit ^12.5; `package.json` declares Vite ^8, Tailwind v4 and Livewire/Flux integration. Exact package compatibility, PHP extensions, `composer.lock` and `package-lock.json` availability remain unverified; do not assume `npm ci` can work without a valid lockfile.
- **Migration safety:** Stage 06 discovery migration adds nullable salary and experience columns. Its rollback removes these columns and would erase populated values. Do not use rollback as the production recovery plan; rehearse a forward fix with a backup.
- **Test portability:** SQLite in-memory tests are configured in `phpunit.xml`. MySQL-specific schema behavior is not established by those tests alone. Confirm both SQLite tests and a separate disposable MySQL migration rehearsal when execution is authorized.
- **Authorization:** Route gates protect admin resources. Validate user-role permissions, editor publication restrictions, employer verification, review metadata and CSRF through tests before release.
- **Public routes/SEO:** Verify home, jobs, employers, articles, RSS, sitemap, robots and canonical/JSON-LD output after dependency installation. Dynamic content must not expose draft, future, demo or expired records contrary to visibility rules.
- **Production environment:** `.env.example` is a local-development template, **not** production configuration. Check `APP_DEBUG=false`, HTTPS `APP_URL`, strong `APP_KEY`, secure cookie/session settings, storage permissions, queues, mail, logging and restricted DB credentials in an approved environment. Never commit credentials.
- **Operational safeguards:** Backups, restoration drill, release owner, maintenance window, monitoring, failure recovery, dependency locking and successful regression tests are all outstanding.

### Explicit release decision

**NO-GO**. Source changes are committed but not executed or verified. Keep merge, local execution, deployment and accessibility-specific enhancements on hold. No release approval is implied.
