<x-layouts.site title="My job-seeker dashboard" robots="noindex,nofollow">
<section class="dji-container py-10 sm:py-12">
    <div class="flex flex-wrap items-start justify-between gap-5">
        <div class="min-w-0">
            <p class="text-sm font-semibold text-neutral-600">My account</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Job-seeker dashboard</h1>
            <p class="mt-3 max-w-2xl text-neutral-700">Welcome, {{ auth()->user()->name }}. Manage your job search, applications and profile in one place.</p>
        </div>
        <form method="POST" action="{{ route('seeker.logout') }}">
            @csrf
            <button type="submit" class="min-h-11 rounded-lg border border-neutral-500 px-4 font-semibold hover:bg-neutral-100">Sign out</button>
        </form>
    </div>

    @if(session('status'))<p role="status" class="mt-6 rounded-lg border border-neutral-300 bg-neutral-50 p-4">{{ session('status') }}</p>@endif
    @if($errors->any())<div role="alert" class="mt-6 rounded-lg border border-neutral-900 p-4"><p class="font-semibold">Please check your information.</p><ul class="mt-2 list-disc pl-6">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <nav aria-label="Job-seeker account sections" class="mt-8">
        <h2 class="text-xl font-bold">Quick actions</h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                ['route'=>'seeker.profile','title'=>'Profile and resume','description'=>'Update your skills and upload your resume.'],
                ['route'=>'seeker.recommendations','title'=>'Recommended jobs','description'=>'Explore jobs that may match your profile.'],
                ['route'=>'seeker.alerts','title'=>'Job alerts','description'=>'Manage saved searches and email preferences.'],
                ['route'=>'seeker.applications','title'=>'Application tracker','description'=>'Track applications you manage yourself.'],
                ['route'=>'seeker.submitted','title'=>'Submitted applications','description'=>'Review applications submitted on DeafJobsIndia.'],
                ['route'=>'seeker.privacy','title'=>'Privacy and my data','description'=>'Review your personal information and privacy options.'],
            ] as $action)
                <a href="{{ route($action['route']) }}" class="group flex min-h-28 flex-col justify-between gap-3 rounded-xl border border-neutral-300 bg-white p-5 transition-colors hover:border-black hover:bg-neutral-50 focus-visible:outline-2 focus-visible:outline-offset-2">
                    <span class="text-lg font-bold underline-offset-4 group-hover:underline">{{ $action['title'] }}</span>
                    <span class="text-sm leading-6 text-neutral-700">{{ $action['description'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    <section aria-labelledby="saved-jobs-heading" class="mt-12">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 id="saved-jobs-heading" class="text-2xl font-bold">Saved jobs and application tracker</h2>
                <p class="mt-2 text-sm leading-6 text-neutral-700">The manual tracker is private. For external jobs, complete your application on the employer's official website.</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-black px-5 py-2 font-semibold text-white hover:bg-neutral-800">Find jobs</a>
        </div>

        <div class="mt-6 grid gap-5 lg:grid-cols-2">
            @forelse($jobs as $job)
                <article class="min-w-0 rounded-xl border border-neutral-300 bg-white p-5 sm:p-6">
                    <h3 class="break-words text-xl font-bold"><a class="underline underline-offset-4" href="{{ route('jobs.show',$job->slug) }}">{{ $job->title }}</a></h3>
                    @if($job->employer?->name)<p class="mt-2 text-neutral-700">{{ $job->employer->name }}</p>@endif
                    @if(in_array($job->id,$saved))
                        <form class="mt-4" method="POST" action="{{ route('seeker.jobs.unsave',$job) }}">
                            @csrf @method('DELETE')
                            <button class="min-h-11 rounded-lg border border-neutral-500 px-3 hover:bg-neutral-100" type="submit">Remove saved job</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('seeker.jobs.track',$job) }}" class="mt-5 grid gap-4">
                        @csrf
                        <label class="grid gap-2 font-semibold">Application status
                            <select name="status" class="min-h-12 w-full rounded-lg border border-neutral-500 bg-white p-3 font-normal">
                                @foreach(['interested','applied','interview','offer','rejected','withdrawn'] as $status)
                                    <option value="{{ $status }}" @selected(($tracked[$job->id]->status ?? 'interested')===$status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="grid gap-2 font-semibold">Date applied
                            <input type="date" name="applied_at" class="min-h-12 w-full rounded-lg border border-neutral-500 p-3 font-normal" value="{{ $tracked[$job->id]->applied_at ?? '' }}">
                        </label>
                        <label class="grid gap-2 font-semibold">Private notes
                            <textarea name="notes" maxlength="2000" class="w-full rounded-lg border border-neutral-500 p-3 font-normal" rows="3">{{ $tracked[$job->id]->notes ?? '' }}</textarea>
                        </label>
                        <div class="flex flex-wrap items-center gap-4">
                            <button class="min-h-12 rounded-lg bg-black px-5 font-semibold text-white hover:bg-neutral-800" type="submit">Save tracker</button>
                            @if(isset($tracked[$job->id]))<a class="underline underline-offset-4" href="{{ route('seeker.applications.history',$job) }}">View status history</a>@endif
                        </div>
                    </form>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-neutral-400 bg-neutral-50 p-7 sm:p-10 lg:col-span-2">
                    <h3 class="text-xl font-bold">Start your job search</h3>
                    <p class="mt-3 max-w-xl leading-7 text-neutral-700">You have no saved or tracked jobs yet. Find a job you like, save it, and track your progress here.</p>
                    <a href="{{ route('jobs.index') }}" class="mt-5 inline-flex min-h-12 items-center rounded-lg bg-black px-5 font-semibold text-white hover:bg-neutral-800">Browse available jobs</a>
                </div>
            @endforelse
        </div>
    </section>
</section>
</x-layouts.site>
