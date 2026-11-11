@props(['title' => 'DeafJobsIndia', 'description' => 'Inclusive employment and career information for Deaf and Hard of Hearing job seekers across India.'])
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }} | DeafJobsIndia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-neutral-900">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:bg-white focus:p-3 focus:text-black">
        Skip to main content
    </a>

    <header class="border-b border-neutral-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-6 px-5 py-5 sm:px-8">
            <a href="{{ route('home') }}" class="shrink-0 text-xl font-bold tracking-tight" aria-label="DeafJobsIndia home">
                DeafJobsIndia<span class="text-neutral-500">.</span>
            </a>
            <nav aria-label="Primary navigation" class="hidden items-center gap-7 text-sm font-medium md:flex">
                <a class="hover:underline" href="{{ route('home') }}">Home</a>
                <a class="hover:underline" href="{{ route('jobs.index') }}">Jobs</a>
                <a class="hover:underline" href="{{ route('latest') }}">Latest News</a>
                <a class="hover:underline" href="{{ route('about') }}">About</a>
                <a class="hover:underline" href="{{ route('contact') }}">Contact</a>
            </nav>
            <details class="relative md:hidden">
                <summary class="cursor-pointer rounded-md border border-neutral-300 px-4 py-2 text-sm font-semibold">Menu</summary>
                <nav aria-label="Mobile navigation" class="absolute right-0 z-30 mt-2 w-56 space-y-1 rounded-lg border border-neutral-200 bg-white p-2 shadow-sm">
                    <a class="block rounded p-3 hover:bg-neutral-100" href="{{ route('home') }}">Home</a>
                    <a class="block rounded p-3 hover:bg-neutral-100" href="{{ route('jobs.index') }}">Jobs</a>
                    <a class="block rounded p-3 hover:bg-neutral-100" href="{{ route('latest') }}">Latest News</a>
                    <a class="block rounded p-3 hover:bg-neutral-100" href="{{ route('about') }}">About</a>
                    <a class="block rounded p-3 hover:bg-neutral-100" href="{{ route('contact') }}">Contact</a>
                </nav>
            </details>
        </div>
    </header>

    <main id="main-content" tabindex="-1" class="min-h-[60vh]">
        {{ $slot }}
    </main>

    <footer class="border-t border-neutral-200 bg-neutral-50">
        <div class="mx-auto flex max-w-6xl flex-col justify-between gap-5 px-5 py-9 text-sm text-neutral-600 sm:px-8 md:flex-row">
            <p>&copy; {{ date('Y') }} DeafJobsIndia. Inclusive careers for everyone.</p>
            <nav aria-label="Footer navigation" class="flex flex-wrap gap-5">
                <a class="hover:underline" href="{{ route('about') }}">About</a>
                <a class="hover:underline" href="{{ route('privacy') }}">Privacy</a>
                <a class="hover:underline" href="{{ route('disclaimer') }}">Disclaimer</a>
                <a class="hover:underline" href="{{ route('rss') }}">RSS</a>
            </nav>
        </div>
    </footer>

    @livewireScripts
    @fluxScripts
</body>
</html>
