<div class="mt-9">
    <div class="grid gap-4 rounded-xl border border-neutral-200 bg-neutral-50 p-5 sm:grid-cols-[2fr_1fr]">
        <flux:input wire:model.live.debounce.400ms="query" label="Search" placeholder="Job title, skill or location" />
        <flux:select wire:model.live="category" label="Category">
            <flux:select.option value="">All categories</flux:select.option>
            <flux:select.option value="Jobs">Jobs</flux:select.option>
            <flux:select.option value="Education">Education</flux:select.option>
            <flux:select.option value="Training">Training</flux:select.option>
            <flux:select.option value="Career">Career</flux:select.option>
            <flux:select.option value="Government Schemes">Government Schemes</flux:select.option>
            <flux:select.option value="Job Fairs">Job Fairs</flux:select.option>
            <flux:select.option value="Community">Community</flux:select.option>
            <flux:select.option value="Technology">Technology</flux:select.option>
        </flux:select>
    </div>
    <p class="mt-6 text-sm text-neutral-600" role="status" aria-live="polite">{{ $articles->total() }} result(s)</p>
    <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($articles as $article)
            <x-article-card :article="$article" wire:key="article-{{ $article->id }}" />
        @empty
            <p class="col-span-full rounded-lg border border-neutral-200 p-7 text-neutral-600">No matching results. Try another keyword or category.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $articles->links() }}</div>
</div>
