<x-layouts.site title="Submitted applications" robots="noindex,nofollow">
<section class="dji-container py-12"><h1 class="text-3xl font-bold">Applications submitted on DeafJobsIndia</h1>
<p class="mt-3">These applications were sent through this website. External applications remain in your separate manual tracker.</p>
@if(session('status'))<p class="mt-4" role="status">{{ session('status') }}</p>@endif
<div class="mt-6 grid gap-4">
@forelse($applications as $application)
@php($job=$jobs->get($application->job_listing_id))
@if($job)
<article class="rounded-xl border border-neutral-300 p-5">
<h2 class="text-xl font-bold">{{ $job->title }}</h2><p>{{ $job->employer?->name }}</p>
<p class="mt-2">Recruiter status: <strong>{{ ucfirst($application->status) }}</strong></p>
@if($application->interview_at)<p class="mt-2">Interview: {{ $application->interview_at }}</p>@endif
@if($application->interview_instructions)<p class="mt-2 whitespace-pre-line">{{ $application->interview_instructions }}</p>@endif
</article>
@endif
@empty<p>No applications submitted through DeafJobsIndia yet.</p>@endforelse
</div><div class="mt-6">{{ $applications->links() }}</div></section></x-layouts.site>
