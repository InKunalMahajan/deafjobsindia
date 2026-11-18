<x-layouts.site title="Search news and career resources">
<section class="dji-container py-10 sm:py-16">
  <x-ui.section-heading :level="1" eyebrow="Discover" title="Search news and resources" description="Search articles and career information. For vacancies, use job search."/>
  <p class="mt-5 text-sm"><a class="font-semibold underline underline-offset-4" href="{{ route('jobs.index',['q'=>request()->query('q','')]) }}">Search jobs instead</a></p>
  <livewire:article-search :initial-query="request()->query('q', '')" />
</section>
</x-layouts.site>
