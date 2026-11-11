<x-layouts.site :title="$category ?? 'Latest Updates'">
    <section class="mx-auto max-w-6xl px-5 py-16 sm:px-8">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-600">DeafJobsIndia newsroom</p>
        <h1 class="mt-3 text-4xl font-bold tracking-tight">{{ $category ?? 'Latest Updates' }}</h1>
        <p class="mt-4 max-w-2xl text-neutral-600">Browse recruitment updates and verified information. Check each announcement's official source and closing date.</p>
        <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse($articles as $article)
                <x-article-card :article="$article" />
            @empty
                <p class="col-span-full rounded-lg border border-neutral-200 p-7">No published articles in this section yet.</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $articles->links() }}</div>
    </section>
</x-layouts.site>
