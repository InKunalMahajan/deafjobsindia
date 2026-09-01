# DeafJobsIndia Editorial Workflow

This is the production workflow for publishing real DeafJobsIndia stories.

## 1. Create a draft

```bash
npm run new:post -- "Your headline" "Jobs"
```

Allowed categories:

- Jobs
- Education
- Government Schemes
- Training
- Job Fairs
- Community
- Technology
- Career

The command creates a draft in:

```text
src/content/news/
```

The new file includes a category-specific writing structure.

## 2. Write the article

Replace every `REPLACE:` field and all example URLs.

Complete:

```yaml
title:
description:
category:
image:
imageAlt:
tags:
```

Write the most important verified facts first.

Do not copy full articles, notices, job posts or press releases. Summarise them in DeafJobsIndia's own words and link readers to the original source.

## 3. Add the source

For real stories, complete:

```yaml
verificationStatus: verified
verifiedAt: 2026-09-01T20:30:00+05:30
sourceName: "Official organisation name"
sourceUrl: "https://official-source.example/page"
sourceType: "Official"
sourceNote: "Checked the official notification, eligibility and deadline."
```

Allowed source types:

- Official
- Employer
- Education Institution
- Community Organization
- Press Release
- Original Reporting
- Other

For `Original Reporting`, `sourceUrl` can be omitted.

## 4. Add optional reader details

For a job, scheme, training programme, admission or event:

```yaml
actionLabel: "Apply now"
actionUrl: "https://official-application.example/"
deadline: 2026-09-30T23:59:00+05:30
location: "Pune, Maharashtra"
```

These fields create a clear **Important details** card on the article page.

## 5. Check the newsroom

```bash
npm run newsroom:status
```

This shows draft, scheduled and published stories.

## 6. Publish now

After verification:

```bash
npm run post:publish -- "your-story-slug"
```

The publish command blocks publication when:
- verification is incomplete
- source information is missing
- placeholder text remains
- required article information is missing

It then sets the article to `published` using the current India date/time.

## 7. Schedule a story

```bash
npm run post:schedule -- "your-story-slug" "2026-09-05T09:00:00+05:30"
```

The story stays hidden until the scheduled time.

Because Astro is a static website, the site must rebuild/deploy after the scheduled time. The existing scheduled publishing workflow can do this automatically on a connected deployment system.

## 8. Run the full content audit

```bash
npm run content:audit
```

The audit checks:
- required metadata
- valid categories
- valid status
- valid author IDs
- image paths
- dates
- verification status
- source type
- source URLs
- action URLs
- placeholders
- scheduled publication problems
- homepage editorial pins

## 9. Build

```bash
npm run build
```

For Hostinger static hosting, upload the **contents of `dist/`** to `public_html/`.

## Recommended editorial sequence

**Draft → Source check → Fact check → Accessibility check → Copy edit → Image/alt-text check → Verify → Publish/Schedule → Audit → Build → Deploy**

## DeafJobsIndia publishing principle

A real story should clearly answer:

1. What happened?
2. Who is affected?
3. What should the reader do?
4. What date/deadline matters?
5. Where was the information verified?
