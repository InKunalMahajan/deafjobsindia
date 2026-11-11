<div class="mt-9">
    <div class="rounded-xl border border-neutral-200 bg-neutral-50 p-5">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <flux:input wire:model.live.debounce.400ms="query" label="Keyword" placeholder="Job title or employer" />
            <flux:input wire:model.live.debounce.400ms="location" label="Location" placeholder="Pune, Mumbai, remote..." />
            <flux:select wire:model.live="sector" label="Sector">
                <flux:select.option value="">All sectors</flux:select.option>
                <flux:select.option value="government">Government</flux:select.option>
                <flux:select.option value="private">Private</flux:select.option>
                <flux:select.option value="nonprofit">Nonprofit</flux:select.option>
            </flux:select>
            <flux:select wire:model.live="employmentType" label="Employment type">
                <flux:select.option value="">All types</flux:select.option>
                <flux:select.option value="full-time">Full-time</flux:select.option>
                <flux:select.option value="part-time">Part-time</flux:select.option>
                <flux:select.option value="contract">Contract</flux:select.option>
                <flux:select.option value="internship">Internship</flux:select.option>
            </flux:select>
            <flux:select wire:model.live="workMode" label="Work mode">
                <flux:select.option value="">All modes</flux:select.option>
                <flux:select.option value="onsite">On-site</flux:select.option>
                <flux:select.option value="hybrid">Hybrid</flux:select.option>
                <flux:select.option value="remote">Remote</flux:select.option>
            </flux:select>
            <flux:select wire:model.live="sort" label="Sort">
                <flux:select.option value="newest">Newest first</flux:select.option>
                <flux:select.option value="deadline">Closing soon</flux:select.option>
            </flux:select>
        </div>
        <div class="mt-5 flex flex-wrap items-center gap-5">
            <flux:checkbox wire:model.live="openOnly" label="Open jobs only" />
            <flux:checkbox wire:model.live="deafFriendly" label="Reported Deaf-friendly" />
            <flux:checkbox wire:model.live="pwdEligible" label="Reported PwD eligible" />
            <flux:button variant="ghost" wire:click="clearFilters">Clear filters</flux:button>
        </div>
    </div>
    <p class="mt-7 text-sm text-neutral-600" role="status" aria-live="polite">{{ $jobs->total() }} jobs found</p>
    <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($jobs as $job)
            <x-job-card :job="$job" wire:key="job-{{ $job->id }}" />
        @empty
            <div class="col-span-full"><x-ui.empty-state title="No matching jobs" description="Try different keywords or remove filters." /></div>
        @endforelse
    </div>
    <div class="mt-8">{{ $jobs->links() }}</div>
</div>
