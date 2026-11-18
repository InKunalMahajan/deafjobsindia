<x-layouts.site title="Employer dashboard" robots="noindex,nofollow">
<section class="dji-container py-12">
<h1 class="text-3xl font-bold">Employer dashboard</h1>
<h2 class="mt-3 text-xl">{{ $company->name }}</h2>
<p class="mt-2">{{ $company->verified ? 'Company verified by DeafJobsIndia.' : 'Verification pending. Administrator review required.' }}</p>
<form method="POST" action="{{ route('employer.logout') }}" class="mt-3">@csrf<button class="underline" type="submit">Sign out</button></form>
@if(session('status'))<p role="status" class="mt-4">{{ session('status') }}</p>@endif
@if($errors->any())<p role="alert" class="mt-4">{{ $errors->first() }}</p>@endif
<section class="mt-8 max-w-2xl rounded-xl border border-neutral-300 p-5">
<h2 class="text-2xl font-bold">Submit a job for review</h2>
<p class="mt-2 text-sm">Your job stays a draft until an administrator publishes it. Applicants apply on your external HTTPS website.</p>
<form method="POST" action="{{ route('employer.jobs.store') }}" class="mt-5 grid gap-4">@csrf
@foreach(['title'=>'Job title','location'=>'Location','apply_url'=>'Official HTTPS application URL'] as $field=>$label)
<label class="grid gap-2">{{ $label }}<input class="min-h-12 rounded-lg border border-neutral-500 px-3" name="{{ $field }}" maxlength="{{ $field==='apply_url' ? 2048 : 255 }}" value="{{ old($field) }}" required></label>
@endforeach
<label class="grid gap-2">Employment type<select class="min-h-12 rounded-lg border border-neutral-500 p-3" name="employment_type">@foreach(['full-time','part-time','contract','internship'] as $type)<option value="{{ $type }}">{{ ucfirst($type) }}</option>@endforeach</select></label>
<label class="grid gap-2">Work mode<select class="min-h-12 rounded-lg border border-neutral-500 p-3" name="work_mode">@foreach(['onsite','hybrid','remote'] as $type)<option value="{{ $type }}">{{ ucfirst($type) }}</option>@endforeach</select></label>
<label class="grid gap-2">Summary<textarea class="rounded-lg border border-neutral-500 p-3" name="summary" rows="3" maxlength="2000" required>{{ old('summary') }}</textarea></label>
<label class="grid gap-2">Description<textarea class="rounded-lg border border-neutral-500 p-3" name="description" rows="6" maxlength="50000" required>{{ old('description') }}</textarea></label>
<label class="grid gap-2">Requirements<textarea class="rounded-lg border border-neutral-500 p-3" name="requirements" rows="3" maxlength="20000">{{ old('requirements') }}</textarea></label>
<label class="grid gap-2">Application deadline<input class="min-h-12 rounded-lg border border-neutral-500 p-3" type="date" name="deadline" value="{{ old('deadline') }}"></label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Submit draft job</button>
</form></section>
<h2 class="mt-10 text-2xl font-bold">Company job submissions</h2>
<div class="mt-4 grid gap-3">@forelse($jobs as $job)
<div class="rounded-lg border border-neutral-300 p-4"><p class="font-semibold">{{ $job->title }}</p><p class="mt-1 text-sm">Status: {{ ucfirst($job->status) }}</p>
@if($job->application_method==='internal')<a class="mt-2 inline-block underline" href="{{ route('employer.applications',$job) }}">View applications</a>@endif</div>
@empty<p>No job submissions yet.</p>@endforelse</div>
<div class="mt-6">{{ $jobs->links() }}</div>
</section></x-layouts.site>
