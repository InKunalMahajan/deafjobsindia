# Stage 3 — Responsive public pages

Stack: Laravel 13, Tailwind CSS v4, MySQL, Livewire and Flux UI; no starter kit.

## Implemented
Responsive sticky header with desktop and mobile navigation; three-column footer; homepage hero, GET search, resource cards and published article cards; consistent jobs, search and latest pages; education, training and career category routes.

## Run
```bash
git checkout stage-3/public-pages
composer install
npm install
cp .env.example .env
php artisan key:generate
# Configure MySQL in .env
php artisan migrate
php artisan news:import
npm run build
php artisan test
php artisan serve
```

Visit http://127.0.0.1:8000. Check 320px, 375px, 768px, 1024px and 1440px widths, keyboard navigation, mobile menu, article links and search.

Contact and privacy pages are placeholders; do not collect applicant data yet. Keep the migration and design-system PRs unmerged until staging validation and PHP hosting are ready.
