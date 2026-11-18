<x-layouts.site :title="$employer->name" :description="\Illuminate\Support\Str::limit($employer->description ?: 'Reviewed employer profile and job opportunities on DeafJobsIndia.', 155)" :canonical="route('employers.show', $employer->slug)">
<section class="dji-container py-10 sm:py-16">
  <div class="mx-auto max-w-5xl">
    <a href="{{ route('employers.index') }}" class="text-sm font-semibold underline underline-offset-4">All employers</a>
    <header class="mt-7 border-b border-neutral-200 pb-7">
      <p class="text-xs font-semibold uppercase tracking-widest text-neutral-600">Reviewed employer profile</p>
      <h1 class="mt-3 break-words text-3xl font-extrabold tracking-tight sm:text-5xl">{{ $employer->name }}</h1>
      @if($employer->location)
        <p class="mt-3 break-words text-sm text-neutral-600">{{ $employer->location }}</p>
      @endif
      @if($employer->description)
        <div class="mt-5 max-w-3xl whitespace-pre-line break-words leading-7 text-neutral-700">{{ $employer->description }}</div>
      @endif
      @if($employer->website && filter_var($employer->website, FILTER_VALIDATE_URL) && str_starts_with($employer->website, 'https://'))
        <a class="mt-5 inline-block break-all text-sm font-semibold underline underline-offset-4" href="{{ $employer->website }}" rel="noopener noreferrer" target="_blank">Visit employer website</a>
      @endif
      <p class="mt-4 text-xs leading-6 text-neutral-600">Employer information is reviewed by DeafJobsIndia. Always confirm job details with the official recruitment source.</p>
    </header>
    <section class="mt-10">
      <div class="flex flex-wrap items-end justify-between gap-3">
        <h2 class="text-2xl font-bold">Open vacancies</h2>
        <p class="text-sm text-neutral-600">{{ $jobs->total() }} {{ $jobs->total() == 1 ? 'job' : 'jobs' }}</p>
      </div>
      <div class="mt-6 grid gap-5 md:grid-cols-2">
        @forelse($jobs as $job)
          <x-job-card :job="$job" />
        @empty
          <div class="col-span-full"><x-ui.empty-state title="No open vacancies" description="Check again later for new opportunities from this employer."/></div>
        @endforelse
      </div>
      <div class="mt-8">{{ $jobs->links() }}</div>
    </section>
  </div>
</section>
</x-layouts.site>
