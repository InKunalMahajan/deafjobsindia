# Security and Performance — Hostinger

DeafJobsIndia is built as a static Astro website.

## Hostinger response configuration

Step 14 uses:

```text
public/.htaccess
```

Astro copies this into:

```text
dist/.htaccess
```

GitHub Actions then deploys it to Hostinger `public_html`.

The file currently provides:

- directory listing protection
- custom 404 handling
- `X-Content-Type-Options`
- `X-Frame-Options`
- `Referrer-Policy`
- `Permissions-Policy`
- static-asset caching
- gzip/deflate compression where the server module is available

## HTTPS

Keep Hostinger SSL enabled for the production domain.

The public site should use:

```text
https://www.deafjobsindia.com
```

or whichever canonical hostname is selected in `SITE_URL`.

Do not add a permanent www/apex redirect until the final canonical hostname is confirmed.

## Cache strategy

DeafJobsIndia is a news site, so HTML should refresh relatively quickly.

The `.htaccess` policy uses a short HTML cache and longer caching for:

- CSS
- JavaScript
- SVG
- PNG/JPEG/WebP/AVIF
- WOFF2 fonts

Astro-generated assets under `/_astro/` are fingerprinted and safe for longer browser caching.

## Deployment security

Production deployment uses a dedicated SSH key.

Recommended rules:

- generate a separate key only for DeafJobsIndia deployment;
- add only the public key to Hostinger;
- store the private key only in GitHub Actions secrets;
- never commit the private key;
- use `HOSTINGER_KNOWN_HOSTS` when possible;
- keep SSH access disabled when you intentionally no longer need automated deployment.

## rsync protection

The deployment workflow uses:

```text
rsync --delete
```

This means old files disappear from `public_html` when they are removed from the current `dist/` build.

Safety controls:

- deployment stops unless `HOSTINGER_REMOTE_PATH` ends with `/public_html`;
- `.well-known/` is excluded from deletion;
- only `dist/` is used as the upload source.

Do not store unrelated/manual files in the DeafJobsIndia `public_html` directory.

## Content security

Real stories are protected separately by the Step 13 editorial workflow:

- verification required
- source metadata
- official/source link checks
- draft/scheduled/published statuses
- content audit before deployment

## Production checks

Before a manual build:

```bash
npm run content:audit
npm run build
npm run verify:dist
```

GitHub Actions runs the same checks automatically before Hostinger deployment.
