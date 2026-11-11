# Stage 7 — Accessibility, SEO, Performance and Security

## Windows commands
git fetch origin
git switch -c stage-7/accessibility-seo-security --track origin/stage-7/accessibility-seo-security
composer install
npm install
php artisan migrate
npm run build
php artisan test --filter=StageSevenTest
php artisan test
php artisan serve

## Delivered
Canonical and Open Graph metadata; noindex on admin/search; dynamic sitemap of published articles, open published jobs and verified employers; robots rules; HTTP security headers; focus visibility and reduced motion; feature tests.

## Manual WCAG 2.2 AA QA
Use keyboard-only navigation and NVDA screen reader; verify headings, error feedback, labels and Livewire updates. Test zoom 200% and 400%, widths 320–1440px, focus visibility, contrast, captions, transcripts and ISL-supported materials. Automated tests do not establish WCAG conformance.

## Performance QA
Run Chrome Lighthouse on home/jobs/articles/job details. Record LCP, INP, CLS, TTFB and bundle size. Use npm run build and Laravel caches only after verifying production configuration. No performance score is claimed without measurement.

## Security release gate
Require APP_DEBUG=false, HTTPS, secure cookies, backup/restore tests, dependency audits, authorization tests, MFA/password reset, audit logging, CSRF checks, CSP report-only evaluation and independent security review. HSTS is set only for HTTPS production responses; no includeSubDomains/preload without domain verification.