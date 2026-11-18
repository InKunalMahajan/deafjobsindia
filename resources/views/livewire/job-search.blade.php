<div class="mt-8">
  <div class="rounded-xl border border-neutral-200 bg-neutral-50 p-4 sm:p-6">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <flux:input wire:model.live.debounce.400ms="query" label="Keyword" placeholder="Job title or employer" />
      <flux:input wire:model.live.debounce.400ms="location" label="Location" placeholder="City or location" />
      <flux:select wire:model.live="category" label="Job category">
        <flux:select.option value="">All categories</flux:select.option>
        @foreach($categories as $categoryOption)
          <flux:select.option value="{{ $categoryOption->slug }}">{{ $categoryOption->name }}</flux:select.option>
        @endforeach
      </flux:select>
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
      <flux:select wire:model.live="salary" label="Annual salary range reaches at least">
        <flux:select.option value="">Any salary</flux:select.option>
        <flux:select.option value="200000">₹2 lakh</flux:select.option>
        <flux:select.option value="400000">₹4 lakh</flux:select.option>
        <flux:select.option value="600000">₹6 lakh</flux:select.option>
        <flux:select.option value="1000000">₹10 lakh</flux:select.option>
      </flux:select>
      <flux:select wire:model.live="experience" label="Experience required (years)">
        <flux:select.option value="">Any experience</flux:select.option>
        <flux:select.option value="0">Fresher (0 years)</flux:select.option>
        <flux:select.option value="1">1 year</flux:select.option>
        <flux:select.option value="3">3 years</flux:select.option>
        <flux:select.option value="5">5 years</flux:select.option>
        <flux:select.option value="10">10 years</flux:select.option>
      </flux:select>
      <flux:select wire:model.live="sort" label="Sort">
        <flux:select.option value="newest">Newest first</flux:select.option>
        <flux:select.option value="deadline">Closing soon</flux:select.option>
        <flux:select.option value="salary_high">Highest salary</flux:select.option>
        <flux:select.option value="salary_low">Lowest salary</flux:select.option>
      </flux:select>
    </div>
    <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-3">
      <flux:checkbox wire:model.live="openOnly" label="Open jobs only" />
      <flux:checkbox wire:model.live="featuredOnly" label="Featured jobs only" />
      <flux:checkbox wire:model.live="deafFriendly" label="Reported Deaf-friendly" />
      <flux:checkbox wire:model.live="pwdEligible" label="Reported PwD eligible" />
      <flux:button variant="ghost" wire:click="clearFilters">Clear filters</flux:button>
    </div>
    <p class="mt-4 text-xs text-neutral-600">Salary shows jobs whose listed annual maximum meets your selected amount. Experience shows jobs whose stated range includes your selected years. Jobs without these details are excluded when those filters are active.</p>
  </div>
  <div class="mt-7 flex flex-wrap items-center justify-between gap-3">
    <p class="text-sm font-medium text-neutral-700" role="status" aria-live="polite">{{ $jobs->total() }} jobs found</p>
    <span wire:loading class="text-xs text-neutral-600">Updating results...</span>
  </div>
  <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
    @forelse($jobs as $job)
      <x-job-card :job="$job" wire:key="job-{{ $job->id }}" />
    @empty
      <div class="col-span-full"><x-ui.empty-state title="No matching jobs" description="Try another keyword, location or filter." /></div>
    @endforelse
  </div>
  <div class="mt-8">{{ $jobs->links() }}</div>
</div>
