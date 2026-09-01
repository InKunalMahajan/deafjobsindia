import fs from 'node:fs';
import path from 'node:path';

const [, , titleArg, categoryArg = 'Community'] = process.argv;

if (!titleArg) {
  console.error('Usage: npm run new:post -- "Story headline" "Jobs"');
  process.exit(1);
}

const allowedCategories = [
  'Jobs',
  'Education',
  'Government Schemes',
  'Training',
  'Job Fairs',
  'Community',
  'Technology',
  'Career',
];

if (!allowedCategories.includes(categoryArg)) {
  console.error(`Invalid category: ${categoryArg}`);
  console.error(`Use one of: ${allowedCategories.join(', ')}`);
  process.exit(1);
}

function slugify(value) {
  return value
    .toLowerCase()
    .normalize('NFKD')
    .replace(/[^\w\s-]/g, '')
    .trim()
    .replace(/[\s_]+/g, '-')
    .replace(/-+/g, '-');
}

function indiaTimestamp() {
  const parts = new Intl.DateTimeFormat('en-CA', {
    timeZone: 'Asia/Kolkata',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hourCycle: 'h23',
  }).formatToParts(new Date());

  const map = Object.fromEntries(parts.map(({ type, value }) => [type, value]));
  return `${map.year}-${map.month}-${map.day}T${map.hour}:${map.minute}:${map.second}+05:30`;
}

function quote(value) {
  return JSON.stringify(value);
}

const templates = {
  Jobs: `## Job overview

Explain the role, employer and why this opportunity is relevant.

## Vacancy details

- **Role:** 
- **Employer:** 
- **Location:** 
- **Employment type:** 
- **Number of vacancies:** 

## Eligibility

List education, experience, age or other eligibility requirements.

## Accessibility information

Add only verified accessibility, interpreter, accommodation or Deaf-friendly information.

## How to apply

Explain the official application process.

## Important dates

Add the application deadline and other important dates.

## Source

Summarise what was verified from the official employer or recruitment source.`,

  Education: `## Key update

Explain the admission, scholarship, examination or education update.

## Who is eligible?

List the eligibility criteria clearly.

## Important dates

Add application, exam, result or admission dates.

## How to apply or check

Give the official process step by step.

## Documents required

List documents only when confirmed by the official source.

## Source

Summarise the official education source used for verification.`,

  'Government Schemes': `## What is this scheme?

Explain the scheme in simple language.

## Who can apply?

List eligibility requirements.

## Benefits

Explain verified benefits, assistance or entitlements.

## Documents required

List official document requirements.

## How to apply

Explain online or offline application steps.

## Important dates

Add deadlines or application periods if applicable.

## Source

Summarise the government source used for verification.`,

  Training: `## Training overview

Explain the course, workshop or skill programme.

## Who can join?

List eligibility and target participants.

## Training details

- **Mode:** 
- **Location:** 
- **Duration:** 
- **Fees:** 

## Registration

Explain the official registration process.

## Important dates

Add registration and programme dates.

## Source

Summarise the organisation or official programme source.`,

  'Job Fairs': `## Event overview

Explain the job fair, recruitment drive or hiring event.

## Event details

- **Date:** 
- **Time:** 
- **Venue:** 
- **Organiser:** 

## Who can attend?

Explain eligibility or registration requirements.

## What to bring

List documents or preparation only when confirmed.

## Registration

Explain how to register through the official organiser.

## Source

Summarise the organiser's official announcement.`,

  Community: `## What happened?

Report the verified community update clearly.

## Key details

Add names, dates, places and organisations only when verified.

## Why it matters

Explain the relevance to Deaf people and the wider community.

## What happens next?

Add confirmed next steps, dates or contacts if available.

## Source

Explain where the information was confirmed.`,

  Technology: `## What changed?

Explain the technology update in simple language.

## Why it matters

Describe the practical impact for Deaf users or readers.

## How to use it

Give clear steps when the feature or tool is available.

## Accessibility notes

Mention captions, sign-language support, visual alerts or other accessibility details only when verified.

## Source

Summarise the official product, developer or organisation source.`,

  Career: `## Career guidance

Explain the career topic and who will benefit.

## Recommended steps

Give practical, realistic steps.

## Checklist

- 
- 
- 

## Common mistakes to avoid

Add useful guidance without making unsupported promises.

## Sources

List reliable references or explain the editorial basis for the guidance.`,
};

const slug = slugify(titleArg);
const destination = path.join(process.cwd(), 'src', 'content', 'news', `${slug}.md`);

if (!slug) {
  console.error('Could not create a valid slug from the title.');
  process.exit(1);
}

if (fs.existsSync(destination)) {
  console.error(`A post already exists: ${destination}`);
  process.exit(1);
}

const timestamp = indiaTimestamp();

const body = `---
title: ${quote(titleArg)}
description: "REPLACE: one clear sentence explaining the story."
category: ${quote(categoryArg)}
publishedAt: ${timestamp}
updatedAt: ${timestamp}
image: "/images/featured.svg"
imageAlt: "REPLACE: describe the article image."
author: "DeafJobsIndia Desk"
authorId: desk
status: draft
tags:
  - ${quote(categoryArg)}
featured: false
trending: false
breaking: false
draft: false
demo: false

verificationStatus: pending
sourceName: "REPLACE: official source name"
sourceUrl: "https://example.com/replace-me"
sourceType: "Official"
sourceNote: "REPLACE: what was checked and confirmed."

# Optional reader action/details. Uncomment and complete only when relevant.
# actionLabel: "Apply now"
# actionUrl: "https://example.com/official-action"
# deadline: 2026-09-30T23:59:00+05:30
# location: "Pune, Maharashtra"
---

${templates[categoryArg]}

---

**Editorial note:** Before publishing, confirm the facts, replace all REPLACE/example placeholders, set \`verificationStatus: verified\`, add \`verifiedAt\`, and run the publish check.
`;

fs.writeFileSync(destination, body, 'utf8');
console.log(`Created draft: ${destination}`);
console.log('Next: verify sources, replace placeholders, then run npm run post:publish -- "' + slug + '"');
