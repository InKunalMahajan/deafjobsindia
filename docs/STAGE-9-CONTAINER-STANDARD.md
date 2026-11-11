# Stage 9 — Consistent container layout

All public pages use the shared .dji-container (maximum 72rem, responsive 1.25rem / 2rem gutters). Reading pages nest .dji-content (maximum 48rem) inside that container, preserving consistent horizontal alignment and comfortable text line length. Admin header and main content use the same .dji-container. Full-width backgrounds stay outside the container.

Content guards: min-width:0 for flex/grid children; article tables and code blocks scroll horizontally; embedded media stays within available width.

Verify in browser at 320px, 375px, 768px, 1280px and 200% zoom. Test all public pages, article/job detail pages, admin tables and forms. Horizontal scrolling should occur only within wide data/code content, never across the entire viewport.

Commands:
php artisan test --filter=StageNineContainerTest
php artisan test
npm run build
