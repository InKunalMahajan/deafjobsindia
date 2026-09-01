import fs from 'node:fs';
import path from 'node:path';
import { frontmatter, newsDir, scalar } from './newsroom-utils.mjs';

const files = fs.readdirSync(newsDir)
  .filter((name) => name.endsWith('.md'))
  .sort();

const rows = files.map((file) => {
  const source = fs.readFileSync(path.join(newsDir, file), 'utf8');
  const fm = frontmatter(source);
  return {
    file,
    title: scalar(fm, 'title'),
    category: scalar(fm, 'category'),
    status: scalar(fm, 'status') || 'published',
    verification: scalar(fm, 'verificationStatus') || 'pending',
    publishedAt: scalar(fm, 'publishedAt'),
    demo: scalar(fm, 'demo') === 'true',
  };
});

const counts = {};
for (const row of rows) counts[row.status] = (counts[row.status] || 0) + 1;

console.log('DeafJobsIndia Newsroom Status');
console.log(`Total stories: ${rows.length}`);
console.log(`Draft: ${counts.draft || 0}`);
console.log(`Scheduled: ${counts.scheduled || 0}`);
console.log(`Published: ${counts.published || 0}`);

console.log('\nStories');
for (const row of rows) {
  const marker = row.demo ? 'DEMO' : row.verification.toUpperCase();
  console.log(`- [${row.status.toUpperCase()}] [${marker}] ${row.category} — ${row.title}`);
  console.log(`  ${row.file}${row.publishedAt ? ` • ${row.publishedAt}` : ''}`);
}
