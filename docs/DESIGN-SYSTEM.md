# DeafJobsIndia Stage 2 — Black & White Design System

## Architecture
Laravel 13 + Blade components + Tailwind CSS v4 + Livewire + Flux UI. MySQL is unchanged from Stage 1. No starter kit.

## Tokens
Ink #111111; muted #525252; border #E5E5E5; surface #FAFAFA; paper #FFFFFF. Spacing follows Tailwind's 4px scale. Radius .75rem. Container 72rem. Typeface Arial/system sans (Google Sans can be substituted only when a licensed font is available).

## Components
- components/layouts/site.blade.php: semantic HTML shell, Vite assets, Livewire/Flux scripts
- components/site/header.blade.php: desktop and mobile navigation
- components/site/footer.blade.php: legal and site navigation
- components/site/nav-link.blade.php: active route indicator with aria-current
- components/ui/button.blade.php: primary, secondary, ghost button/link
- components/ui/badge.blade.php: neutral, outline, strong status labels
- components/ui/card.blade.php: reusable content surface
- components/ui/section-heading.blade.php: heading and description
- components/ui/empty-state.blade.php: no-results presentation
- components/article-card.blade.php: editorial card composed from primitives
- pages/design-system.blade.php: local-only component preview

## Accessibility
- Semantic header/main/footer and labeled nav landmarks
- Keyboard-visible focus outlines, skip link, minimum 44px button height
- Text labels for statuses; no color-only meaning
- Responsive mobile navigation without JavaScript
- Reduced motion preference respected
- Real-time search stays in Livewire and form controls use Flux UI

## Verify
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan test
php artisan serve

Visit /design-system only in APP_ENV=local. The route intentionally returns 404 in production. Never deploy the preview publicly.

## Stage 3 backlog
Accessible employer and candidate workflows, validated contact form, editorial admin dashboard, SEO parity, privacy and security review. Do not enable registration or collect applicant information before those are ready.
