@props(['job'])
<x-ui.card {{ $attributes->class(['flex h-full flex-col']) }}>
    <div class="flex flex-wrap gap-2">
        <x-ui.badge>{{ ucfirst($job->sector) }}</x-ui.badge>
        @if($job->is_deaf_friendly)<x-ui.badge variant="outline">Deaf-friendly (reported)</x-ui.badge>@endif
        @if($job->is_pwd_eligible)<x-ui.badge variant="outline">PwD eligible (reported)</x-ui.badge>@endif
        @if($job->is_closed)<x-ui.badge variant="strong">Closed</x-ui.badge>@endif
    </div>
    <h3 class="mt-4 text-xl font-bold"><a class="hover:underline" href="{{ route('jobs.show',$job->slug) }}">{{ $job->title }}</a></h3>
    <p class="mt-2 text-sm font-medium">{{ $job->employer->name }}</p>
    <p class="mt-2 text-sm text-neutral-600">{{ $job->location }} · {{ ucfirst(str_replace('-',' ',$job->work_mode)) }} · {{ ucfirst(str_replace('-',' ',$job->employment_type)) }}</p>
    <p class="mt-4 flex-1 text-sm leading-6 text-neutral-600">{{ $job->summary }}</p>
    <div class="mt-5 flex items-center justify-between gap-3 border-t border-neutral-200 pt-4 text-sm">
        <span class="text-neutral-600">{{ $job->deadline ? 'Deadline: '.$job->deadline->format('d M Y') : 'Deadline not specified' }}</span>
        <a class="font-semibold underline underline-offset-4" href="{{ route('jobs.show',$job->slug) }}">View job</a>
    </div>
</x-ui.card>
