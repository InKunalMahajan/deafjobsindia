import rss from '@astrojs/rss';
import { getCollection } from 'astro:content';
import { getPublicPosts } from '../utils/news';

export async function GET(context) {
  const posts = getPublicPosts(await getCollection('news'))
    .sort((a, b) => b.data.publishedAt.getTime() - a.data.publishedAt.getTime());

  return rss({
    title: 'DeafJobsIndia',
    description: 'News, jobs, education, schemes, training and community updates for Deaf India.',
    site: context.site,
    items: posts.map((post) => ({
      title: post.data.title,
      pubDate: post.data.publishedAt,
      description: post.data.description,
      link: `/news/${post.id}/`,
      categories: [post.data.category, ...post.data.tags],
      author: post.data.author,
    })),
    customData: '<language>en-IN</language>',
  });
}
