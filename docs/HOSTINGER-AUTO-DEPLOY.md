# Step 14 — GitHub → Hostinger Automatic Deployment

DeafJobsIndia remains a **static Astro website**.

The production flow is:

```text
Edit / publish story
        ↓
Push to GitHub main
        ↓
GitHub Actions
        ↓
Content audit
        ↓
Astro build
        ↓
Verify dist/
        ↓
Secure SSH + rsync
        ↓
Hostinger public_html
        ↓
Live website check
```

Only the generated `dist/` files are uploaded to Hostinger. The Astro source code is not placed inside `public_html`.

## Hosting requirement

This deployment workflow uses SSH/SFTP/rsync.

Hostinger web hosting needs a plan with SSH access (Premium Web or higher at the time Step 14 was prepared).

The default Hostinger SSH/SFTP port is normally:

```text
65002
```

Always use the connection details shown in your own hPanel account.

---

# Part 1 — Enable SSH in Hostinger

In Hostinger:

1. Open **Websites**.
2. Open the Dashboard for DeafJobsIndia.
3. Open **Advanced → SSH Access**.
4. Enable SSH access.
5. Note:
   - Host / IP address
   - Username
   - Port

Do not put your Hostinger account login password in the project.

---

# Part 2 — Create a dedicated deployment SSH key

On Windows PowerShell:

```powershell
ssh-keygen -t ed25519 `
  -C "deafjobsindia-github-deploy" `
  -f "$env:USERPROFILE\.ssh\deafjobsindia_hostinger"
```

This creates:

```text
deafjobsindia_hostinger
deafjobsindia_hostinger.pub
```

The `.pub` file is the **public key**.

The file without `.pub` is the **private key**.

Never commit the private key to GitHub.

## Add the public key to Hostinger

Display it:

```powershell
Get-Content "$env:USERPROFILE\.ssh\deafjobsindia_hostinger.pub"
```

Then in Hostinger:

**Websites → Dashboard → Advanced → SSH Access → Add SSH key**

Paste only the public key.

---

# Part 3 — Test the SSH key

Use the Hostinger username and IP from hPanel:

```powershell
ssh `
  -i "$env:USERPROFILE\.ssh\deafjobsindia_hostinger" `
  -p 65002 `
  YOUR_HOSTINGER_USERNAME@YOUR_HOSTINGER_IP
```

The login should work without asking for the normal Hostinger password.

---

# Part 4 — Find the exact public_html path

After connecting by SSH:

```bash
pwd
```

Then locate your domain:

```bash
cd domains
ls
```

Open the DeafJobsIndia domain folder:

```bash
cd deafjobsindia.com/public_html
pwd
```

Copy the full path returned by `pwd`.

It commonly looks similar to:

```text
/home/u123456789/domains/deafjobsindia.com/public_html
```

Do not copy the example. Use the exact path from your hosting account.

The deployment workflow intentionally refuses to run unless the path ends in:

```text
/public_html
```

This protects against accidentally running `rsync --delete` in the wrong server directory.

---

# Part 5 — Add GitHub Actions secrets

Open:

**GitHub repository → Settings → Secrets and variables → Actions**

Under **Repository secrets**, create:

## HOSTINGER_HOST

Example:

```text
185.185.185.185
```

Use the Hostinger SSH host/IP shown in hPanel.

## HOSTINGER_USERNAME

Example:

```text
u123456789
```

Use the actual SSH username.

## HOSTINGER_SSH_PRIVATE_KEY

Display the private key locally:

```powershell
Get-Content "$env:USERPROFILE\.ssh\deafjobsindia_hostinger" -Raw
```

Copy the complete value, including:

```text
-----BEGIN OPENSSH PRIVATE KEY-----
...
-----END OPENSSH PRIVATE KEY-----
```

Paste it into the GitHub secret.

## HOSTINGER_REMOTE_PATH

Example structure:

```text
/home/u123456789/domains/deafjobsindia.com/public_html
```

Use the exact path you confirmed using SSH.

## HOSTINGER_KNOWN_HOSTS

Recommended.

Generate it locally:

```powershell
ssh-keyscan -p 65002 -H YOUR_HOSTINGER_IP
```

Copy the complete output into this GitHub secret.

If this secret is omitted, the workflow will fetch the host key during deployment and show a warning.

---

# Part 6 — Add GitHub repository variables

In:

**Settings → Secrets and variables → Actions → Variables**

Create:

## SITE_URL

```text
https://www.deafjobsindia.com
```

Use your actual canonical domain.

## HOSTINGER_PORT

```text
65002
```

Only change this if hPanel gives a different port.

---

# Part 7 — Push the project to GitHub

From the project folder:

```bash
git init
git add .
git commit -m "DeafJobsIndia Step 14 Hostinger deployment"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/deafjobsindia.git
git push -u origin main
```

If the repository already exists:

```bash
git add .
git commit -m "Add Hostinger automatic deployment"
git push
```

---

# Part 8 — What happens automatically

`.github/workflows/deploy-hostinger.yml`

runs when:

- code/content is pushed to `main`
- you choose **Run workflow** manually
- the hourly scheduled publishing check runs

For a normal push it:

1. checks out the repository;
2. installs Node dependencies;
3. runs the DeafJobsIndia content audit;
4. builds the Astro site;
5. verifies `dist/`;
6. validates the Hostinger deployment secrets;
7. connects to Hostinger securely;
8. checks that the remote directory exists;
9. synchronizes `dist/` into `public_html`;
10. removes old site files that are no longer in `dist/`;
11. preserves `.well-known/`;
12. opens the production website and checks for HTTP 200 + `DeafJobsIndia`.

---

# Scheduled stories

Step 13 already supports:

```bash
npm run post:schedule -- "story-slug" "2026-09-05T09:00:00+05:30"
```

The GitHub production workflow checks once per hour.

When a scheduled story becomes due:

```text
Scheduled story
      ↓
release:scheduled
      ↓
verification check
      ↓
content audit
      ↓
Astro build
      ↓
Hostinger deploy
      ↓
live-site verification
      ↓
story status committed back to GitHub
```

If no story is due, the scheduled workflow does not build or deploy the website.

---

# Important deployment safety

The workflow uses:

```text
rsync --delete
```

This keeps `public_html` exactly synchronized with the current `dist/` build.

Therefore:

- do not manually keep unrelated files inside DeafJobsIndia `public_html`;
- `.well-known/` is excluded from deletion;
- the configured remote path must end in `/public_html`;
- always verify `HOSTINGER_REMOTE_PATH` carefully.

---

# Hostinger .htaccess

Step 14 adds:

```text
public/.htaccess
```

Astro copies it into `dist/`, and GitHub deploys it to Hostinger.

It provides:

- directory listing protection
- custom 404 handling
- common security headers
- browser caching
- compression where supported

---

# Manual emergency deployment

If GitHub deployment is temporarily unavailable:

```bash
npm install
npm run content:audit
npm run build
npm run verify:dist
```

Then upload the **contents inside `dist/`** to the Hostinger `public_html` directory.

Do not upload the `dist` folder itself as:

```text
public_html/dist/
```

The correct structure is:

```text
public_html/
├── index.html
├── _astro/
├── news/
├── jobs/
├── education/
├── favicon.svg
└── ...
```

---

# Troubleshooting

## Permission denied (publickey)

Check:

- SSH access is enabled in Hostinger.
- The correct public key was added to hPanel.
- `HOSTINGER_SSH_PRIVATE_KEY` contains the matching private key.
- The GitHub secret contains the entire key.

## Connection timed out

Check:

- `HOSTINGER_HOST`
- `HOSTINGER_PORT`
- Hostinger remote/SSH access status

## Remote path failed

Connect manually by SSH and run:

```bash
cd domains/deafjobsindia.com/public_html
pwd
```

Use that exact value for `HOSTINGER_REMOTE_PATH`.

## Build failed

Run locally:

```bash
npm run content:audit
npm run build
npm run verify:dist
```

Fix the first reported error before pushing again.

## Upload succeeded but live verification failed

Check:

- `SITE_URL`
- domain DNS
- SSL
- Hostinger cache
- whether the domain points to the same Hostinger website
