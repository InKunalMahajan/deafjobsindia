# Stage 5 — Administration, RBAC and CRUD

## Setup (Windows PowerShell)
git fetch origin
git switch -c stage-5/admin-rbac-crud --track origin/stage-5/admin-rbac-crud
composer install
npm install
php artisan migrate
php artisan admin:create
npm run build
php artisan test
php artisan serve

Open http://127.0.0.1:8000/admin/login.

## Roles
- super_admin: full access, including user management
- admin: employers and jobs CRUD and publication
- editor: create and edit draft jobs; cannot edit published jobs, publish or delete
- viewer: dashboard only

## Workflow
1. Create the first Super Admin interactively using php artisan admin:create.
2. Super Admin creates team accounts and assigns roles.
3. Admin creates employer records and manually reviews verification evidence.
4. Admin/Editor creates draft jobs with original source and application URL.
5. Admin checks the source and publishes the listing.

No public registration, file uploads or automatic job ingestion. Authentication uses Laravel sessions and CSRF. Login is throttled to 5 requests per minute; session regenerates on login and invalidates on logout.

## Tests
php artisan test --filter=AdminManagementTest
php artisan test
npm run build
php artisan route:list --path=admin

## Production requirements
Before public deployment, implement MFA, password reset, email verification, immutable audit logs, approval history, soft deletion, monitoring and a security review. Configure HTTPS, secure session cookies and database backups.
