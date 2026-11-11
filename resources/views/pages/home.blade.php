<x-layouts.site title="Inclusive Jobs and Careers">
<section class="border-b border-neutral-200 py-20 sm:py-28"><div class="dji-container text-center">
<p class="text-xs font-bold uppercase tracking-widest text-neutral-600">Inclusive careers in India</p>
<h1 class="mx-auto mt-6 max-w-4xl text-5xl font-extrabold tracking-tight sm:text-7xl">Find work.<br>Build your future.</h1>
<p class="mx-auto mt-6 max-w-2xl leading-8 text-neutral-600">Jobs, skills and career opportunities for Deaf and Hard of Hearing people.</p>
<div class="mt-8 flex flex-wrap justify-center gap-3"><x-ui.button :href="route('jobs.index')">Explore Jobs</x-ui.button><x-ui.button variant="secondary" :href="route('latest')">Latest News</x-ui.button></div>
<form method="GET" action="{{ route('search') }}" role="search" class="mx-auto mt-9 flex max-w-xl flex-col gap-2 sm:flex-row"><label class="sr-only" for="home-search">Search jobs and news</label><input id="home-search" name="q" type="search" placeholder="Search jobs and news" class="min-h-11 flex-1 rounded-lg border border-neutral-300 px-4"><x-ui.button type="submit">Search</x-ui.button></form>
</div></section>
<section class="dji-container py-16"><x-ui.section-heading eyebrow="Explore" title="Opportunities and resources"/>
<div class="mt-8 grid gap-4 md:grid-cols-3">
@foreach([['Find Jobs','Browse recruitment notices.',route('jobs.index')],['Education and Training','Explore learning opportunities.',route('section.show','training')],['Career Guidance','Plan your next step.',route('section.show','career')]] as [$title,$description,$url])
<a href="{{ $url }}" class="rounded-xl border border-neutral-200 p-7 hover:border-black"><h3 class="text-xl font-bold">{{ $title }}</h3><p class="mt-3 text-sm text-neutral-600">{{ $description }}</p><span class="mt-5 inline-block text-sm font-semibold underline">Explore</span></a>
@endforeach
</div></section>
<section class="border-t border-neutral-200 bg-neutral-50"><div class="dji-container py-16"><div class="flex items-end justify-between gap-4"><x-ui.section-heading eyebrow="Newsroom" title="Latest opportunities"/><a class="text-sm font-semibold underline" href="{{ route('latest') }}">View all</a></div>
<div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">@forelse($articles as $article)<x-article-card :article="$article"/>@empty<div class="col-span-full"><x-ui.empty-state title="Articles coming soon" description="Updates will appear after editorial review."/></div>@endforelse</div></div></section>
</x-layouts.site>