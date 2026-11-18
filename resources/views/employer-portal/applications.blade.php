<x-layouts.site title="Recruitment applications" robots="noindex,nofollow">
<section class="dji-container py-12"><a class="underline" href="{{ route('employer.dashboard') }}">Back to employer dashboard</a>
<h1 class="mt-5 text-3xl font-bold">Applications: {{ $job->title }}</h1>
<p class="mt-3">Only applications submitted through DeafJobsIndia are shown. Candidate resumes are not shared automatically.</p>
@if(session('status'))<p class="mt-4" role="status">{{ session('status') }}</p>@endif
@if($errors->any())<p class="mt-4" role="alert">{{ $errors->first() }}</p>@endif
<div class="mt-6 grid gap-5">
@forelse($applications as $application)
@php($candidate=$candidates->get($application->user_id))
<article class="rounded-xl border border-neutral-300 p-5">
<h2 class="text-xl font-bold">{{ $candidate?->name ?? 'Candidate' }}</h2>
@if($candidate?->headline)<p class="mt-1">{{ $candidate->headline }}</p>@endif
@if($candidate?->education)<p class="mt-1">Education: {{ $candidate->education }}</p>@endif
@if($candidate?->skills)<p class="mt-1">Skills: {{ $candidate->skills }}</p>@endif
@if($application->cover_note)<p class="mt-3 whitespace-pre-line">{{ $application->cover_note }}</p>@endif
<form class="mt-4 grid gap-3" method="POST" action="{{ route('employer.applications.update',[$job,$application->id]) }}">@csrf @method('PATCH')
<label class="grid gap-1">Recruiter status<select class="min-h-12 rounded-lg border border-neutral-500 p-2" name="status">
@foreach(['submitted','reviewing','shortlisted','interview','offer','rejected'] as $status)
<option value="{{ $status }}" @selected($application->status===$status)>{{ ucfirst($status) }}</option>
@endforeach</select></label>
<label class="grid gap-1">Interview date and time (optional)<input class="min-h-12 rounded-lg border border-neutral-500 p-2" type="datetime-local" name="interview_at" value="{{ $application->interview_at ? \Illuminate\Support\Carbon::parse($application->interview_at)->format('Y-m-d\TH:i') : '' }}"></label>
<label class="grid gap-1">Interview details and accessible communication instructions<textarea class="rounded-lg border border-neutral-500 p-2" name="interview_instructions" maxlength="2000" rows="3">{{ $application->interview_instructions }}</textarea></label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Update application</button>
</form></article>
@empty<p>No applications for this job.</p>@endforelse
</div><div class="mt-6">{{ $applications->links() }}</div></section></x-layouts.site>
