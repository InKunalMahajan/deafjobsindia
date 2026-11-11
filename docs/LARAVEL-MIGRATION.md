# Astro → Laravel 13 migration checklist

## Repository
- Source branch: main (Astro production, preserved)
- Migration branch: migration/laravel-13
- Framework: Laravel 13, Blade, Livewire, Flux UI, Tailwind CSS v4, MySQL
- Styling: strictly monochrome with accessible focus treatments
- Original news files: src/content/news/*.md, retained as import sources
- Original image assets: public/images, retained
- Original Astro deploy-to-GitHub-Pages workflow removed on migration branch

## Content mapping
- title, description, category, body: articles
- status, demo, publishedAt: publication controls
- sourceName, sourceUrl, sourceType, sourceNote: source verification
- verificationStatus, verifiedAt: editorial review metadata
- actionLabel, actionUrl, deadline, location: recruitment detail
- tags, featured, trending, breaking, image, imageAlt: display metadata
- File basename: stable article slug; existing /news/{slug} route is preserved

Run: php artisan news:import --dry-run
Then: php artisan news:import
The import uses updateOrCreate, so it can be rerun without duplicating articles.

## Behaviour safeguards
- Demo and draft articles are not surfaced as published content.
- Recruitment deadlines in the past are visibly marked closed.
- Application buttons are not shown for an expired deadline.
- Official source links retain their original URL.
- Existing Markdown HTML is stripped before rendering; JavaScript URLs are disallowed.
- No user registration, application uploads or form-mail delivery are claimed to work yet.

## Required manual checks before launch
1. Install Composer/npm dependencies on a development machine and commit lock files.
2. Run php artisan test and npm run build with installed dependencies.
3. Import original articles and compare sample article content, images and slugs.
4. Review all old SEO URLs; the initial category aliases currently show broad Jobs.
5. Implement complete search redirects, author/tag archives, canonical metadata and sitemap.
6. Test keyboard navigation, contrast, mobile layouts, captions and screen readers.
7. Re-check government job sources and deadline corrigenda before marking posts as current.
8. Implement and verify real contact submission and privacy policy before accepting personal data.
9. Provision Laravel-compatible PHP hosting and MySQL; do NOT point GitHub Pages at this app.
10. Deploy into a staging URL, confirm backups and rollback, then cut over domain with a monitored release.

## Production deployment concept
- Nginx/Apache document root must point to /path/to/deafjobsindia/public.
- Keep .env, vendor and app files outside the public web root.
- PHP 8.3+; pdo_mysql, mbstring, xml, curl, openssl and other Laravel extensions required.
- Use HTTPS, correct file permissions and APP_DEBUG=false.
- Run composer install --no-dev --optimize-autoloader, npm ci, npm run build.
- Run php artisan migrate --force and php artisan optimize.
- Configure reliable storage permissions, logs, monitoring and backups.

The live site remains on the existing Astro main branch until the migration is reviewed and deployed.
