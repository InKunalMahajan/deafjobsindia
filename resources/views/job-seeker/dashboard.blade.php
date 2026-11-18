<x-layouts.site title="My job-seeker dashboard" robots="noindex,nofollow">
<section class="dji-container py-12">
<h1 class="text-3xl font-bold">My job-seeker dashboard</h1>
<p class="mt-3">Welcome, {{ auth()->user()->name }}. Your saved jobs and personal application notes are private.</p>
<div class="mt-5 flex flex-wrap gap-4"><a class="underline" href="{{ route('seeker.profile') }}">Edit profile and resume</a><a class="underline" href="{{ route('seeker.recommendations') }}">Recommended jobs</a><a class="underline" href="{{ route('seeker.alerts') }}">Job alerts</a><a class="underline" href="{{ route('seeker.applications') }}">Manual application tracker</a><a class="underline" href="{{ route('seeker.submitted') }}">Submitted applications</a><a class="underline" href="{{ route('seeker.privacy') }}">Privacy and my data</a><a class="underline" href="{{ route('jobs.index') }}">Find jobs</a>
<form method="POST" action="{{ route('seeker.logout') }}">@csrf<button type="submit" class="underline">Sign out</button></form></div>
@if(session('status'))<p role="status" class="mt-4">{{ session('status') }}</p>@endif
@if($errors->any())<p role="alert" class="mt-4">{{ $errors->first() }}</p>@endif
<h2 class="mt-10 text-2xl font-bold">Saved jobs and application tracker</h2>
<p class="mt-2 text-sm text-neutral-700">Tracking is manual. Complete applications on the official employer website.</p>
<div class="mt-6 grid gap-5">
@forelse($jobs as $job)
<article class="rounded-xl border border-neutral-300 p-5">
<h3 class="text-xl font-bold"><a class="underline" href="{{ route('jobs.show',$job->slug) }}">{{ $job->title }}</a></h3>
<p class="mt-1">{{ $job->employer?->name }}</p>
@if(in_array($job->id,$saved))
<form class="mt-3" method="POST" action="{{ route('seeker.jobs.unsave',$job) }}">@csrf @method('DELETE')<button class="underline" type="submit">Remove saved job</button></form>
@endif
<form method="POST" action="{{ route('seeker.jobs.track',$job) }}" class="mt-4 grid gap-3">@csrf
<label class="grid gap-1">Application status
<select name="status" class="min-h-12 rounded-lg border border-neutral-500 p-2">
@foreach(['interested','applied','interview','offer','rejected','withdrawn'] as $status)
<option value="{{ $status }}" @selected(($tracked[$job->id]->status ?? 'interested')===$status)>{{ ucfirst($status) }}</option>
@endforeach
</select></label>
<label class="grid gap-1">Date applied<input type="date" name="applied_at" class="min-h-12 rounded-lg border border-neutral-500 p-2" value="{{ $tracked[$job->id]->applied_at ?? '' }}"></label>
<label class="grid gap-1">Private notes<textarea name="notes" maxlength="2000" class="rounded-lg border border-neutral-500 p-2" rows="2">{{ $tracked[$job->id]->notes ?? '' }}</textarea></label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Update tracker</button>
@if(isset($tracked[$job->id]))<a class="underline" href="{{ route('seeker.applications.history',$job) }}">View status history</a>@endif
</form></article>
@empty<p>No saved or tracked jobs yet. Browse jobs to get started.</p>@endforelse
</div></section></x-layouts.site>
