import type { APIRoute } from 'astro';

export const GET: APIRoute = ({ site }) => {
  const base = site ?? new URL('https://www.deafjobsindia.com');
  const sitemapURL = new URL('sitemap-index.xml', base);

  return new Response(`User-agent: *\nAllow: /\n\nSitemap: ${sitemapURL.href}\n`, {
    headers: { 'Content-Type': 'text/plain; charset=utf-8' },
  });
};
