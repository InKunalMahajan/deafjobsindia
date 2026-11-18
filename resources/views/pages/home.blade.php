<x-layouts.site title="Find jobs and career opportunities" description="Search job opportunities, employers and career guidance across India.">
<section class="border-b border-neutral-200 bg-neutral-50">
  <div class="dji-container grid gap-10 py-12 lg:grid-cols-2 lg:items-center lg:py-20">
    <div>
      <p class="text-xs font-semibold uppercase tracking-widest text-neutral-600">Jobs across India</p>
      <h1 class="mt-4 max-w-xl text-4xl font-extrabold tracking-tight sm:text-6xl">Find a job.<br>Build your future.</h1>
      <p class="mt-5 max-w-lg text-base leading-8 text-neutral-600">Explore vacancies, compare employers and apply through official recruitment websites.</p>
      <div class="mt-6 flex flex-wrap gap-3"><x-ui.button :href="route('jobs.index')">Browse jobs</x-ui.button><x-ui.button variant="secondary" :href="route('employers.index')">Explore employers</x-ui.button></div>
    </div>
    <form action="{{ route('jobs.index') }}" method="GET" role="search" class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm sm:p-7">
      <h2 class="text-xl font-bold">Search jobs</h2>
      <label for="home-query" class="mt-5 block text-sm font-semibold">Job title or keyword</label>
      <input id="home-query" name="q" maxlength="120" placeholder="e.g. Office Assistant" class="mt-2 min-h-12 w-full rounded-lg border border-neutral-300 px-4 focus:border-black">
      <button type="submit" class="mt-5 min-h-12 w-full rounded-lg bg-black px-5 py-3 font-semibold text-white hover:bg-neutral-800">Find jobs</button>
      <p class="mt-4 text-xs leading-5 text-neutral-600">Always confirm eligibility and deadlines with the employer.</p>
    </form>
  </div>
</section>
@if($featuredJobs->isNotEmpty())
<section class="dji-container py-12 sm:py-16">
  <x-ui.section-heading eyebrow="Selected opportunities" title="Featured jobs"/>
  <div class="mt-7 grid gap-5 md:grid-cols-2 lg:grid-cols-3">@foreach($featuredJobs as $job)<x-job-card :job="$job"/>@endforeach</div>
</section>
@endif
<section class="dji-container py-12 sm:py-16">
  <div class="flex flex-wrap items-end justify-between gap-4"><x-ui.section-heading eyebrow="Opportunities" title="Latest open jobs" description="Recently published vacancies from across India."/><a href="{{ route('jobs.index') }}" class="text-sm font-semibold underline">View all jobs</a></div>
  <div class="mt-7 grid gap-5 md:grid-cols-2 lg:grid-cols-3">@forelse($jobs as $job)<x-job-card :job="$job"/>@empty<div class="col-span-full"><x-ui.empty-state title="No open jobs yet" description="Please check again soon."/></div>@endforelse</div>
</section>
<section class="border-y border-neutral-200 bg-neutral-50"><div class="dji-container py-12 sm:py-16">
  <x-ui.section-heading eyebrow="Explore" title="More career resources"/>
  <div class="mt-7 grid gap-4 md:grid-cols-3">
  @foreach([['Employers','Explore reviewed employer profiles.',route('employers.index')],['Training','Find skills and training information.',route('section.show','training')],['Career guidance','Read advice for your next step.',route('section.show','career')]] as [$title,$description,$href])
    <a href="{{ $href }}" class="rounded-xl border border-neutral-200 bg-white p-6 hover:border-black"><h3 class="font-bold">{{ $title }}</h3><p class="mt-2 text-sm text-neutral-600">{{ $description }}</p><span class="mt-4 inline-block text-sm font-semibold underline">Explore</span></a>
  @endforeach
  </div>
</div></section>
<section class="dji-container py-12 sm:py-16">
  <div class="flex flex-wrap items-end justify-between gap-4"><x-ui.section-heading eyebrow="News and guidance" title="Latest updates"/><a href="{{ route('latest') }}" class="text-sm font-semibold underline">All updates</a></div>
  <div class="mt-7 grid gap-5 md:grid-cols-2 lg:grid-cols-3">@forelse($articles as $article)<x-article-card :article="$article"/>@empty<div class="col-span-full"><x-ui.empty-state title="Updates coming soon" description="New articles appear after review."/></div>@endforelse</div>
</section>
</x-layouts.site>