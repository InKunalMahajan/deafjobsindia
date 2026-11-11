<header class="border-b border-neutral-200 bg-white">
    <div class="dji-container flex min-h-20 items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="shrink-0 text-xl font-extrabold tracking-tight" aria-label="DeafJobsIndia home">DeafJobsIndia<span class="text-neutral-500">.</span></a>
        <nav aria-label="Primary navigation" class="hidden items-center gap-7 text-sm font-semibold md:flex">
            <x-site.nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-site.nav-link>
            <x-site.nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.index')">Jobs</x-site.nav-link>
            <x-site.nav-link :href="route('latest')" :active="request()->routeIs('latest')">Latest News</x-site.nav-link>
            <x-site.nav-link :href="route('about')" :active="request()->routeIs('about')">About</x-site.nav-link>
            <x-site.nav-link :href="route('contact')" :active="request()->routeIs('contact')">Contact</x-site.nav-link>
        </nav>
        <details class="relative md:hidden">
            <summary class="cursor-pointer rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold">Menu</summary>
            <nav aria-label="Mobile navigation" class="absolute right-0 z-30 mt-2 w-56 space-y-1 rounded-xl border border-neutral-200 bg-white p-2 shadow-lg">
                <x-site.nav-link mobile :href="route('home')" :active="request()->routeIs('home')">Home</x-site.nav-link>
                <x-site.nav-link mobile :href="route('jobs.index')" :active="request()->routeIs('jobs.index')">Jobs</x-site.nav-link>
                <x-site.nav-link mobile :href="route('latest')" :active="request()->routeIs('latest')">Latest News</x-site.nav-link>
                <x-site.nav-link mobile :href="route('about')" :active="request()->routeIs('about')">About</x-site.nav-link>
                <x-site.nav-link mobile :href="route('contact')" :active="request()->routeIs('contact')">Contact</x-site.nav-link>
            </nav>
        </details>
    </div>
</header>
