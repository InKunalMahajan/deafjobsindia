<x-layouts.site title="My applications" robots="noindex,nofollow">
<section class="dji-container py-12">
<h1 class="text-3xl font-bold">My application tracker</h1>
<p class="mt-3 text-neutral-700">These are your own records. Employer application status is not automatically verified.</p>
<form method="GET" action="{{ route('seeker.applications') }}" class="mt-6 flex flex-wrap items-end gap-3">
<label class="grid gap-2">Filter by status<select name="status" class="min-h-12 rounded-lg border border-neutral-500 p-3">
<option value="">All statuses</option>
@foreach(['interested','applied','interview','offer','rejected','withdrawn'] as $choice)
<option value="{{ $choice }}" @selected($status===$choice)>{{ ucfirst($choice) }}</option>
@endforeach</select></label>
<button class="min-h-12 rounded-lg bg-black px-5 text-white" type="submit">Filter</button></form>
<div class="mt-6 grid gap-4">
@forelse($tracked as $item)
@php($job=$jobs->get($item->job_listing_id))
@if($job)
<article class="rounded-xl border border-neutral-300 p-5">
<h2 class="text-lg font-bold"><a class="underline" href="{{ route('jobs.show',$job->slug) }}">{{ $job->title }}</a></h2>
<p class="mt-2">{{ $job->employer?->name }} · {{ $job->location }}</p>
<p class="mt-2">Your status: <strong>{{ ucfirst($item->status) }}</strong></p>
<p class="mt-1 text-sm">Last updated: {{ $item->updated_at }}</p>
<a class="mt-3 inline-block font-semibold underline" href="{{ route('seeker.applications.history',$job) }}">View status history</a>
</article>
@endif
@empty<p>No applications recorded. Browse jobs and add your own tracking status.</p>@endforelse
</div><div class="mt-6">{{ $tracked->links() }}</div>
</section></x-layouts.site>
