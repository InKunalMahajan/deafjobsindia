<x-layouts.site title="Recommended jobs" robots="noindex,nofollow">
<section class="dji-container py-12"><h1 class="text-3xl font-bold">Recommended jobs</h1>
<p class="mt-3 text-neutral-700">Results use your preferred location, work mode and skills. They are simple matches, not employer decisions.</p>
<p class="mt-4"><a class="underline" href="{{ route('seeker.profile') }}">Update job preferences</a></p>
<div class="mt-6 grid gap-4">
@forelse($jobs as $job)
<article class="rounded-xl border border-neutral-300 p-5"><h2 class="text-xl font-bold"><a class="underline" href="{{ route('jobs.show',$job->slug) }}">{{ $job->title }}</a></h2>
<p class="mt-2">{{ $job->employer?->name }} · {{ $job->location }}</p>
<form class="mt-3" method="POST" action="{{ route('seeker.jobs.save',$job) }}">@csrf<button class="rounded-lg border border-black px-4 py-2" type="submit">Save job</button></form>
</article>
@empty<p>No matching jobs found. Try changing your preferences.</p>@endforelse
</div><div class="mt-6">{{ $jobs->links() }}</div></section></x-layouts.site>
