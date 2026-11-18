<x-layouts.site title="Employer directory">
<section class="dji-container py-10 sm:py-16">
  <x-ui.section-heading :level="1" eyebrow="Companies" title="Explore employers" description="Browse reviewed employer profiles and published job opportunities across India."/>
  <p class="mt-5 text-sm text-neutral-600">{{ $employers->total() }} reviewed employers</p>
  <div class="mt-7 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
    @forelse($employers as $employer)
      <x-ui.card>
        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-600">Reviewed employer</p>
        <h2 class="mt-3 break-words text-xl font-bold">
          <a class="underline underline-offset-4" href="{{ route('employers.show', $employer->slug) }}">{{ $employer->name }}</a>
        </h2>
        @if($employer->location)
          <p class="mt-3 break-words text-sm text-neutral-600">{{ $employer->location }}</p>
        @endif
        @if($employer->description)
          <p class="mt-3 break-words text-sm leading-6">{{ \Illuminate\Support\Str::limit($employer->description, 160) }}</p>
        @endif
        <p class="mt-4 text-sm font-medium">{{ $employer->open_jobs_count }} open {{ $employer->open_jobs_count == 1 ? 'vacancy' : 'vacancies' }}</p>
        <a class="mt-5 inline-block text-sm font-semibold underline underline-offset-4" href="{{ route('employers.show', $employer->slug) }}">View employer profile</a>
      </x-ui.card>
    @empty
      <div class="col-span-full"><x-ui.empty-state title="No reviewed employers yet" description="Employer profiles will appear here after review."/></div>
    @endforelse
  </div>
  <div class="mt-8">{{ $employers->links() }}</div>
</section>
</x-layouts.site>
