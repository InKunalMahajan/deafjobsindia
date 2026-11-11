# Stage 9 — Final UI/UX and Accessibility QA

## Source review
- Homepage job search targets /jobs, not news.
- Navigation includes desktop and mobile labels.
- Skip link and visible focus indicators exist.
- Reduced-motion CSS exists.
- Mobile menu width and minimum summary tap target improved.
- Footer links gain hover underlines.

## Blocking before public launch
1. Contact page has no working contact channel. Configure a real monitored email address or validated contact form before release; never invent an address.
2. Privacy page is a placeholder. Replace with a legally reviewed policy reflecting actual processing, cookies, logs, admin accounts and retention.
3. Verify keyboard and screen-reader behavior in a real browser; code review is not WCAG certification.
4. Record Lighthouse mobile/desktop results and test at 320, 375, 768 and 1280 px.

## Manual keyboard test
- At /, press Tab. Skip to main content must appear.
- Press Enter. Focus must move to the main region.
- At 320 px, open Menu with Enter and Space; navigate links with Tab.
- Close menu with Enter/Space on summary. Check no clipped links or horizontal overflow.
- Test 200% and 400% zoom; text remains usable.
- Test reduced-motion preference.

## Assistive technology and content
- Test NVDA with Chrome or Firefox: landmarks, headings, search form label, jobs cards, mobile menu.
- Test Deaf users with varied reading proficiency; use plain English, visual steps and transcripts/captions for future videos.
- Verify real source links, employer verification wording, expiry dates and application instructions.

## Commands
php artisan test
npm run build
php artisan route:list
composer audit
npm audit --audit-level=high

Do not merge or deploy until the blockers and manual checks are resolved.
