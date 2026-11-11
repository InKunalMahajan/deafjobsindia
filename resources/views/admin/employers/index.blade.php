<x-layouts.admin title="Employers">
<a class="inline-block rounded bg-black px-4 py-3 font-semibold text-white" href="{{ route('admin.employers.create') }}">Add employer</a>
<div class="mt-6 overflow-x-auto"><table class="w-full border-collapse text-left text-sm"><thead><tr class="border-b"><th class="p-3">Name</th><th class="p-3">Verified</th><th class="p-3">Actions</th></tr></thead><tbody>
@foreach($employers as $employer)<tr class="border-b"><td class="p-3">{{ $employer->name }}</td><td class="p-3">{{ $employer->verified ? 'Yes' : 'No' }}</td><td class="p-3"><div class="flex gap-4">
<a class="underline" href="{{ route('admin.employers.edit',$employer) }}">Edit</a>
<form method="POST" action="{{ route('admin.employers.destroy',$employer) }}" onsubmit="return confirm('Delete this employer?')">@csrf @method('DELETE')<button class="underline" type="submit">Delete</button></form>
</div></td></tr>@endforeach
</tbody></table></div><div class="mt-6">{{ $employers->links() }}</div>
</x-layouts.admin>
