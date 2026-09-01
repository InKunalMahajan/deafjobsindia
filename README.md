# DeafJobsIndia — 

DeafJobsIndia is a custom Astro + Tailwind CSS digital newsroom for Deaf India.

## Current design

Main palette:

- Purple
- White
- Light Gray
- Black

Category colors are used only as small visual accents.

Brand tagline:

**Connecting Deaf Talent with Opportunities**

## Main sections

- Latest News
- Jobs
- Education
- Government Schemes
- Training
- Job Fairs
- Community
- Technology
- Career

## Technology

- Astro
- Tailwind CSS
- Astro Content Collections
- Markdown newsroom content
- static production output
- GitHub Actions
- Hostinger static hosting
- secure SSH/rsync deployment

No downloaded/default Astro theme is used.

## Local development

Requires Node.js 22.12+.

```bash
npm install
npm run dev
```

## Newsroom commands

Create a story:

```bash
npm run new:post -- "Headline" "Jobs"
```

Check newsroom status:

```bash
npm run newsroom:status
```

Publish:

```bash
npm run post:publish -- "story-slug"
```

Schedule:

```bash
npm run post:schedule -- "story-slug" "2026-09-05T09:00:00+05:30"
```

Audit:

```bash
npm run content:audit
```

## Production build

```bash
npm run content:audit
npm run build
npm run verify:dist
```

Production output is created in:

```text
dist/
```

## Automatic Hostinger deployment

Step 14 adds:

```text
.github/workflows/deploy-hostinger.yml
```

On a push to `main`:

```text
GitHub
  ↓
Content audit
  ↓
Astro build
  ↓
dist verification
  ↓
SSH / rsync
  ↓
Hostinger public_html
  ↓
Live-site verification
```

Scheduled stories are checked hourly and deployed when due.

## One-time setup

Read:

```text
docs/HOSTINGER-AUTO-DEPLOY.md
docs/STEP-14-FIRST-DEPLOY-CHECKLIST.md
docs/GITHUB-SETUP.md
```

## Editorial workflow

Read:

```text
docs/EDITORIAL-WORKFLOW.md
docs/NEWSROOM-CHECKLIST.md
```

## Important

Never commit:

- Hostinger passwords
- private SSH keys
- GitHub Actions secrets
- API credentials
