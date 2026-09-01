import fs from 'node:fs';
import path from 'node:path';

const root = process.cwd();
const newsDir = path.join(root, 'src', 'content', 'news');
const authorsPath = path.join(root, 'src', 'data', 'authors.json');
const editorialPath = path.join(root, 'src', 'data', 'editorial.ts');

const authors = JSON.parse(fs.readFileSync(authorsPath, 'utf8'));

const allowedCategories = new Set([
  'Jobs',
  'Education',
  'Government Schemes',
  'Training',
  'Job Fairs',
  'Community',
  'Technology',
  'Career',
]);

const allowedStatuses = new Set(['draft', 'published', 'scheduled']);
const allowedVerification = new Set(['pending', 'verified']);
const allowedSourceTypes = new Set([
  'Official',
  'Employer',
  'Education Institution',
  'Community Organization',
  'Press Release',
  'Original Reporting',
  'Other',
]);

function frontmatter(source) {
  if (!source.startsWith('---')) return '';
  const end = source.indexOf('\n---', 3);
  return end === -1 ? '' : source.slice(3, end);
}

function scalar(fm, key) {
  const match = fm.match(new RegExp(`^${key}:\\s*(.+?)\\s*$`, 'm'));
  if (!match) return '';
  return match[1].trim().replace(/^["']|["']$/g, '');
}

function bool(fm, key) {
  return scalar(fm, key) === 'true';
}

function parseDate(value) {
  const date = new Date(value);
  return Number.isNaN(date.getTime()) ? null : date;
}

function validUrl(value) {
  if (!value) return false;
  try {
    const url = new URL(value);
    return url.protocol === 'http:' || url.protocol === 'https:';
  } catch {
    return false;
  }
}

const files = fs.readdirSync(newsDir)
  .filter((name) => name.endsWith('.md'))
  .sort();

const errors = [];
const warnings = [];
const rows = [];
const now = new Date();

for (const file of files) {
  const full = path.join(newsDir, file);
  const source = fs.readFileSync(full, 'utf8');
  const fm = frontmatter(source);

  if (!fm) {
    errors.push(`${file}: missing frontmatter`);
    continue;
  }

  const title = scalar(fm, 'title');
  const description = scalar(fm, 'description');
  const category = scalar(fm, 'category');
  const dateValue = scalar(fm, 'publishedAt');
  const image = scalar(fm, 'image');
  const imageAlt = scalar(fm, 'imageAlt');
  const authorId = scalar(fm, 'authorId') || 'desk';
  const status = scalar(fm, 'status') || 'published';
  const verificationStatus = scalar(fm, 'verificationStatus') || 'pending';
  const verifiedAtValue = scalar(fm, 'verifiedAt');
  const sourceName = scalar(fm, 'sourceName');
  const sourceUrl = scalar(fm, 'sourceUrl');
  const sourceType = scalar(fm, 'sourceType');
  const sourceNote = scalar(fm, 'sourceNote');
  const actionUrl = scalar(fm, 'actionUrl');
  const deadlineValue = scalar(fm, 'deadline');
  const demo = bool(fm, 'demo');

  const date = parseDate(dateValue);
  const verifiedAt = verifiedAtValue ? parseDate(verifiedAtValue) : null;
  const deadline = deadlineValue ? parseDate(deadlineValue) : null;

  if (!title) errors.push(`${file}: title is required`);
  if (!description) errors.push(`${file}: description is required`);
  if (!allowedCategories.has(category)) errors.push(`${file}: invalid category "${category}"`);
  if (!date) errors.push(`${file}: publishedAt is missing or invalid`);
  if (!image) errors.push(`${file}: image is required`);
  if (!imageAlt) warnings.push(`${file}: imageAlt is empty`);
  if (!authors[authorId]) errors.push(`${file}: unknown authorId "${authorId}"`);
  if (!allowedStatuses.has(status)) errors.push(`${file}: invalid status "${status}"`);
  if (!allowedVerification.has(verificationStatus)) errors.push(`${file}: invalid verificationStatus "${verificationStatus}"`);
  if (sourceType && !allowedSourceTypes.has(sourceType)) errors.push(`${file}: invalid sourceType "${sourceType}"`);

  if (date) {
    if (status === 'published' && date > now) {
      warnings.push(`${file}: status is published but publishedAt is in the future`);
    }
    if (status === 'scheduled' && date <= now) {
      warnings.push(`${file}: scheduled time has passed; rebuild/deploy or change status to published`);
    }
  }

  if (verifiedAtValue && !verifiedAt) errors.push(`${file}: verifiedAt is invalid`);
  if (deadlineValue && !deadline) errors.push(`${file}: deadline is invalid`);
  if (sourceUrl && !validUrl(sourceUrl)) errors.push(`${file}: sourceUrl is invalid`);
  if (actionUrl && !validUrl(actionUrl)) errors.push(`${file}: actionUrl is invalid`);

  if (image.startsWith('/')) {
    const localImage = path.join(root, 'public', image.replace(/^\/+/, ''));
    if (!fs.existsSync(localImage)) warnings.push(`${file}: local image not found at public${image}`);
  }

  // Demo starter stories are allowed to omit newsroom verification metadata.
  // Real published/scheduled stories are held to the production standard.
  if (!demo && (status === 'published' || status === 'scheduled')) {
    if (verificationStatus !== 'verified') {
      errors.push(`${file}: real ${status} story must have verificationStatus: verified`);
    }
    if (!verifiedAt) errors.push(`${file}: real ${status} story requires verifiedAt`);
    if (!sourceName) errors.push(`${file}: real ${status} story requires sourceName`);
    if (!sourceType) errors.push(`${file}: real ${status} story requires sourceType`);
    if (sourceType !== 'Original Reporting' && !validUrl(sourceUrl)) {
      errors.push(`${file}: real ${status} story requires a valid sourceUrl unless sourceType is Original Reporting`);
    }
  }

  if (!demo && /\bREPLACE:/i.test(source)) {
    if (status === 'draft') warnings.push(`${file}: draft still contains REPLACE placeholders`);
    else errors.push(`${file}: published/scheduled story contains REPLACE placeholders`);
  }

  if (/example\.com\/(?:replace-me|official-action)/i.test(source)) {
    if (status === 'draft') warnings.push(`${file}: draft still contains example.com placeholders`);
    else errors.push(`${file}: published/scheduled story contains example.com placeholders`);
  }

  if (!demo && sourceName && !sourceNote) {
    warnings.push(`${file}: sourceNote is recommended`);
  }

  if (!demo && image === '/images/featured.svg') {
    warnings.push(`${file}: default placeholder image is still being used`);
  }

  rows.push({
    file,
    status,
    category,
    title,
    date: dateValue,
    verificationStatus,
    demo,
  });
}

// Validate story IDs pinned in editorial config.
if (fs.existsSync(editorialPath)) {
  const editorial = fs.readFileSync(editorialPath, 'utf8');
  const knownIds = new Set(files.map((file) => file.replace(/\.md$/, '')));
  const quotedIds = [...editorial.matchAll(/['"]([a-z0-9][a-z0-9-]+)['"]/g)]
    .map((match) => match[1])
    .filter((value) => value.includes('-'));

  for (const id of quotedIds) {
    if (
      !knownIds.has(id) &&
      !['job-fairs'].includes(id)
    ) {
      // Only warn for values that look like story pins.
      if (editorial.includes(`'${id}'`) || editorial.includes(`"${id}"`)) {
        const around = editorial.slice(Math.max(0, editorial.indexOf(id) - 80), editorial.indexOf(id) + id.length + 80);
        if (/Story|Trending/i.test(around)) warnings.push(`editorial.ts: pinned story "${id}" does not exist`);
      }
    }
  }
}

console.log('DeafJobsIndia content audit');
console.log(`Stories checked: ${rows.length}`);
console.log(`Errors: ${errors.length}`);
console.log(`Warnings: ${warnings.length}`);

if (warnings.length) {
  console.log('\nWarnings');
  warnings.forEach((item) => console.log(`- ${item}`));
}

if (errors.length) {
  console.error('\nErrors');
  errors.forEach((item) => console.error(`- ${item}`));
  process.exitCode = 1;
} else {
  console.log('\nContent audit passed.');
}
