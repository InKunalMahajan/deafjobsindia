@php
$jobSchema = null;
// Only mark up vacancies with a usable application link and grounded location.
// Remote eligibility cannot be inferred from the website's country alone.
$validApplyUrl = is_string($job->apply_url)
    && filter_var($job->apply_url, FILTER_VALIDATE_URL)
    && str_starts_with($job->apply_url, 'https://');
if (!$job->is_closed && $job->published_at && $validApplyUrl && $job->work_mode !== 'remote' && filled($job->location)) {
    $jobSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'JobPosting',
        'title' => $job->title,
        'description' => \Illuminate\Support\Str::markdown($job->description, ['html_input' => 'strip', 'allow_unsafe_links' => false]),
        'datePosted' => $job->published_at->toDateString(),
        'hiringOrganization' => ['@type' => 'Organization', 'name' => $job->employer->name],
        'employmentType' => match ($job->employment_type) {
            'full-time' => 'FULL_TIME', 'part-time' => 'PART_TIME',
            'contract' => 'CONTRACTOR', 'internship' => 'INTERN',
            default => 'OTHER',
        },
        'url' => route('jobs.show', $job->slug),
    ];
    if ($job->deadline) $jobSchema['validThrough'] = $job->deadline->endOfDay()->toIso8601String();
    $jobSchema['jobLocation'] = [
        '@type' => 'Place',
        'address' => array_filter([
            '@type' => 'PostalAddress',
            'addressLocality' => $job->location,
            'addressRegion' => $job->state,
            'addressCountry' => 'IN',
        ], fn ($value) => $value !== null && $value !== ''),
    ];
}
@endphp
<x-layouts.site :title="$job->title" :description="$job->summary" :canonical="route('jobs.show', $job->slug)" :robots="$job->is_closed ? 'noindex,follow' : null" :structured-data="$jobSchema">
<article class="dji-container py-10 sm:py-14">
  <div class="mx-auto max-w-4xl">
    <a href="{{ route('jobs.index') }}" class="text-sm font-semibold underline underline-offset-4">Back to jobs</a>
    <header class="mt-7 border-b border-neutral-200 pb-7">
      <div class="flex flex-wrap gap-2">
        <x-ui.badge>{{ ucfirst($job->sector) }}</x-ui.badge>
        @if($job->is_closed)<x-ui.badge variant="strong">Application closed</x-ui.badge>@endif
        @if($job->is_deaf_friendly)<x-ui.badge variant="outline">Deaf-friendly (reported)</x-ui.badge>@endif
        @if($job->is_pwd_eligible)<x-ui.badge variant="outline">PwD eligible (reported)</x-ui.badge>@endif
      </div>
      <h1 class="mt-5 break-words text-3xl font-extrabold tracking-tight sm:text-5xl">{{ $job->title }}</h1>
      <p class="mt-4 break-words text-base text-neutral-600">{{ $job->employer->name }} · {{ $job->location }}</p>
      @if($job->employer->verified)
        <a class="mt-2 inline-block text-sm font-semibold underline underline-offset-4" href="{{ route('employers.show', $job->employer->slug) }}">View employer profile</a>
      @else
        <p class="mt-2 text-xs text-neutral-600">Employer profile not yet reviewed</p>
      @endif
    </header>
    <section aria-label="Job overview" class="mt-7 grid gap-4 rounded-xl border border-neutral-200 bg-neutral-50 p-5 text-sm sm:grid-cols-2 sm:p-6">
      <p><strong>Employment:</strong> {{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}</p>
      <p><strong>Work mode:</strong> {{ ucfirst($job->work_mode) }}</p>
      <p><strong>Sector:</strong> {{ ucfirst($job->sector) }}</p>
      <p><strong>Salary:</strong> {{ $job->salary_text ?: 'Not specified' }}</p>
      <p><strong>Deadline:</strong> {{ $job->deadline?->format('d M Y') ?? 'Not specified' }}</p>
      <p><strong>Published:</strong> {{ $job->published_at?->format('d M Y') ?? 'Not specified' }}</p>
      @if($job->category && $job->category->is_active)
        <p><strong>Category:</strong> {{ $job->category->name }}</p>
      @endif
      @if($job->experience_min_years !== null && $job->experience_max_years !== null)
        <p><strong>Experience:</strong> {{ $job->experience_min_years }}–{{ $job->experience_max_years }} years</p>
      @endif
    </section>
    <section class="mt-10">
      <h2 class="text-2xl font-bold">Job description</h2>
      <div class="dji-prose mt-4">{!! \Illuminate\Support\Str::markdown($job->description, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}</div>
    </section>
    @if($job->requirements)
      <section class="mt-10">
        <h2 class="text-2xl font-bold">Requirements</h2>
        <div class="dji-prose mt-4">{!! \Illuminate\Support\Str::markdown($job->requirements, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}</div>
      </section>
    @endif
    @if(auth()->check() && auth()->user()->role === 'job_seeker')
      <form class="mt-6" method="POST" action="{{ route('seeker.jobs.save',$job) }}">@csrf
        <button class="min-h-11 rounded-lg border border-neutral-900 px-5 font-semibold hover:bg-neutral-100" type="submit">Save this job</button>
      </form>
    @elseif(auth()->guest())
      <p class="mt-6 text-sm"><a class="font-semibold underline" href="{{ route('seeker.login') }}">Sign in to save this job</a></p>
    @endif
    <section class="mt-10 rounded-xl border border-neutral-200 p-5 sm:p-7">
      <h2 class="text-xl font-bold">How to apply</h2>
      @if($job->application_instructions)
        <p class="mt-3 whitespace-pre-line break-words text-sm leading-7">{{ $job->application_instructions }}</p>
      @endif
      <p class="mt-3 text-sm text-neutral-600">Applications are completed on external recruitment websites. DeafJobsIndia does not collect CVs.</p>
      <p class="mt-2 text-sm text-neutral-600">Confirm eligibility and deadlines with the employer.</p>
      @if($job->is_closed)
        <p class="mt-5 font-semibold">Application closed. Check the source for any extension.</p>
      @elseif(filter_var($job->apply_url, FILTER_VALIDATE_URL) && str_starts_with($job->apply_url, 'https://'))
        <div class="mt-5"><x-ui.button :href="$job->apply_url" target="_blank" rel="noopener noreferrer">Apply on official site</x-ui.button></div>
      @endif
      @if(filter_var($job->source_url, FILTER_VALIDATE_URL) && str_starts_with($job->source_url, 'https://'))
        <p class="mt-5"><a class="break-all text-sm font-semibold underline underline-offset-4" href="{{ $job->source_url }}" target="_blank" rel="noopener noreferrer">Read original recruitment source</a></p>
      @endif
    </section>
  </div>
</article>
</x-layouts.site>
