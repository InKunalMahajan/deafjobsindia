# Stage 9 layout and accessibility corrections

## Updated
- Public listing/search page titles now use h1; section-heading retains h2 by default for homepage sections.
- About and Partnerships pages link to the new contact form.
- Article content uses the shared .dji-prose styling with responsive table overflow handling.
- News search placeholder reflects article search.
- Admin job review and edit controls identify the relevant job in their accessible names.
- Added regression tests for headings, contact navigation and privacy availability.

## Manual QA still required
Test public and admin pages at 320px, 375px, 768px, and 1280px, keyboard-only, 200% zoom, and NVDA. In particular verify table overflow, admin controls, and screen-reader announcements.

## Commands
php artisan test --filter=StageNineLayoutAuditTest
php artisan test
npm run build

This source-level patch is not a visual browser test or WCAG certification.
