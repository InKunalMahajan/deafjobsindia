# DeafJobsIndia

DeafJobsIndia is a Laravel 13 job and career information platform designed with Deaf and hard-of-hearing accessibility in mind.

## Technology

- Laravel 13, PHP 8.4, Blade, Livewire and Flux UI
- Tailwind CSS v4 and Vite
- MySQL for application data; SQLite can be used for automated tests where supported
- Job-seeker, employer and administrator portals

## Windows 11 local setup

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
```

Set database credentials in `.env`, then:

```powershell
php artisan migrate
npm run build
php artisan serve
```

If `.env` already exists, **do not overwrite it or regenerate the application key**. Existing users and encrypted values may depend on that key.

## Verification

```powershell
php artisan test
composer audit
npm audit
npm run build
php artisan route:list
```

The repository currently has no committed `package-lock.json` or `composer.lock` in the inspected Stage 30 branch. To enable reproducible installs and npm audit, generate and review lockfiles on a development machine, then commit them. Until then, `npm audit` may report `ENOLOCK`. Avoid `npm audit fix --force` without reviewing dependency changes.

## Original news content

`src/content/news/*.md` contains original article source files from the Astro website. **This is content, not an active Astro application.** Laravel still reads these Markdown files using `app/Console/Commands/ImportNews.php`.

Preview the import before writing data:

```powershell
php artisan news:import --dry-run
```

Run `php artisan news:import` only when you intend to import or update articles in the configured database. Do not delete `src/content/news` until all articles, metadata and images have been migrated, backed up and verified. Image assets in `public/images` may still be used by published articles.

## Deployment safety

The recent feature stages and UI/UX audit are maintained in dependent draft pull requests. Do not assume these changes have been merged into `main` or released. Production readiness remains **NO-GO** pending full integration tests, security checks, production email configuration, database backup/restore and browser accessibility verification.

Keep `JOB_ALERTS_SCHEDULE_ENABLED=false` and `JOB_ALERT_DELIVERY_ENABLED=false` until email readiness and operations are verified.

Laravel requires PHP-capable hosting; GitHub Pages cannot serve this application.
