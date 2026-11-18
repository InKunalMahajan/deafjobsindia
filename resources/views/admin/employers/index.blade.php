<x-layouts.admin title="Employers">
<div class="flex flex-wrap items-center justify-between gap-4">
  <p class="text-sm text-neutral-600">Review employer information before public verification.</p>
  <a class="inline-block rounded-lg bg-black px-4 py-3 font-semibold text-white" href="{{ route('admin.employers.create') }}">Add employer</a>
</div>
<div class="mt-6 overflow-x-auto rounded-xl border border-neutral-200">
<table class="w-full min-w-[520px] text-left text-sm">
<thead class="bg-neutral-50"><tr class="border-b"><th class="p-3">Employer</th><th class="p-3">Verification</th><th class="p-3">Jobs</th><th class="p-3">Actions</th></tr></thead>
<tbody>
@forelse($employers as $employer)
<tr class="border-b last:border-0">
<td class="p-3 font-medium">{{ $employer->name }}</td>
<td class="p-3">{{ $employer->verified ? 'Verified' : 'Pending review' }}</td>
<td class="p-3">{{ $employer->jobs_count }}</td>
<td class="p-3">
  <div class="flex flex-wrap gap-4">
    <a class="underline underline-offset-4" href="{{ route('admin.employers.edit',$employer) }}">Review / edit</a>
    @if($employer->jobs_count === 0)
    <form method="POST" action="{{ route('admin.employers.destroy',$employer) }}" onsubmit="return confirm('Delete this employer permanently?')">
      @csrf @method('DELETE')<button type="submit" class="underline underline-offset-4">Delete</button>
    </form>
    @endif
  </div>
</td></tr>
@empty<tr><td colspan="4" class="p-6 text-neutral-600">No employers yet.</td></tr>@endforelse
</tbody></table></div>
<div class="mt-6">{{ $employers->links() }}</div>
</x-layouts.admin>
