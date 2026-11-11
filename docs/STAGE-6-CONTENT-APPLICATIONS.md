# Stage 6 — Articles, Employer Records and Application Guidance

## Installation (PowerShell)
git fetch origin
git switch -c stage-6/articles-applications --track origin/stage-6/articles-applications
composer install
npm install
php artisan migrate
npm run build
php artisan test --filter=StageSixTest
php artisan test
php artisan serve

## Routes
- /admin/articles — editor/admin draft content; admin publishes
- /employers — only verified employers
- /employers/{slug} — verified employer profile and published open jobs
- /admin/jobs — application-link review status and guidance
- /jobs/{slug} — external application instructions and HTTPS handoff

## Application workflow
1. Admin creates or verifies an employer record.
2. Editor/Admin drafts a job and includes the official source and HTTPS apply URL.
3. Admin checks the link, recruitment deadline and eligibility, then marks review status verified or needs_update.
4. Admin publishes the job. Applicants read instructions and apply directly on the recruitment website.
5. No CV, personal identification, disability documentation or application status is collected by DeafJobsIndia.

The application review flag is an internal editorial record, not an employer guarantee. Published job links still require independent checking.

## Follow-up scope
A true first-party application management system needs applicant authentication, explicit consent, data retention/deletion, email notifications, anti-spam, audit logs and secure storage. This stage intentionally does not claim to provide those capabilities.

## Verification
php artisan test --filter=StageSixTest
php artisan test
php artisan route:list
npm run build
Manually inspect keyboard navigation, editor/admin restrictions, employer visibility, and link safety.
