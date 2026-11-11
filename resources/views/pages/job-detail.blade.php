<x-layouts.site :title="$job->title" :description="$job->summary">
    <article class="dji-container py-14">
        <div class="mx-auto max-w-3xl">
            <a href="{{ route('jobs.index') }}" class="text-sm font-semibold underline underline-offset-4">Back to jobs</a>
            <div class="mt-8 flex flex-wrap gap-2">
                <x-ui.badge>{{ ucfirst($job->sector) }}</x-ui.badge>
                @if($job->is_closed)<x-ui.badge variant="strong">Application closed</x-ui.badge>@endif
                @if($job->is_deaf_friendly)<x-ui.badge variant="outline">Deaf-friendly (reported)</x-ui.badge>@endif
                @if($job->is_pwd_eligible)<x-ui.badge variant="outline">PwD eligible (reported)</x-ui.badge>@endif
            </div>
            <h1 class="mt-5 text-4xl font-extrabold tracking-tight sm:text-5xl">{{ $job->title }}</h1>
            <p class="mt-4 text-lg text-neutral-600">{{ $job->employer->name }} · {{ $job->location }}</p>
            <div class="mt-7 grid gap-3 rounded-xl border border-neutral-200 bg-neutral-50 p-6 text-sm sm:grid-cols-2">
                <p><strong>Employment:</strong> {{ ucfirst(str_replace('-',' ',$job->employment_type)) }}</p>
                <p><strong>Work mode:</strong> {{ ucfirst($job->work_mode) }}</p>
                <p><strong>Sector:</strong> {{ ucfirst($job->sector) }}</p>
                <p><strong>Salary:</strong> {{ $job->salary_text ?: 'Not specified' }}</p>
                <p><strong>Deadline:</strong> {{ $job->deadline?->format('d M Y') ?? 'Not specified' }}</p>
                <p><strong>Published:</strong> {{ $job->published_at?->format('d M Y') }}</p>
            </div>
            <h2 class="mt-10 text-2xl font-bold">Job description</h2>
            <div class="dji-prose mt-4">{!! \Illuminate\Support\Str::markdown($job->description, ['html_input'=>'strip','allow_unsafe_links'=>false]) !!}</div>
            @if($job->requirements)
                <h2 class="mt-10 text-2xl font-bold">Requirements</h2>
                <div class="dji-prose mt-4">{!! \Illuminate\Support\Str::markdown($job->requirements, ['html_input'=>'strip','allow_unsafe_links'=>false]) !!}</div>
            @endif
            <div class="mt-10 rounded-xl border border-neutral-200 p-6">
                <h2 class="text-lg font-bold">Application information</h2>
                <p class="mt-3 text-sm leading-7 text-neutral-600">Eligibility, disability accommodations and deadlines must be confirmed with the recruiting organisation. Reported accessibility labels are not independently guaranteed.</p>
                @if($job->is_closed)
                    <p class="mt-4 font-semibold">Application closed. Check the source for any extension.</p>
                @elseif(filter_var($job->apply_url,FILTER_VALIDATE_URL) && str_starts_with($job->apply_url,'https://'))
                    <div class="mt-5"><x-ui.button :href="$job->apply_url" target="_blank" rel="noopener noreferrer">Apply on official site</x-ui.button></div>
                @endif
                @if(filter_var($job->source_url,FILTER_VALIDATE_URL) && str_starts_with($job->source_url,'https://'))
                    <p class="mt-5"><a class="break-all text-sm font-semibold underline underline-offset-4" href="{{ $job->source_url }}" target="_blank" rel="noopener noreferrer">Read original recruitment source</a></p>
                @endif
            </div>
        </div>
    </article>
</x-layouts.site>
