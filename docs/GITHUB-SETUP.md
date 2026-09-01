# DeafJobsIndia GitHub Setup

## Repository

Recommended repository name:

```text
deafjobsindia
```

Production branch:

```text
main
```

## Included workflows

### CI

```text
.github/workflows/ci.yml
```

Runs on pushes and pull requests.

It checks:

1. Node dependencies
2. newsroom/content audit
3. Astro production build
4. `dist/` verification

### Hostinger production deployment

```text
.github/workflows/deploy-hostinger.yml
```

Runs on:

- push to `main`
- manual workflow dispatch
- hourly scheduled publishing check

The production workflow builds Astro on GitHub and synchronizes only `dist/` to Hostinger.

## GitHub Actions secrets

Required:

```text
HOSTINGER_HOST
HOSTINGER_USERNAME
HOSTINGER_SSH_PRIVATE_KEY
HOSTINGER_REMOTE_PATH
```

Recommended:

```text
HOSTINGER_KNOWN_HOSTS
```

## GitHub Actions variables

Recommended:

```text
SITE_URL=https://www.deafjobsindia.com
HOSTINGER_PORT=65002
```

## Branch protection

For a production newsroom, consider protecting `main` and requiring the CI workflow to pass before merge.

For a one-person workflow, you can initially push directly to `main`, then enable branch protection later.

## Secret safety

Never put Hostinger credentials or private SSH keys in:

- Markdown posts
- `.env`
- `.env.example`
- source files
- workflow YAML values
- screenshots shared publicly
- Git commits

Only GitHub Actions secrets should store the private deployment key.

See:

```text
docs/HOSTINGER-AUTO-DEPLOY.md
```
