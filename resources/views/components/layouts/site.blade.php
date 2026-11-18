@props(['title' => 'DeafJobsIndia', 'description' => 'Inclusive employment and career information for Deaf and Hard of Hearing job seekers across India.', 'canonical' => null, 'robots' => null, 'structuredData' => null])
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }} | DeafJobsIndia</title>
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    <meta property="og:title" content="{{ $title }} | DeafJobsIndia">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="DeafJobsIndia">
    @if($robots || (request()->is('admin*') || request()->is('account*') || request()->is('employer-account*')) || request()->is('search') || request()->is('alerts*') || request()->hasAny(['q', 'page', 'category', 'sort', 'salary', 'experience', 'featuredOnly']))<meta name="robots" content="{{ $robots ?? ((request()->is('admin*') || request()->is('account*') || request()->is('employer-account*')) ? 'noindex,nofollow' : 'noindex,follow') }}">@endif
    @if($structuredData)
    <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @endif
    <style>
        :focus-visible { outline: 3px solid #111; outline-offset: 3px; }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-white text-neutral-900 antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:border focus:border-black focus:bg-white focus:p-3 focus:text-black">Skip to main content</a>
    <x-site.header />
    <main id="main-content" tabindex="-1" class="w-full flex-1">{{ $slot }}</main>
    <x-site.footer />
    @livewireScripts
    @fluxScripts
</body>
</html>
