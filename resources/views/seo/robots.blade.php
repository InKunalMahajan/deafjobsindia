User-agent: *
Disallow: /admin/
Disallow: /login
Disallow: /search
@if(app()->environment('production'))
Sitemap: {{ route('sitemap') }}
@else
Disallow: /
@endif