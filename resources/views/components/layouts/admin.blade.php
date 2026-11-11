@props(['title'=>'Administration'])
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="robots" content="noindex,nofollow"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $title }} | DeafJobsIndia Admin</title>
@vite(['resources/css/app.css','resources/js/app.js'])
@livewireStyles
@fluxAppearance
</head>
<body class="min-h-screen bg-white text-neutral-950">
<a class="sr-only focus:not-sr-only" href="#admin-main">Skip to main content</a>
<header class="border-b border-neutral-200">
<div class="dji-container flex flex-wrap items-center justify-between gap-4 py-5">
<a href="{{ route('admin.dashboard') }}" class="font-bold">DeafJobsIndia / Admin</a>
<nav aria-label="Admin navigation" class="flex flex-wrap items-center gap-4 text-sm">
<a href="{{ route('admin.dashboard') }}" class="underline">Dashboard</a>
@can('employers.manage')<a href="{{ route('admin.employers.index') }}" class="underline">Employers</a>@endcan
@can('articles.edit')<a href="{{ route('admin.articles.index') }}" class="underline">Articles</a>@endcan
@can('jobs.edit')<a href="{{ route('admin.jobs.index') }}" class="underline">Jobs</a>@endcan
@can('users.manage')<a href="{{ route('admin.users.index') }}" class="underline">Users</a>@endcan
<a href="{{ route('home') }}" class="underline">Public site</a>
<form method="POST" action="{{ route('admin.logout') }}">@csrf <button class="rounded border border-neutral-900 px-3 py-2 font-semibold" type="submit">Sign out</button></form>
</nav>
</div></header>
<main id="admin-main" class="dji-container min-w-0 py-10">
<h1 class="mb-7 text-3xl font-bold">{{ $title }}</h1>
@if(session('status'))<p role="status" class="mb-5 rounded border border-neutral-300 p-4">{{ session('status') }}</p>@endif
@if($errors->any())<div role="alert" class="mb-5 rounded border border-neutral-900 p-4"><strong>Please fix:</strong><ul class="list-disc pl-6">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
{{ $slot }}
</main>
@livewireScripts
@fluxScripts
</body></html>
