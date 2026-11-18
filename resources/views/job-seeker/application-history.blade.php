<x-layouts.site title="Application history" robots="noindex,nofollow">
<section class="dji-container max-w-3xl py-12">
<a class="underline" href="{{ route('seeker.applications') }}">Back to applications</a>
<h1 class="mt-5 text-3xl font-bold">Application history</h1>
<h2 class="mt-3 text-xl">{{ $job->title }}</h2>
<p class="mt-3 text-neutral-700">This history records your manual updates, not employer decisions.</p>
<div class="mt-6 grid gap-3">
@forelse($history as $entry)
<div class="rounded-xl border border-neutral-300 p-4">
<p class="font-bold">{{ ucfirst($entry->status) }}</p>
<p class="mt-1 text-sm text-neutral-700">{{ $entry->created_at }}</p>
@if($entry->notes)<p class="mt-2 whitespace-pre-line break-words">{{ $entry->notes }}</p>@endif
</div>
@empty<p>No status changes recorded yet.</p>@endforelse
</div><div class="mt-6">{{ $history->links() }}</div>
</section></x-layouts.site>
