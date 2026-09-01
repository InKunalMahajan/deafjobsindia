import fs from 'node:fs';
import { frontmatter, indiaTimestamp, preflight, resolvePost, scalar, setScalar } from './newsroom-utils.mjs';

const [, , slugArg] = process.argv;

if (!slugArg) {
  console.error('Usage: npm run post:publish -- "story-slug"');
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
  console.log('Pre-publish warnings:');
  warnings.forEach((item) => console.log(`- ${item}`));
}

if (errors.length) {
  console.error('\nCannot publish:');
  errors.forEach((item) => console.error(`- ${item}`));
  process.exit(1);
}

const now = indiaTimestamp();
source = setScalar(source, 'status', 'published');
source = setScalar(source, 'publishedAt', now);
source = setScalar(source, 'updatedAt', now);
source = setScalar(source, 'draft', 'false');
source = setScalar(source, 'demo', 'false');

fs.writeFileSync(file, source, 'utf8');

const fm = frontmatter(source);
console.log(`Published: ${scalar(fm, 'title')}`);
console.log(`File: ${file}`);
console.log(`Published at: ${now}`);
console.log('Next: npm run content:audit && npm run build');
