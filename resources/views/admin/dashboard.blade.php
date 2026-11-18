<x-layouts.admin title="Dashboard">
<p class="mb-7 text-sm text-neutral-600">Signed in as {{ auth()->user()->name }} · {{ str_replace('_',' ',auth()->user()->role) }}</p>
<h2 class="text-xl font-bold">Content overview</h2>
<div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
@foreach([
    'Employers' => $employers,
    'Verified employers' => $verifiedEmployers,
    'Total jobs' => $jobs,
    'Published jobs' => $published,
    'Open jobs' => $openJobs,
    'Draft jobs' => $drafts,
    'Total articles' => $articles,
    'Published articles' => $publishedArticles,
] as $label => $value)
<div class="rounded-xl border border-neutral-200 bg-white p-5">
  <p class="text-sm text-neutral-600">{{ $label }}</p>
  <p class="mt-2 text-3xl font-bold">{{ $value }}</p>
</div>
@endforeach
</div>
<h2 class="mt-10 text-xl font-bold">Items requiring attention</h2>
<div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
@foreach([
    'Unverified employers' => $pendingEmployers,
    'Articles in draft' => $draftArticles,
    'Jobs needing application updates' => $jobsNeedingReview,
    'Unreviewed application guidance' => $jobsUnreviewed,
] as $label => $value)
<div class="rounded-xl border border-neutral-200 bg-neutral-50 p-5">
  <p class="text-sm text-neutral-600">{{ $label }}</p>
  <p class="mt-2 text-3xl font-bold">{{ $value }}</p>
</div>
@endforeach
</div>
<div class="mt-8 flex flex-wrap gap-4 text-sm font-semibold">
  @can('jobs.edit')<a class="underline underline-offset-4" href="{{ route('admin.jobs.index') }}">Manage jobs</a>@endcan
  @can('articles.edit')<a class="underline underline-offset-4" href="{{ route('admin.articles.index') }}">Manage articles</a>@endcan
  @can('employers.manage')<a class="underline underline-offset-4" href="{{ route('admin.employers.index') }}">Manage employers</a>@endcan
</div>
</x-layouts.admin>
