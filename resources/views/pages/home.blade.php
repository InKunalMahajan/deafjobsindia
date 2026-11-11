<x-layouts.site title="Jobs and career opportunities" description="Find job opportunities, verified employer profiles and career guidance for Deaf and Hard of Hearing people in India.">
<section class="border-b border-neutral-200 bg-neutral-50/50">
 <div class="dji-container grid gap-10 py-16 lg:grid-cols-[1.2fr_.8fr] lg:items-center lg:py-24">
  <div>
   <p class="text-xs font-bold uppercase tracking-[.16em] text-neutral-600">Employment information for Deaf and Hard of Hearing people</p>
   <h1 class="mt-5 max-w-3xl text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-6xl">Find opportunities.<br>Build your career.</h1>
   <p class="mt-6 max-w-xl text-lg leading-8 text-neutral-600">Search recruitment notices, explore employers and discover career resources across India. Apply directly through official recruitment websites.</p>
   <div class="mt-7 flex flex-wrap gap-3"><x-ui.button :href="route('jobs.index')">Browse all jobs</x-ui.button><x-ui.button variant="secondary" :href="route('section.show','career')">Career resources</x-ui.button></div>
  </div>
  <form method="GET" action="{{ route('jobs.index') }}" role="search" aria-label="Find job opportunities" class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm">
   <h2 class="text-xl font-bold">Find your next opportunity</h2>
   <p class="mt-2 text-sm text-neutral-600">Search by job title, employer or keyword.</p>
   <label for="home-job-query" class="mt-6 block text-sm font-semibold">What job are you looking for?</label>
   <input id="home-job-query" name="q" type="search" maxlength="120" placeholder="e.g. Accountant, Office Assistant" class="mt-2 min-h-12 w-full rounded-lg border border-neutral-300 px-4 focus:border-black">
   <button type="submit" class="mt-4 min-h-12 w-full rounded-lg bg-black px-5 py-3 font-semibold text-white hover:bg-neutral-800">Search jobs</button>
   <p class="mt-4 text-xs leading-5 text-neutral-600">Listings are informational. Confirm eligibility and deadlines with the employer.</p>
  </form>
 </div>
</section>
<section class="dji-container py-16">
 <x-ui.section-heading eyebrow="Recruitment" title="Latest open jobs" description="Recently published opportunities. Always check the official recruitment source."/>
 <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">@forelse($jobs as $job)<x-job-card :job="$job"/>@empty<div class="col-span-full"><x-ui.empty-state title="No open jobs published yet" description="Check back for verified recruitment information."/></div>@endforelse</div>
 <div class="mt-8"><x-ui.button variant="secondary" :href="route('jobs.index')">View all jobs</x-ui.button></div>
</section>
<section class="border-y border-neutral-200 bg-neutral-50"><div class="dji-container py-16">
 <x-ui.section-heading eyebrow="Explore" title="More ways to grow your career"/>
 <div class="mt-8 grid gap-5 md:grid-cols-3">
 @foreach([['Employer directory','Explore reviewed employer profiles and their published vacancies.',route('employers.index')],['Education and training','Find learning and skill-development information.',route('section.show','training')],['Career guidance','Read practical advice for your next career step.',route('section.show','career')]] as [$heading,$summary,$link])
 <a href="{{ $link }}" class="group rounded-xl border border-neutral-200 bg-white p-7 transition-colors hover:border-black"><h3 class="text-xl font-bold group-hover:underline">{{ $heading }}</h3><p class="mt-3 text-sm leading-7 text-neutral-600">{{ $summary }}</p><span class="mt-6 inline-block text-sm font-semibold underline">Explore resources</span></a>
 @endforeach
 </div>
 </div></section>
<section class="dji-container py-16"><div class="flex flex-wrap items-end justify-between gap-4"><x-ui.section-heading eyebrow="News and guidance" title="Latest updates"/><a class="text-sm font-semibold underline" href="{{ route('latest') }}">All updates</a></div>
 <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">@forelse($articles as $article)<x-article-card :article="$article"/>@empty<div class="col-span-full"><x-ui.empty-state title="Updates coming soon" description="New content appears after editorial review."/></div>@endforelse</div>
</section>
<section class="border-t border-neutral-200"><div class="dji-container grid gap-8 py-14 md:grid-cols-3">
 <div><h2 class="text-lg font-bold">1. Search</h2><p class="mt-2 text-sm leading-7 text-neutral-600">Find jobs by title, employer and location.</p></div>
 <div><h2 class="text-lg font-bold">2. Check</h2><p class="mt-2 text-sm leading-7 text-neutral-600">Read requirements, deadlines and original sources.</p></div>
 <div><h2 class="text-lg font-bold">3. Apply</h2><p class="mt-2 text-sm leading-7 text-neutral-600">Complete your application on the official employer website.</p></div>
</div></section>
</x-layouts.site>