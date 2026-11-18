<div class="mt-8">
  <div class="grid gap-4 rounded-xl border border-neutral-200 bg-neutral-50 p-4 sm:grid-cols-[2fr_1fr] sm:p-6">
    <flux:input wire:model.live.debounce.400ms="query" label="Search articles" placeholder="Title, topic or location" />
    <flux:select wire:model.live="category" label="Category">
      <flux:select.option value="">All categories</flux:select.option>
      @foreach(['Jobs','Education','Training','Career','Government Schemes','Job Fairs','Community','Technology'] as $categoryOption)
        <flux:select.option value="{{ $categoryOption }}">{{ $categoryOption }}</flux:select.option>
      @endforeach
    </flux:select>
    <div class="sm:col-span-2">
      <flux:button variant="ghost" wire:click="clearFilters">Clear filters</flux:button>
    </div>
  </div>
  <p class="mt-6 text-sm text-neutral-600" role="status" aria-live="polite">{{ $articles->total() }} {{ $articles->total() === 1 ? 'article' : 'articles' }} found</p>
  <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
    @forelse($articles as $article)
      <x-article-card :article="$article" wire:key="article-{{ $article->id }}" />
    @empty
      <div class="col-span-full"><x-ui.empty-state title="No matching articles" description="Try another keyword or category."/></div>
    @endforelse
  </div>
  <div class="mt-8">{{ $articles->links() }}</div>
</div>
