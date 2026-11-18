<header class="sticky top-0 z-40 border-b border-neutral-200 bg-white">
<div class="dji-container flex min-h-16 items-center justify-between gap-4">
  <a href="{{ route('home') }}" class="break-words text-lg font-extrabold tracking-tight sm:text-xl">DeafJobsIndia.</a>
  <nav aria-label="Primary navigation" class="hidden items-center gap-6 text-sm font-semibold lg:flex">
    @foreach(['jobs.index'=>'Find Jobs','employers.index'=>'Employers','latest'=>'News & Updates','search'=>'Search articles','about'=>'About','contact'=>'Contact'] as $route=>$label)
      <x-site.nav-link :href="route($route)" :active="request()->routeIs($route)">{{ $label }}</x-site.nav-link>
    @endforeach
  </nav>
  <details class="relative shrink-0 lg:hidden">
    <summary class="flex min-h-11 cursor-pointer list-none items-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold">Menu</summary>
    <nav aria-label="Mobile navigation" class="absolute right-0 z-50 mt-2 grid w-[min(18rem,calc(100vw-2.5rem))] gap-1 rounded-xl border border-neutral-200 bg-white p-3 shadow-lg">
      @foreach(['home'=>'Home','jobs.index'=>'Find Jobs','employers.index'=>'Employers','latest'=>'News & Updates','search'=>'Search articles','about'=>'About','contact'=>'Contact'] as $route=>$label)
        <x-site.nav-link mobile :href="route($route)" :active="request()->routeIs($route)">{{ $label }}</x-site.nav-link>
      @endforeach
      <x-site.nav-link mobile :href="route('section.show','career')" :active="request()->is('career')">Career resources</x-site.nav-link>
      <x-site.nav-link mobile :href="route('section.show','training')" :active="request()->is('training')">Training</x-site.nav-link>
    </nav>
  </details>
</div></header>