<x-layouts.site :title="$employer->name">
<section class="dji-container py-16"><a href="{{ route('employers.index') }}" class="underline">All employers</a>
<h1 class="mt-6 text-4xl font-bold">{{ $employer->name }}</h1>
<p class="mt-3 text-neutral-600">{{ $employer->location }}</p>
<p class="mt-5 max-w-3xl whitespace-pre-line">{{ $employer->description }}</p>
@if($employer->website && str_starts_with($employer->website,'https://'))<p class="mt-4"><a class="underline" href="{{ $employer->website }}" rel="noopener noreferrer" target="_blank">Employer website</a></p>@endif
<h2 class="mt-12 text-2xl font-bold">Open vacancies</h2>
<div class="mt-6 grid gap-5 md:grid-cols-2">
@forelse($jobs as $job)<x-job-card :job="$job" />@empty<p>No open vacancies published.</p>@endforelse
</div><div class="mt-7">{{ $jobs->links() }}</div>
</section></x-layouts.site>
