<x-layouts.site title="Find jobs" description="Search open job opportunities by title, employer, sector, location and work mode.">
<section class="dji-container py-10 sm:py-16">
  <x-ui.section-heading :level="1" eyebrow="Employment" title="Find your next job" description="Search vacancies, compare requirements and check the original recruitment notice before applying."/>
  <livewire:job-search :initial-query="request()->query('q', '')" />
</section>
</x-layouts.site>
