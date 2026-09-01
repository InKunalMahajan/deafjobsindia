import fs from 'node:fs';
import path from 'node:path';

const root = process.cwd();
const dist = path.join(root, 'dist');
const domain = 'https://deafjobsindia.in';

const errors = [];
const warnings = [];

function requireFile(rel) {
  const full = path.join(dist, rel);
  if (!fs.existsSync(full)) errors.push(`Missing dist/${rel}`);
  return full;
}

function read(rel) {
  const full = requireFile(rel);
  return fs.existsSync(full) ? fs.readFileSync(full, 'utf8') : '';
}

if (!fs.existsSync(dist)) {
  console.error('dist/ does not exist. Run npm run build first.');
  process.exit(1);
}

const index = read('index.html');
const robots = read('robots.txt');
const cname = read('CNAME').trim();
const manifest = read('site.webmanifest');
requireFile('sitemap-index.xml');
requireFile('rss.xml');
requireFile('404.html');
requireFile('favicon.svg');

if (!index.includes(`<link rel="canonical" href="${domain}/"`)) {
  errors.push(`Homepage canonical is not ${domain}/`);
}

if (!index.includes('property="og:url"')) errors.push('Homepage is missing og:url.');
if (!index.includes('property="og:image"')) errors.push('Homepage is missing og:image.');
if (!index.includes('name="twitter:card"')) errors.push('Homepage is missing Twitter card metadata.');
if (!index.includes('application/ld+json')) errors.push('Homepage is missing structured data.');
if (!index.includes('name="robots"')) errors.push('Homepage is missing robots meta tag.');
if (!index.includes('rel="manifest"')) errors.push('Homepage is missing web manifest link.');

if (!robots.includes(`Sitemap: ${domain}/sitemap-index.xml`)) {
  errors.push('robots.txt sitemap URL is incorrect.');
}

if (cname !== 'deafjobsindia.in') {
  errors.push('CNAME must contain exactly deafjobsindia.in');
}

if (manifest && !manifest.includes('"name": "DeafJobsIndia"')) {
  warnings.push('site.webmanifest does not contain the expected app name.');
}

if (index.includes('www.deafjobsindia.com') || robots.includes('www.deafjobsindia.com')) {
  errors.push('Old .com production URL is still present in generated output.');
}

console.log('DeafJobsIndia launch audit');
console.log(`Errors: ${errors.length}`);
console.log(`Warnings: ${warnings.length}`);

if (warnings.length) {
  console.log('\nWarnings');
  warnings.forEach((item) => console.log(`- ${item}`));
}

if (errors.length) {
  console.error('\nErrors');
  errors.forEach((item) => console.error(`- ${item}`));
  process.exit(1);
}

console.log('\nLaunch audit passed.');
