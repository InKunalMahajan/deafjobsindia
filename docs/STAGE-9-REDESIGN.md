# Stage 9 — Professional Website Redesign and Content Architecture
## Scope
- Rebuild homepage around jobs and official-source applications
- Homepage search submits to jobs with q parameter, not article search
- Promote verified employer directory in header/footer and homepage
- Reduce top-level navigation and group training/education/career resources
- Explain job application workflow and editorial limits
- Improve About and clarify dedicated news search
- Preserve minimal monochrome design, Tailwind CSS v4, Livewire, Flux UI, Laravel 13
## Verification (Windows PowerShell)
git fetch origin
git switch -c stage-9/professional-redesign --track origin/stage-9/professional-redesign
composer install
npm ci
php artisan migrate
npm run build
php artisan test
php artisan route:list
php artisan serve
## Manual QA
1. Home job search 'Accountant' must open /jobs?q=Accountant and prefill Livewire keyword.
2. Home shows only published open job listings; verify empty state with zero jobs.
3. Header and footer employer links open verified employer directory.
4. Mobile navigation at 320px and 375px; keyboard and screen reader navigation.
5. Articles search /search is explicitly labeled for news and resources.
6. Verify published/draft content boundaries, deadlines and source links.
7. Review legal privacy copy and provide an actual contact channel before public launch.
## Release gate
No production deployment or merge. Obtain visual QA, accessibility QA and CI approval first.
