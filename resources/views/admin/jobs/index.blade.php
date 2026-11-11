<x-layouts.admin title="Job management">
<a class="inline-block rounded bg-black px-4 py-3 font-semibold text-white" href="{{ route('admin.jobs.create') }}">Add job</a>
<div class="mt-6 overflow-x-auto"><table class="w-full border-collapse text-left text-sm"><thead><tr class="border-b"><th class="p-3">Job</th><th class="p-3">Employer</th><th class="p-3">Status</th><th class="p-3">Actions</th></tr></thead><tbody>
@foreach($jobs as $job)<tr class="border-b"><td class="p-3">{{ $job->title }}</td><td class="p-3">{{ $job->employer->name }}</td><td class="p-3">{{ ucfirst($job->status) }}</td><td class="p-3"><div class="flex gap-4">
<a class="underline" href="{{ route('admin.jobs.edit',$job) }}">Edit</a>
@can('jobs.publish')<form method="POST" action="{{ route('admin.jobs.destroy',$job) }}" onsubmit="return confirm('Delete this job?')">@csrf @method('DELETE')<button class="underline" type="submit">Delete</button></form>@endcan
</div></td></tr>@endforeach
</tbody></table></div><div class="mt-6">{{ $jobs->links() }}</div>
</x-layouts.admin>
