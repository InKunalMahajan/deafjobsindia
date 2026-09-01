import fs from 'node:fs';
import path from 'node:path';

const dist = path.join(process.cwd(), 'dist');

const required = [
  'index.html',
  '404.html',
  'robots.txt',
  'rss.xml',
  'sitemap-index.xml',
  '.htaccess',
];

const missing = required.filter((name) => !fs.existsSync(path.join(dist, name)));

if (!fs.existsSync(dist)) {
  console.error('dist/ does not exist. Run npm run build first.');
  process.exit(1);
}

if (missing.length) {
  console.error('Production verification failed. Missing build artifacts:');
  missing.forEach((name) => console.error(`- ${name}`));
  process.exit(1);
}

const index = fs.readFileSync(path.join(dist, 'index.html'), 'utf8');

const requiredSignals = [
  ['canonical link', 'rel="canonical"'],
  ['Open Graph metadata', 'property="og:title"'],
  ['structured data', 'application/ld+json'],
];

const missingSignals = requiredSignals
  .filter(([, needle]) => !index.includes(needle))
  .map(([label]) => label);

if (missingSignals.length) {
  console.error('Production verification failed. Homepage is missing:');
  missingSignals.forEach((label) => console.error(`- ${label}`));
  process.exit(1);
}

console.log('Production build verification passed.');
