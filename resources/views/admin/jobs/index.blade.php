<x-layouts.admin title="Job management">
<div class="flex flex-wrap items-center justify-between gap-4">
  <p class="text-sm text-neutral-600">Review publication and external application information.</p>
  <a class="inline-block rounded-lg bg-black px-4 py-3 font-semibold text-white" href="{{ route('admin.jobs.create') }}">Add job</a>
</div>
<div class="mt-6 overflow-x-auto rounded-xl border border-neutral-200">
<table class="w-full min-w-[760px] text-left text-sm">
<thead class="bg-neutral-50"><tr class="border-b"><th class="p-3">Job</th><th class="p-3">Employer</th><th class="p-3">Publication</th><th class="p-3">Application review</th><th class="p-3">Actions</th></tr></thead>
<tbody>
@forelse($jobs as $job)
<tr class="border-b last:border-0">
<td class="p-3 font-medium">{{ $job->title }}</td>
<td class="p-3">{{ $job->employer->name }}</td>
<td class="p-3">
  <span class="font-medium">{{ ucfirst($job->status) }}</span>
  @if($job->status === 'published' && $job->published_at && $job->published_at->isFuture())
    <span class="block text-xs text-neutral-600">Scheduled {{ $job->published_at->format('d M Y') }}</span>
  @elseif($job->status === 'published' && $job->is_closed)
    <span class="block text-xs text-neutral-600">Deadline passed</span>
  @endif
</td>
<td class="p-3">
  {{ ucfirst(str_replace('_',' ', $job->application_review_status ?? 'unreviewed')) }}
  @can('applications.review')
  <form method="POST" action="{{ route('admin.jobs.application-review',$job) }}" class="mt-2 flex flex-wrap gap-2">
    @csrf @method('PATCH')
    <input type="hidden" name="application_method" value="external">
    <select name="application_review_status" aria-label="Review status for {{ $job->title }}" class="rounded border border-neutral-300 bg-white p-2">
    @foreach(['unreviewed','verified','needs_update'] as $status)
      <option value="{{ $status }}" @selected($job->application_review_status === $status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>
    @endforeach
    </select>
    <button type="submit" class="rounded border border-neutral-900 px-3 py-2 font-medium">Save review</button>
  </form>
  @endcan
</td>
<td class="p-3">
  <div class="flex flex-wrap gap-4">
    <a class="underline underline-offset-4" href="{{ route('admin.jobs.edit',$job) }}">Edit</a>
    @can('jobs.publish')
    <form method="POST" action="{{ route('admin.jobs.destroy',$job) }}" onsubmit="return confirm('Delete this job permanently?')">
      @csrf @method('DELETE')
      <button type="submit" class="underline underline-offset-4">Delete</button>
    </form>
    @endcan
  </div>
</td>
</tr>
@empty
<tr><td colspan="5" class="p-6 text-neutral-600">No jobs have been added.</td></tr>
@endforelse
</tbody></table></div>
<div class="mt-6">{{ $jobs->links() }}</div>
</x-layouts.admin>
