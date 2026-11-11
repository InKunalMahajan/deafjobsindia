<x-layouts.site :title="$article->title" :description="$article->description">
    <article class="mx-auto max-w-3xl px-5 py-14 sm:px-8">
        <a href="{{ route('latest') }}" class="text-sm font-semibold underline underline-offset-4">Back to latest updates</a>
        <p class="mt-9 text-xs font-semibold uppercase tracking-[0.14em] text-neutral-600">{{ $article->category }}</p>
        <h1 class="mt-3 text-4xl font-bold leading-tight tracking-tight sm:text-5xl">{{ $article->title }}</h1>
        <p class="mt-5 text-lg leading-8 text-neutral-600">{{ $article->description }}</p>
        <div class="mt-7 flex flex-wrap gap-4 border-y border-neutral-200 py-5 text-sm text-neutral-600">
            <span>Published {{ $article->published_at?->format('d M Y') }}</span>
            @if($article->location)<span>Location: {{ $article->location }}</span>@endif
            @if($article->deadline)
                <span class="font-semibold text-neutral-900">
                    Deadline: {{ $article->deadline->format('d M Y') }}
                    @if($article->is_closed) (Closed) @endif
                </span>
            @endif
        </div>
        <div class="prose prose-neutral mt-10 max-w-none leading-relaxed [&_a]:underline [&_h2]:mt-8 [&_h2]:text-2xl [&_h2]:font-bold [&_h3]:mt-6 [&_h3]:text-xl [&_h3]:font-semibold [&_p]:mb-5 [&_ul]:mb-5 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:mb-5 [&_ol]:list-decimal [&_ol]:pl-6 [&_table]:w-full [&_table]:text-sm [&_th]:border [&_th]:p-2 [&_td]:border [&_td]:p-2">
            {!! \Illuminate\Support\Str::markdown($article->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
        </div>
        @if($article->source_url && str_starts_with($article->source_url, 'https://'))
            <aside class="mt-10 rounded-lg border border-neutral-200 bg-neutral-50 p-6">
                <h2 class="font-semibold">Official or cited source</h2>
                <p class="mt-2 text-sm text-neutral-600">{{ $article->source_name ?? 'Source document' }}</p>
                <a class="mt-3 inline-block break-all text-sm font-semibold underline underline-offset-4" href="{{ $article->source_url }}" target="_blank" rel="noopener noreferrer">Read source</a>
            </aside>
        @endif
        @if($article->action_url && str_starts_with($article->action_url, 'https://'))
            <div class="mt-7">
                @if($article->is_closed)
                    <p class="rounded-lg border border-neutral-300 bg-neutral-50 p-4 text-sm font-medium">The listed application deadline has passed. Check the official source for changes or extensions.</p>
                @else
                    <flux:button variant="primary" href="{{ $article->action_url }}" target="_blank">{{ $article->action_label ?: 'Visit official application page' }}</flux:button>
                @endif
            </div>
        @endif
    </article>
</x-layouts.site>
