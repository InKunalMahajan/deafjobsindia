<x-layouts.site :title="$article->title" :description="$article->description">
<article class="dji-container py-10 sm:py-16">
  <div class="mx-auto max-w-3xl">
    <a href="{{ route('latest') }}" class="text-sm font-semibold underline underline-offset-4">Back to latest updates</a>
    <p class="mt-8 text-xs font-semibold uppercase tracking-widest text-neutral-600">{{ $article->category }}</p>
    <h1 class="mt-3 break-words text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl">{{ $article->title }}</h1>
    <p class="mt-5 break-words text-lg leading-8 text-neutral-600">{{ $article->description }}</p>
    <div class="mt-7 flex flex-wrap gap-4 border-y border-neutral-200 py-5 text-sm text-neutral-600">
      <span>Published {{ $article->published_at?->format('d M Y') }}</span>
      @if($article->location)<span>Location: {{ $article->location }}</span>@endif
      @if($article->deadline)
        <span class="font-semibold text-neutral-900">Deadline: {{ $article->deadline->format('d M Y') }} @if($article->is_closed)(Closed)@endif</span>
      @endif
    </div>
    <div class="dji-prose mt-10">
      {!! \Illuminate\Support\Str::markdown($article->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
    </div>
    @if($article->source_url && filter_var($article->source_url, FILTER_VALIDATE_URL) && str_starts_with($article->source_url, 'https://'))
      <aside class="mt-10 rounded-xl border border-neutral-200 bg-neutral-50 p-5 sm:p-6">
        <h2 class="font-semibold">Original or cited source</h2>
        <p class="mt-2 text-sm text-neutral-600">{{ $article->source_name ?? 'Source document' }}</p>
        <a class="mt-3 inline-block break-all text-sm font-semibold underline underline-offset-4" href="{{ $article->source_url }}" target="_blank" rel="noopener noreferrer">Read source</a>
      </aside>
    @endif
    @if($article->action_url && filter_var($article->action_url, FILTER_VALIDATE_URL) && str_starts_with($article->action_url, 'https://'))
      <div class="mt-7">
        @if($article->is_closed)
          <p class="rounded-lg border border-neutral-300 bg-neutral-50 p-4 text-sm font-medium">The listed deadline has passed. Check the source for changes or extensions.</p>
        @else
          <flux:button variant="primary" href="{{ $article->action_url }}" target="_blank" rel="noopener noreferrer">{{ $article->action_label ?: 'Visit official page' }}</flux:button>
        @endif
      </div>
    @endif
  </div>
</article>
</x-layouts.site>
