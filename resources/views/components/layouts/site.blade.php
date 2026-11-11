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
<body class="flex min-h-screen flex-col bg-white text-neutral-900 antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:bg-white focus:p-3 focus:text-black">Skip to main content</a>
    <x-site.header />
    <main id="main-content" tabindex="-1" class="w-full flex-1">{{ $slot }}</main>
    <x-site.footer />
    @livewireScripts
    @fluxScripts
</body>
</html>
