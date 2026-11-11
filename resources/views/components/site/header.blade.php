<header class="sticky top-0 z-40 border-b border-neutral-200 bg-white/95 backdrop-blur">
<div class="dji-container flex min-h-18 items-center justify-between gap-5">
<a href="{{ route('home') }}" class="text-xl font-extrabold tracking-tight" aria-label="DeafJobsIndia home">DeafJobsIndia<span aria-hidden="true">.</span></a>
<nav aria-label="Primary navigation" class="hidden items-center gap-6 text-sm font-semibold lg:flex">
@foreach(['jobs.index'=>'Find Jobs','employers.index'=>'Employers','latest'=>'News & Updates','about'=>'About','contact'=>'Contact'] as $route=>$label)
<x-site.nav-link :href="route($route)" :active="request()->routeIs($route)">{{ $label }}</x-site.nav-link>
@endforeach
<a href="{{ route('section.show','career') }}" class="rounded-lg border border-neutral-900 px-4 py-2 hover:bg-neutral-100">Career Resources</a>
</nav>
<details class="relative lg:hidden"><summary class="cursor-pointer rounded-lg border border-neutral-300 px-4 py-2 font-semibold">Menu</summary>
<nav aria-label="Mobile navigation" class="absolute right-0 z-30 mt-2 w-64 rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
@foreach(['home'=>'Home','jobs.index'=>'Find Jobs','employers.index'=>'Employers','latest'=>'News & Updates','about'=>'About','contact'=>'Contact'] as $route=>$label)
<x-site.nav-link mobile :href="route($route)" :active="request()->routeIs($route)">{{ $label }}</x-site.nav-link>
@endforeach
<x-site.nav-link mobile :href="route('section.show','career')" :active="request()->is('career')">Career Resources</x-site.nav-link>
<x-site.nav-link mobile :href="route('section.show','training')" :active="request()->is('training')">Training</x-site.nav-link>
</nav></details>
</div></header>