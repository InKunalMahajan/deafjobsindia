@props(['article'])
<x-ui.card {{ $attributes->class(['flex h-full flex-col transition-colors hover:border-neutral-500']) }}>
    <div class="flex flex-wrap items-center gap-2">
        <x-ui.badge>{{ $article->category }}</x-ui.badge>
        @if($article->deadline && $article->is_closed)
            <x-ui.badge variant="outline">Application closed</x-ui.badge>
        @endif
    </div>
    <h3 class="mt-4 text-lg font-bold leading-snug tracking-tight">
        <a class="hover:underline" href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a>
    </h3>
    <p class="mt-3 flex-1 text-sm leading-6 text-neutral-600">{{ $article->description }}</p>
    <div class="mt-5 flex flex-wrap justify-between gap-3 border-t border-neutral-100 pt-4 text-xs text-neutral-600">
        <span>{{ $article->published_at?->format('d M Y') }}</span>
        <a class="font-semibold text-black underline underline-offset-4" href="{{ route('news.show', $article->slug) }}">Read details</a>
    </div>
</x-ui.card>
