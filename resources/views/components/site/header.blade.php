<header class="sticky top-0 z-40 border-b border-neutral-200 bg-white">
    <div class="dji-container flex min-h-16 items-center justify-between gap-3">
        <a href="{{ route('home') }}" class="rounded-md text-lg font-extrabold tracking-tight text-neutral-950 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black sm:text-xl" aria-label="DeafJobsIndia home">DeafJobsIndia.</a>
        <nav aria-label="Primary navigation" class="hidden items-center gap-1 lg:flex">
            @foreach(['jobs.index'=>'Find Jobs','employers.index'=>'Employers','latest'=>'News & Updates','search'=>'Search articles','about'=>'About','contact'=>'Contact'] as $route=>$label)
                <x-site.nav-link :href="route($route)" :active="request()->routeIs($route)">{{ $label }}</x-site.nav-link>
            @endforeach
        </nav>
        <div class="hidden items-center gap-2 lg:flex">
            @if(auth()->check() && auth()->user()->role === 'job_seeker')
                <x-site.nav-link :href="route('seeker.dashboard')" :active="request()->routeIs('seeker.*')">My account</x-site.nav-link>
            @elseif(auth()->check() && auth()->user()->role === 'employer')
                <x-site.nav-link :href="route('employer.dashboard')" :active="request()->routeIs('employer.*')">Employer dashboard</x-site.nav-link>
            @elseif(auth()->guest())
                <x-site.nav-link :href="route('seeker.login')" :active="request()->routeIs('seeker.login')">Job-seeker login</x-site.nav-link>
            @endif
        </div>
        <details class="relative shrink-0 lg:hidden">
            <summary class="flex min-h-11 cursor-pointer list-none items-center gap-2 rounded-lg border border-neutral-500 px-4 py-2 text-sm font-semibold text-neutral-950 hover:bg-neutral-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                <span>Menu</span>
                <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </summary>
            <nav aria-label="Mobile navigation" class="absolute right-0 z-50 mt-2 grid w-[min(20rem,calc(100vw-2.5rem))] max-h-[calc(100dvh-6rem)] gap-1 overflow-y-auto rounded-xl border border-neutral-300 bg-white p-3 shadow-lg">
                @foreach(['home'=>'Home','jobs.index'=>'Find Jobs','employers.index'=>'Employers','latest'=>'News & Updates','search'=>'Search articles','about'=>'About','contact'=>'Contact'] as $route=>$label)
                    <x-site.nav-link mobile :href="route($route)" :active="request()->routeIs($route)">{{ $label }}</x-site.nav-link>
                @endforeach
                @if(auth()->check() && auth()->user()->role === 'job_seeker')
                    <x-site.nav-link mobile :href="route('seeker.dashboard')" :active="request()->routeIs('seeker.*')">My account</x-site.nav-link>
                @elseif(auth()->check() && auth()->user()->role === 'employer')
                    <x-site.nav-link mobile :href="route('employer.dashboard')" :active="request()->routeIs('employer.*')">Employer dashboard</x-site.nav-link>
                @elseif(auth()->guest())
                    <x-site.nav-link mobile :href="route('seeker.login')" :active="request()->routeIs('seeker.login')">Job-seeker login</x-site.nav-link>
                @endif
                <div class="my-1 border-t border-neutral-200" role="separator"></div>
                <x-site.nav-link mobile :href="route('section.show','career')" :active="request()->is('career')">Career resources</x-site.nav-link>
                <x-site.nav-link mobile :href="route('section.show','training')" :active="request()->is('training')">Training</x-site.nav-link>
            </nav>
        </details>
    </div>
</header>
