# CMS-ready architecture

Step 6 intentionally keeps the public Astro theme independent from any one CMS.

Current content contracts:
- `src/content.config.ts` defines the article schema.
- `src/content/news/` contains article records.
- `src/data/authors.json` contains author records.
- `src/data/editorial.ts` controls homepage placement.
- `src/utils/news.ts` contains publishing and selection rules.

A future headless CMS can replace the Markdown loader while the page components, article layout, categories, tags, authors and homepage presentation remain largely unchanged.

Good future CMS requirements:
- draft / published / scheduled workflow
- role-based editorial access
- media library
- image alt text
- author records
- category and tag fields
- webhooks to trigger Astro deploys
- preview URLs
- revision history

Do not add a CMS merely to change the theme. The theme is already custom and CMS-independent.


## Step 13 publishing fields

The content contract now also supports:

- `verificationStatus`
- `verifiedAt`
- `sourceName`
- `sourceUrl`
- `sourceType`
- `sourceNote`
- `actionLabel`
- `actionUrl`
- `deadline`
- `location`

A future CMS should expose these fields directly to editors and should not allow a real story to move to Published until verification requirements are satisfied.
