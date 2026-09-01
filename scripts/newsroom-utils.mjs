import fs from 'node:fs';
import path from 'node:path';

export const root = process.cwd();
export const newsDir = path.join(root, 'src', 'content', 'news');

export function resolvePost(arg) {
  if (!arg) return null;
  const name = arg.endsWith('.md') ? arg : `${arg}.md`;
  const full = path.join(newsDir, name);
  return fs.existsSync(full) ? full : null;
}

export function frontmatter(source) {
  if (!source.startsWith('---')) return '';
  const end = source.indexOf('\n---', 3);
  return end === -1 ? '' : source.slice(3, end);
}

export function scalar(fm, key) {
  const match = fm.match(new RegExp(`^${key}:\\s*(.+?)\\s*$`, 'm'));
  if (!match) return '';
  return match[1].trim().replace(/^["']|["']$/g, '');
}

export function setScalar(source, key, value) {
  const encoded = typeof value === 'string' && /[:#\s]/.test(value) && !/^https?:\/\//.test(value)
    ? JSON.stringify(value)
    : String(value);
  const line = `${key}: ${encoded}`;
  const pattern = new RegExp(`^${key}:\\s*.*$`, 'm');

  if (pattern.test(source)) return source.replace(pattern, line);

  const end = source.indexOf('\n---', 3);
  if (end === -1) throw new Error('Missing frontmatter closing delimiter.');
  return source.slice(0, end) + `\n${line}` + source.slice(end);
}

export function indiaTimestamp(date = new Date()) {
  const parts = new Intl.DateTimeFormat('en-CA', {
    timeZone: 'Asia/Kolkata',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hourCycle: 'h23',
  }).formatToParts(date);

  const map = Object.fromEntries(parts.map(({ type, value }) => [type, value]));
  return `${map.year}-${map.month}-${map.day}T${map.hour}:${map.minute}:${map.second}+05:30`;
}

export function preflight(source) {
  const fm = frontmatter(source);
  const errors = [];
  const warnings = [];

  if (!fm) return { errors: ['Missing frontmatter.'], warnings };

  const required = ['title', 'description', 'category', 'image', 'imageAlt', 'authorId'];
  for (const key of required) {
    if (!scalar(fm, key)) errors.push(`${key} is required.`);
  }

  const description = scalar(fm, 'description');
  const imageAlt = scalar(fm, 'imageAlt');
  const image = scalar(fm, 'image');
  const sourceName = scalar(fm, 'sourceName');
  const sourceUrl = scalar(fm, 'sourceUrl');
  const sourceType = scalar(fm, 'sourceType');
  const sourceNote = scalar(fm, 'sourceNote');
  const verificationStatus = scalar(fm, 'verificationStatus');
  const verifiedAt = scalar(fm, 'verifiedAt');
  const demo = scalar(fm, 'demo') === 'true';

  const combined = `${fm}\n${source}`;
  if (/\bREPLACE:/i.test(combined)) errors.push('Replace all REPLACE placeholders.');
  if (/example\.com\/replace-me/i.test(combined)) errors.push('Replace the example source URL.');
  if (/example\.com\/official-action/i.test(combined)) errors.push('Replace the example action URL.');

  if (!demo) {
    if (verificationStatus !== 'verified') errors.push('verificationStatus must be verified.');
    if (!verifiedAt || Number.isNaN(new Date(verifiedAt).getTime())) errors.push('verifiedAt must contain a valid verification date/time.');
    if (!sourceName) errors.push('sourceName is required for real stories.');
    if (!sourceType) errors.push('sourceType is required for real stories.');
    if (!sourceNote) warnings.push('sourceNote is recommended for editorial transparency.');

    if (sourceType !== 'Original Reporting') {
      if (!/^https?:\/\/\S+/i.test(sourceUrl)) errors.push('A valid sourceUrl is required unless sourceType is Original Reporting.');
    }
  }

  if (image === '/images/featured.svg') warnings.push('The default placeholder image is still being used.');
  if (description.length > 180) warnings.push('Description is longer than 180 characters.');
  if (imageAlt.length < 8) warnings.push('imageAlt is very short.');

  return { errors, warnings };
}
