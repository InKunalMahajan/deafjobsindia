import type { APIRoute } from 'astro';

export const GET: APIRoute = ({ site }) => {
  const base = site ?? new URL('https://deafjobsindia.in');
  const sitemapURL = new URL('sitemap-index.xml', base);

  return new Response(
    [
      'User-agent: *',
      'Allow: /',
      '',
      `Sitemap: ${sitemapURL.href}`,
      '',
    ].join('\n'),
    {
      headers: { 'Content-Type': 'text/plain; charset=utf-8' },
    },
  );
};
