# DeafJobsIndia Stage 4 — Job Listings

## Stack
Laravel 13 + MySQL + Livewire + Flux UI + Tailwind CSS v4, no starter kit.

## Schema
- employers: identity, website, description, verification flag
- job_listings: employer, slug, title, location, state, sector, employment type, work mode, Deaf-friendly/PwD flags, salary text, content, original source, external application URL, deadline, publishing state

The Stage 1 articles table remains independent; no automatic conversion of news articles into verified job listings.

## Routes
GET /jobs — job discovery with Livewire search
GET /jobs/{slug} — public job details; published only

## Filters
Keyword, location, sector, employment type, work mode, reported Deaf-friendly, reported PwD eligibility, open jobs only, newest/closing soon, pagination and clear filters.

## Publication policy
Only status=published with published_at <= now are visible. The listing page defaults to open deadlines. Closed listings remain viewable at their detail URLs but have no apply button. External links require HTTPS. Labels are explicitly described as reported, not guaranteed.

## Setup
git fetch origin
git switch stage-4/job-listings
composer install
npm install
# Configure .env MySQL first
php artisan migrate
npm run build
php artisan test
php artisan serve

Open http://127.0.0.1:8000/jobs

## Data entry
Stage 4 does not include a job administration UI or fabricated job postings. Add legitimate employer/job records using a controlled seeder or Stage 5 admin CRUD after verifying the official source.

## QA
Test at 320, 375, 768, 1024 and 1440 px widths. Confirm keyboard focus, filter resets, result counts, closed deadlines, publication gating, safe external URLs and database indexes.

## Next
Stage 5: authenticated administrator/employer management, validated forms, review and publishing workflows, authorization, and audit history.
