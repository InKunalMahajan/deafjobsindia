# Step 15 — DeafJobsIndia Production Launch

Production domain:

`https://deafjobsindia.in`

## Automated launch checks

Every CI build and GitHub Pages deployment now runs:

```bash
npm run content:audit
npm run build
npm run verify:dist
npm run launch:audit
```

The launch audit checks the generated production site for:

- correct `https://deafjobsindia.in` canonical URL
- Open Graph metadata
- Twitter card metadata
- structured data
- robots meta tag
- `robots.txt`
- sitemap URL
- RSS feed
- 404 page
- favicon
- web manifest
- GitHub Pages `CNAME`
- accidental old `.com` production URLs

## Google Search Console

Recommended property:

`https://deafjobsindia.in/`

After adding the site in Search Console, choose HTML tag verification if you want to use the built-in site hook.

Copy only the token from Google's verification tag and create this GitHub repository variable:

`PUBLIC_GOOGLE_SITE_VERIFICATION`

Example structure only:

`abc123...`

Do not paste the complete `<meta>` element into the variable.

The site automatically outputs:

```html
<meta name="google-site-verification" content="..." />
```

After GitHub Pages deploys, return to Search Console and complete verification.

Then submit:

`https://deafjobsindia.in/sitemap-index.xml`

## Google Analytics 4

Create a GA4 web data stream for:

`https://deafjobsindia.in`

Copy the Measurement ID, normally formatted like:

`G-XXXXXXXXXX`

Create this GitHub repository variable:

`PUBLIC_GA_MEASUREMENT_ID`

The site loads Google Analytics only when this variable is configured. No placeholder analytics ID is committed to the repository.

## Where to add GitHub variables

Repository → Settings → Secrets and variables → Actions → Variables

Add:

- `PUBLIC_GOOGLE_SITE_VERIFICATION`
- `PUBLIC_GA_MEASUREMENT_ID`

Neither value is treated as a private secret because both are visible in the public site's HTML/network requests.

## Search engine launch checklist

- [ ] `https://deafjobsindia.in` opens over HTTPS
- [ ] GitHub Pages DNS check is successful
- [ ] Enforce HTTPS is enabled
- [ ] `https://deafjobsindia.in/robots.txt` opens
- [ ] `https://deafjobsindia.in/sitemap-index.xml` opens
- [ ] `https://deafjobsindia.in/rss.xml` opens
- [ ] Search Console property is verified
- [ ] Sitemap is submitted in Search Console
- [ ] GA4 web stream is connected
- [ ] GA4 Realtime registers a test visit
- [ ] Homepage canonical uses `https://deafjobsindia.in/`
- [ ] One article's canonical URL is correct
- [ ] One article's social-share metadata is correct

## Editorial launch checklist

- [ ] Replace demo stories before public promotion
- [ ] Verify every real article source
- [ ] Add accurate image alt text
- [ ] Confirm deadlines and application links
- [ ] Confirm accessibility claims instead of assuming them
- [ ] Add contact email and real social profile URLs
- [ ] Review About, Contact, Privacy and Disclaimer pages

## Normal publishing workflow

```text
Write / verify story
      ↓
npm run post:publish
      ↓
git commit + push
      ↓
CI + launch audit
      ↓
GitHub Pages deploy
      ↓
deafjobsindia.in updated
```
