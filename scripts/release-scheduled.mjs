import fs from 'node:fs';
import path from 'node:path';

const newsDir = path.join(process.cwd(), 'src', 'content', 'news');
const now = new Date();
const files = fs.readdirSync(newsDir).filter((name) => name.endsWith('.md'));
const released = [];

function readScalar(frontmatter, key) {
  const match = frontmatter.match(new RegExp(`^${key}:\\s*(.+?)\\s*$`, 'm'));
  if (!match) return '';
  return match[1].trim().replace(/^["']|["']$/g, '');
}

for (const file of files) {
  const fullPath = path.join(newsDir, file);
  const source = fs.readFileSync(fullPath, 'utf8');

  if (!source.startsWith('---')) continue;
  const end = source.indexOf('\n---', 3);
  if (end === -1) continue;

  const frontmatter = source.slice(3, end);
  const status = readScalar(frontmatter, 'status') || 'published';
  const publishedAt = readScalar(frontmatter, 'publishedAt');
  const verificationStatus = readScalar(frontmatter, 'verificationStatus') || 'pending';
  const demo = readScalar(frontmatter, 'demo') === 'true';

  if (status !== 'scheduled' || !publishedAt) continue;
  if (!demo && verificationStatus !== 'verified') {
    console.warn(`Skipping unverified scheduled story: ${file}`);
    continue;
  }

  const publicationDate = new Date(publishedAt);
  if (Number.isNaN(publicationDate.getTime()) || publicationDate > now) continue;

  const next = source.replace(
    /^status:\s*scheduled\s*$/m,
    'status: published',
  );

  if (next !== source) {
    fs.writeFileSync(fullPath, next, 'utf8');
    released.push(file);
  }
}

if (released.length) {
  console.log(`Released ${released.length} scheduled ${released.length === 1 ? 'story' : 'stories'}:`);
  released.forEach((file) => console.log(`- ${file}`));
} else {
  console.log('No scheduled stories are due for release.');
}
