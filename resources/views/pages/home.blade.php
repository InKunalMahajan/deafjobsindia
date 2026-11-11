<x-layouts.site title="Inclusive Jobs and Careers" description="Jobs, career guidance and recruitment updates for Deaf and Hard of Hearing candidates in India.">
    <section class="border-b border-neutral-200 py-20 sm:py-28">
        <div class="dji-container text-center">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-neutral-600">Connecting Deaf talent with opportunities</p>
            <h1 class="mx-auto mt-6 max-w-4xl text-5xl font-extrabold leading-tight tracking-tight sm:text-6xl lg:text-7xl">Find work.<br>Build your future.</h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-neutral-600 sm:text-lg">Explore accessible recruitment updates, career resources and inclusive opportunities for Deaf and Hard of Hearing job seekers across India.</p>
            <div class="mt-9 flex flex-wrap justify-center gap-3">
                <x-ui.button :href="route('jobs.index')">Find Jobs</x-ui.button>
                <x-ui.button variant="secondary" :href="route('latest')">Latest Updates</x-ui.button>
            </div>
        </div>
    </section>
    <section class="dji-container py-16">
        <x-ui.section-heading eyebrow="Explore" title="Opportunities and resources" />
        <div class="mt-8 grid gap-4 md:grid-cols-3">
            @foreach([
                ['01', 'Find Jobs', 'Browse recruitment announcements and application information.', route('jobs.index')],
                ['02', 'Training', 'Discover skills, courses and learning opportunities.', '/training'],
                ['03', 'Career Guidance', 'Understand career paths, qualifications and applications.', '/career'],
            ] as [$number, $heading, $summary, $url])
                <a href="{{ $url }}" class="block rounded-xl border border-neutral-200 p-7 transition-colors hover:border-black">
                    <p class="text-xs font-bold text-neutral-500">{{ $number }}</p>
                    <h3 class="mt-5 text-xl font-bold">{{ $heading }}</h3>
                    <p class="mt-2 text-sm leading-6 text-neutral-600">{{ $summary }}</p>
                    <span class="mt-7 inline-block text-sm font-semibold underline underline-offset-4">Explore</span>
                </a>
            @endforeach
        </div>
    </section>
    <section class="border-t border-neutral-200 bg-neutral-50">
        <div class="dji-container py-16">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <x-ui.section-heading eyebrow="Newsroom" title="Latest updates" />
                <a href="{{ route('latest') }}" class="text-sm font-bold underline underline-offset-4">View all</a>
            </div>
            <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse($articles as $article)
                    <x-article-card :article="$article" />
                @empty
                    <div class="col-span-full">
                        <x-ui.empty-state title="Articles coming soon" description="Updates will appear here after content import and editorial review." />
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.site>
