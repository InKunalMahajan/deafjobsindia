import type { CollectionEntry } from 'astro:content';
import { getAuthorProfile } from '../data/authors';

export type NewsPost = CollectionEntry<'news'>;

export const POSTS_PER_PAGE = 6;

export const categoryMeta = [
  { category: 'Jobs', slug: 'jobs', title: 'Jobs News', description: 'Recruitment, employment and career opportunity news from across India.' },
  { category: 'Education', slug: 'education', title: 'Education News', description: 'Admissions, scholarships, examinations, learning opportunities and education updates.' },
  { category: 'Government Schemes', slug: 'schemes', title: 'Government Schemes', description: 'Government programmes, public notices and scheme-related updates explained clearly.' },
  { category: 'Training', slug: 'training', title: 'Training & Skills', description: 'Skill development, workshops, courses and training opportunities.' },
  { category: 'Job Fairs', slug: 'job-fairs', title: 'Job Fairs & Events', description: 'Hiring events, career fairs, recruitment drives and employment events.' },
  { category: 'Community', slug: 'community', title: 'Deaf Community News', description: 'Community stories, achievements, organisations, events and important updates.' },
  { category: 'Technology', slug: 'technology', title: 'Technology', description: 'Digital tools, technology news and practical technology updates.' },
  { category: 'Career', slug: 'career', title: 'Career', description: 'Career guidance, workplace advice, resumes, interviews and professional development.' },
] as const;


export function isPublicPost(post: NewsPost, now = new Date()) {
  const status = post.data.status ?? (post.data.draft ? 'draft' : 'published');

  if (post.data.draft || status === 'draft') return false;

  // Demo content remains visible in the starter project.
  // Real DeafJobsIndia stories must pass editorial verification before publication.
  if (!post.data.demo && post.data.verificationStatus !== 'verified') return false;

  if (status === 'scheduled' && post.data.publishedAt.getTime() > now.getTime()) return false;
  if (post.data.publishedAt.getTime() > now.getTime()) return false;

  return true;
}

export function getPublicPosts(posts: NewsPost[], now = new Date()) {
  return sortPosts(posts.filter((post) => isPublicPost(post, now)));
}

export function pickEditorialPosts(posts: NewsPost[], ids: readonly string[], limit?: number) {
  const byId = new Map(posts.map((post) => [post.id, post]));
  const picked = ids.map((id) => byId.get(id)).filter((post): post is NewsPost => Boolean(post));
  return typeof limit === 'number' ? picked.slice(0, limit) : picked;
}

export function sortPosts(posts: NewsPost[]) {
  return [...posts].sort((a, b) => b.data.publishedAt.getTime() - a.data.publishedAt.getTime());
}

export function getPostUrl(post: NewsPost) {
  return `/news/${post.id}/`;
}

export function getCategoryMeta(category: string) {
  return categoryMeta.find((item) => item.category === category);
}

export function getCategoryBySlug(slug: string) {
  return categoryMeta.find((item) => item.slug === slug);
}

export function getCategoryUrl(category: string) {
  const meta = getCategoryMeta(category);
  return meta ? `/${meta.slug}/` : '/latest/';
}

export function slugify(value: string) {
  return value
    .toLowerCase()
    .normalize('NFKD')
    .replace(/[^\w\s-]/g, '')
    .trim()
    .replace(/[\s_]+/g, '-')
    .replace(/-+/g, '-');
}

export function getTagUrl(tag: string) {
  return `/tag/${slugify(tag)}/`;
}

export function getAuthorUrl(author: string) {
  return `/author/${slugify(author)}/`;
}

export function getAllTags(posts: NewsPost[]) {
  const map = new Map<string, string>();
  for (const post of posts) {
    for (const tag of post.data.tags) {
      const slug = slugify(tag);
      if (slug && !map.has(slug)) map.set(slug, tag);
    }
  }
  return [...map.entries()].map(([slug, name]) => ({ slug, name }));
}

export function getAllAuthors(posts: NewsPost[]) {
  const map = new Map<string, string>();
  for (const post of posts) {
    const profile = getAuthorProfile(post.data.authorId, post.data.author);
    const slug = slugify(profile.name);
    if (slug && !map.has(slug)) map.set(slug, profile.name);
  }
  return [...map.entries()].map(([slug, name]) => ({ slug, name }));
}

export function getPostAuthor(post: NewsPost) {
  return getAuthorProfile(post.data.authorId, post.data.author);
}

export function formatNewsDate(date: Date) {
  return new Intl.DateTimeFormat('en-IN', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  }).format(date);
}

export function getReadingTime(post: NewsPost) {
  const source = (post as NewsPost & { body?: string }).body ?? '';
  const plain = source
    .replace(/```[\s\S]*?```/g, ' ')
    .replace(/`[^`]*`/g, ' ')
    .replace(/!\[[^\]]*]\([^)]*\)/g, ' ')
    .replace(/\[[^\]]*]\([^)]*\)/g, ' ')
    .replace(/[#>*_~\-]/g, ' ');
  const words = plain.trim().split(/\s+/).filter(Boolean).length;
  return Math.max(1, Math.ceil(words / 220));
}

export function paginatePosts(posts: NewsPost[], currentPage: number, pageSize = POSTS_PER_PAGE) {
  const totalPages = Math.max(1, Math.ceil(posts.length / pageSize));
  const safePage = Math.min(Math.max(currentPage, 1), totalPages);
  const start = (safePage - 1) * pageSize;
  return {
    items: posts.slice(start, start + pageSize),
    currentPage: safePage,
    totalPages,
    totalItems: posts.length,
  };
}

export function getRelatedPosts(post: NewsPost, posts: NewsPost[], limit = 3) {
  const postTags = new Set(post.data.tags.map((tag) => slugify(tag)));

  return posts
    .filter((item) => item.id !== post.id)
    .map((item) => {
      let score = 0;
      if (item.data.category === post.data.category) score += 4;
      for (const tag of item.data.tags) {
        if (postTags.has(slugify(tag))) score += 2;
      }
      if (item.data.trending) score += 0.5;
      return { item, score };
    })
    .filter(({ score }) => score > 0)
    .sort((a, b) => {
      if (b.score !== a.score) return b.score - a.score;
      return b.item.data.publishedAt.getTime() - a.item.data.publishedAt.getTime();
    })
    .slice(0, limit)
    .map(({ item }) => item);
}
