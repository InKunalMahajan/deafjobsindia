<header class="sticky top-0 z-40 border-b border-neutral-200 bg-white">
<div class="dji-container flex min-h-20 items-center justify-between gap-4">
<a href="{{ route('home') }}" class="text-xl font-extrabold tracking-tight">DeafJobsIndia.</a>
<nav aria-label="Primary navigation" class="hidden items-center gap-5 text-sm font-semibold lg:flex">
@foreach(['home'=>'Home','jobs.index'=>'Jobs','latest'=>'Latest News','about'=>'About','contact'=>'Contact'] as $route=>$label)
<x-site.nav-link :href="route($route)" :active="request()->routeIs($route)">{{ $label }}</x-site.nav-link>
@endforeach
@foreach(['education'=>'Education','training'=>'Training','career'=>'Career'] as $slug=>$label)
<x-site.nav-link :href="route('section.show',$slug)" :active="request()->is($slug)">{{ $label }}</x-site.nav-link>
@endforeach
</nav>
<details class="relative lg:hidden"><summary class="cursor-pointer rounded-lg border border-neutral-300 px-4 py-2 font-semibold">Menu</summary>
<nav aria-label="Mobile navigation" class="absolute right-0 z-30 mt-2 w-64 rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
@foreach(['home'=>'Home','jobs.index'=>'Jobs','latest'=>'Latest News','about'=>'About','contact'=>'Contact','search'=>'Search'] as $route=>$label)
<x-site.nav-link mobile :href="route($route)" :active="request()->routeIs($route)">{{ $label }}</x-site.nav-link>
@endforeach
@foreach(['education'=>'Education','training'=>'Training','career'=>'Career'] as $slug=>$label)
<x-site.nav-link mobile :href="route('section.show',$slug)" :active="request()->is($slug)">{{ $label }}</x-site.nav-link>
@endforeach
</nav></details>
</div></header>