<x-layouts.site title="Inclusive Jobs and Careers" description="Verified recruitment information, jobs and career resources for Deaf and Hard of Hearing candidates in India.">
    <section class="border-b border-neutral-200 px-5 py-20 sm:px-8 md:py-28">
        <div class="mx-auto max-w-5xl text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-neutral-600">Connecting Deaf talent with opportunities</p>
            <h1 class="mx-auto mt-6 max-w-4xl text-5xl font-bold leading-tight tracking-tight sm:text-6xl lg:text-7xl">
                Find work.<br>Build your future.
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-neutral-600 sm:text-lg">
                Explore accessible recruitment updates, career resources and inclusive opportunities for Deaf and Hard of Hearing job seekers across India.
            </p>
            <div class="mt-9 flex flex-wrap justify-center gap-3">
                <flux:button variant="primary" href="{{ route('jobs.index') }}">Find Jobs</flux:button>
                <flux:button href="{{ route('latest') }}">Latest Updates</flux:button>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-5 py-16 sm:px-8">
        <div class="mb-7 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-neutral-600">Explore</p>
                <h2 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">Opportunities and resources</h2>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('jobs.index') }}" class="rounded-xl border border-neutral-200 p-7 hover:border-black">
                <p class="text-xs font-semibold uppercase text-neutral-500">01</p>
                <h3 class="mt-5 text-xl font-semibold">Find Jobs</h3>
                <p class="mt-2 text-sm leading-6 text-neutral-600">Browse recruitment announcements and application information.</p>
                <span class="mt-7 inline-block text-sm font-semibold underline underline-offset-4">Explore jobs</span>
            </a>
            <a href="/training" class="rounded-xl border border-neutral-200 p-7 hover:border-black">
                <p class="text-xs font-semibold uppercase text-neutral-500">02</p>
                <h3 class="mt-5 text-xl font-semibold">Training</h3>
                <p class="mt-2 text-sm leading-6 text-neutral-600">Discover skills, courses and learning opportunities.</p>
                <span class="mt-7 inline-block text-sm font-semibold underline underline-offset-4">Explore training</span>
            </a>
            <a href="/career" class="rounded-xl border border-neutral-200 p-7 hover:border-black">
                <p class="text-xs font-semibold uppercase text-neutral-500">03</p>
                <h3 class="mt-5 text-xl font-semibold">Career Guidance</h3>
                <p class="mt-2 text-sm leading-6 text-neutral-600">Understand career paths, qualifications and applications.</p>
                <span class="mt-7 inline-block text-sm font-semibold underline underline-offset-4">View resources</span>
            </a>
        </div>
    </section>

    <section class="border-t border-neutral-200 bg-neutral-50">
        <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8">
            <div class="mb-8 flex items-center justify-between gap-4">
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Latest updates</h2>
                <a href="{{ route('latest') }}" class="text-sm font-semibold underline underline-offset-4">View all</a>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse($articles as $article)
                    <x-article-card :article="$article" />
                @empty
                    <p class="col-span-full rounded-xl border border-neutral-200 bg-white p-8 text-neutral-600">
                        Articles will appear here after the news import and editorial review.
                    </p>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.site>
