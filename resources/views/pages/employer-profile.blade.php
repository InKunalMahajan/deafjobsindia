<x-layouts.site :title="$employer->name">
<section class="dji-container py-10 sm:py-16">
  <div class="mx-auto max-w-5xl">
    <a href="{{ route('employers.index') }}" class="text-sm font-semibold underline underline-offset-4">All employers</a>
    <header class="mt-7 border-b border-neutral-200 pb-7">
      <p class="text-xs font-semibold uppercase tracking-widest text-neutral-600">Employer profile</p>
      <h1 class="mt-3 break-words text-3xl font-extrabold tracking-tight sm:text-5xl">{{ $employer->name }}</h1>
      @if($employer->location)<p class="mt-3 text-sm text-neutral-600">{{ $employer->location }}</p>@endif
      @if($employer->description)<p class="mt-5 max-w-3xl whitespace-pre-line break-words leading-7 text-neutral-700">{{ $employer->description }}</p>@endif
      @if($employer->website && filter_var($employer->website,FILTER_VALIDATE_URL) && str_starts_with($employer->website,'https://'))
        <a class="mt-5 inline-block break-all text-sm font-semibold underline" href="{{ $employer->website }}" rel="noopener noreferrer" target="_blank">Employer website</a>
      @endif
    </header>
    <section class="mt-10">
      <h2 class="text-2xl font-bold">Open vacancies</h2>
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
