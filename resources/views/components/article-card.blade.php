@props(['article'])
<article class="flex h-full flex-col rounded-xl border border-neutral-200 bg-white p-6 transition hover:border-neutral-500">
    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wide text-neutral-600">
        <span>{{ $article->category }}</span>
        @if($article->deadline && $article->is_closed)
            <span class="rounded-md border border-neutral-300 px-2 py-1">Application closed</span>
        @endif
    </div>
    <h3 class="mt-4 text-lg font-semibold leading-snug tracking-tight">
        <a class="hover:underline" href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a>
    </h3>
    <p class="mt-3 flex-1 text-sm leading-6 text-neutral-600">{{ $article->description }}</p>
    <div class="mt-5 flex flex-wrap justify-between gap-3 border-t border-neutral-100 pt-4 text-xs text-neutral-600">
        <span>{{ $article->published_at?->format('d M Y') }}</span>
        <a class="font-semibold text-black underline underline-offset-4" href="{{ route('news.show', $article->slug) }}">Read details</a>
    </div>
</article>
