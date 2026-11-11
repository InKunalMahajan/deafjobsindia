# DeafJobsIndia Stage 8 — Production Deployment and QA

## Deployment target
Ubuntu Linux server, Nginx + PHP 8.4-FPM, MySQL 8+, HTTPS, Node 24 build, Laravel 13, Livewire/Flux and Tailwind v4. No starter kit.

## 1. Local Windows PowerShell
git fetch origin
git switch -c stage-8/production-qa --track origin/stage-8/production-qa
composer install
npm ci
php artisan migrate
php artisan test
npm run build
composer audit
npm audit --audit-level=high

## 2. Provision server (Ubuntu, privileged setup)
sudo apt update
sudo apt install nginx git unzip curl mysql-client php8.4-fpm php8.4-cli php8.4-mysql php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip
# Package availability depends on Ubuntu release and configured repositories.
# Provision a dedicated deploy user, /var/www/deafjobsindia/releases, shared/storage and shared/.env.
# Keep MySQL private; create least-privileged application database user.
# Build vendor and Vite assets in CI; copy a verified release artifact to the server.

## 3. Environment (shared/.env; NEVER commit)
APP_NAME=DeafJobsIndia
APP_ENV=production
APP_DEBUG=false
APP_URL=https://YOUR_DOMAIN
LOG_CHANNEL=stack
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=deafjobsindia
DB_USERNAME=deafjobsindia_app
DB_PASSWORD=SET_IN_SECRET_MANAGER
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
CACHE_STORE=database
QUEUE_CONNECTION=database

Generate APP_KEY once in a secure environment and preserve it across releases; do not regenerate for an existing installation. Ensure correct permissions for shared/storage and bootstrap/cache. Run migrations on staging first.

## 4. Nginx / TLS
deploy/nginx/deafjobsindia.conf is an INTERNAL backend template bound to 127.0.0.1:8080; do not expose it publicly. Put HTTPS termination in a correctly configured reverse proxy or replace with a proper 443 TLS vhost and certificate. Confirm forwarded HTTPS scheme/trusted proxy configuration before production. Web root MUST point to public/ only.

## 5. Release steps
1. Back up MySQL and shared storage, verify restoration.
2. Require green CI, reviewed PR, security and accessibility QA.
3. Upload versioned release with vendor/ and public/build/manifest.json.
4. Verify .env and storage are shared and writable by PHP-FPM.
5. Run deploy/scripts/preflight.sh on a prepared staging release (requires valid DB).
6. Run deploy/scripts/deploy.sh /var/www/deafjobsindia/releases/RELEASE_ID on production only after approval.
7. Smoke test HTTPS, /up, /jobs, /latest, /employers, /sitemap.xml and /admin/login.
8. Monitor logs, response times and MySQL.

CAUTION: the deploy script is a reference for a prepared single-server release, not an unattended deployment system. It runs database migrations before switching code. Review backward compatibility and use a maintenance window if necessary.

## 6. Rollback
Run deploy/scripts/rollback.sh /var/www/deafjobsindia/releases/PREVIOUS_ID.
This switches code only. It DOES NOT roll back schema/data. Database restoration is a separate, high-risk operation; rehearse in staging.

## 7. QA release checklist
- PHP unit/feature suite and Composer audit green
- npm audit and npm build green
- MySQL staging migration and rollback compatibility tested
- Keyboard navigation, NVDA, WCAG 2.2 AA manual audit
- Captions/transcripts/ISL where applicable
- Lighthouse mobile/desktop results recorded
- 320px, 375px, 768px, 1024px responsive tests
- Admin authentication, roles, CSRF and login rate limits
- Published vs draft data visibility and verified employer controls
- No public CV uploads or private application records
- HTTPS, secure cookies, no debug, security headers, backup/restore drill
- Production release explicitly approved

Do not interpret a passing CI suite as proof of production security or accessibility conformance.
