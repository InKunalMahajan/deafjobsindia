# Step 14 First Deployment Checklist

Complete these once.

## Hostinger
- [ ] DeafJobsIndia website/domain exists in Hostinger.
- [ ] SSH access is enabled.
- [ ] Host/IP copied from hPanel.
- [ ] SSH username copied from hPanel.
- [ ] SSH port confirmed.
- [ ] Dedicated deployment SSH public key added.
- [ ] Exact DeafJobsIndia `public_html` path confirmed.

## GitHub secrets
- [ ] `HOSTINGER_HOST`
- [ ] `HOSTINGER_USERNAME`
- [ ] `HOSTINGER_SSH_PRIVATE_KEY`
- [ ] `HOSTINGER_REMOTE_PATH`
- [ ] `HOSTINGER_KNOWN_HOSTS` (recommended)

## GitHub variables
- [ ] `SITE_URL`
- [ ] `HOSTINGER_PORT`

## Repository
- [ ] Project pushed to `main`.
- [ ] CI passes.
- [ ] `Deploy DeafJobsIndia to Hostinger` workflow passes.
- [ ] Website opens over HTTPS.
- [ ] Homepage displays correctly.
- [ ] Jobs page opens.
- [ ] Education page opens.
- [ ] One news article opens.
- [ ] Search page opens.
- [ ] `robots.txt` opens.
- [ ] `sitemap-index.xml` opens.
- [ ] RSS feed opens.

## Mobile/accessibility
- [ ] Mobile navigation works.
- [ ] Search opens and closes.
- [ ] Keyboard focus is visible.
- [ ] Skip-to-content works.
- [ ] Images have useful alt text.
- [ ] Body text is readable at normal zoom.

## Newsroom
- [ ] `npm run newsroom:status`
- [ ] `npm run content:audit`
- [ ] One test draft created.
- [ ] Draft does not appear publicly.
- [ ] Source verification workflow understood.
- [ ] Scheduled publishing workflow understood.

After all checks pass, Step 14 deployment is ready for normal newsroom use.
