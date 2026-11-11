<x-layouts.site title="Find Jobs">
    <section class="dji-container py-16">
        <x-ui.section-heading eyebrow="Employment" title="Find Jobs" description="Browse published job listings, filter by location and work type, and check the original recruitment notice." />
        <livewire:job-search :initial-query="request()->query('q', '')" />
    </section>
</x-layouts.site>
