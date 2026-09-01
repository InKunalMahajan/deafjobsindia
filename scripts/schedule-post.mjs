import fs from 'node:fs';
import { frontmatter, indiaTimestamp, preflight, resolvePost, scalar, setScalar } from './newsroom-utils.mjs';

const [, , slugArg, dateArg] = process.argv;

if (!slugArg || !dateArg) {
  console.error('Usage: npm run post:schedule -- "story-slug" "2026-09-05T09:00:00+05:30"');
  process.exit(1);
}

const scheduleDate = new Date(dateArg);
if (Number.isNaN(scheduleDate.getTime())) {
  console.error('Invalid schedule date/time. Use an ISO date with timezone, e.g. 2026-09-05T09:00:00+05:30');
  process.exit(1);
}

if (scheduleDate <= new Date()) {
  console.error('Schedule date/time must be in the future.');
  process.exit(1);
}

const file = resolvePost(slugArg);
if (!file) {
  console.error(`Post not found: ${slugArg}`);
  process.exit(1);
}

let source = fs.readFileSync(file, 'utf8');
const { errors, warnings } = preflight(source);

if (warnings.length) {
  console.log('Pre-schedule warnings:');
  warnings.forEach((item) => console.log(`- ${item}`));
}

if (errors.length) {
  console.error('\nCannot schedule:');
  errors.forEach((item) => console.error(`- ${item}`));
  process.exit(1);
}

source = setScalar(source, 'status', 'scheduled');
source = setScalar(source, 'publishedAt', dateArg);
source = setScalar(source, 'updatedAt', indiaTimestamp());
source = setScalar(source, 'draft', 'false');
source = setScalar(source, 'demo', 'false');

fs.writeFileSync(file, source, 'utf8');

const fm = frontmatter(source);
console.log(`Scheduled: ${scalar(fm, 'title')}`);
console.log(`Publish date: ${dateArg}`);
console.log('The site must rebuild/deploy after the scheduled time for the story to become public.');
