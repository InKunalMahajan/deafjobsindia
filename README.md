# DeafJobsIndia — Laravel migration

DeafJobsIndia is moving from **Astro** to **Laravel 13 + MySQL + Livewire + Flux UI + Tailwind CSS v4**.

This migration branch replaces the static-site code with a PHP application. The original Astro version remains available on the main branch until this pull request is merged. Original Markdown recruitment stories are preserved in src/content/news, ready to import into MySQL.

## Requirements

- PHP 8.3+ and its Laravel extensions (including pdo_mysql and mbstring)
- Composer 2
- Node.js 22.12+ and npm
- MySQL 8+
- No Laravel starter kit required

## Local installation

1. Clone the migration branch and install dependencies:

    git clone --branch migration/laravel-13 https://github.com/InKunalMahajan/deafjobsindia.git
    cd deafjobsindia
    composer install
    npm install

2. Create a MySQL database and a dedicated database user. Use a strong password, and do not commit it.

    CREATE DATABASE deafjobsindia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE USER 'deafjobs'@'127.0.0.1' IDENTIFIED BY 'CHOOSE_A_STRONG_PASSWORD';
    GRANT ALL PRIVILEGES ON deafjobsindia.* TO 'deafjobs'@'127.0.0.1';

3. Configure the environment:

    cp .env.example .env
    php artisan key:generate

   Edit .env with the password and local DB settings. On Windows PowerShell, use Copy-Item .env.example .env.

4. Create schema and import content:

    php artisan migrate
    php artisan news:import --dry-run
    php artisan news:import

5. Build assets and start both development servers:

    npm run build
    php artisan serve

   For live CSS updates, run npm run dev in a second terminal.

Open http://127.0.0.1:8000

## Testing

    php artisan test
    php artisan route:list
    npm run build

Before merging, run composer update and npm install on a networked development machine to create **composer.lock** and **package-lock.json**, then commit both lock files. This migration was authored without package-download access and must be installation-tested.

## Content migration

The original articles are stored as Markdown with YAML front matter in src/content/news/*.md. The repeatable command news:import maps the fields to the articles table and preserves article slugs, official sources, deadlines and publishing status. Demo, draft and article-format examples are intentionally not published.

Existing article URLs use /news/{slug}. Previous images remain under public/images.

## Accessibility and visual style

- Minimal black-and-white Tailwind CSS styles
- Native mobile navigation and visible focus rings
- Text-first recruitment detail pages
- Livewire search with Flux UI form components
- Google Sans is declared as a preferred font but is not bundled; use a properly licensed font file or the Arial/system fallback

## Production warning

**GitHub Pages cannot run Laravel PHP or MySQL**. The old static publishing workflow is removed from this migration branch. Deploy to a PHP 8.3+ host with MySQL and point the web server's document root to the Laravel public directory. Set APP_ENV=production and APP_DEBUG=false, set a production APP_KEY, create database backups, and use a secure deployment process.

This branch is a foundation, not yet a completed employer portal or admin CMS. See docs/LARAVEL-MIGRATION.md for the migration checklist.
