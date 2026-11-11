<x-layouts.admin title="Users">
<a class="inline-block rounded bg-black px-4 py-3 font-semibold text-white" href="{{ route('admin.users.create') }}">Add user</a>
<div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b"><th class="p-3">Name</th><th class="p-3">Email</th><th class="p-3">Role</th><th class="p-3">Actions</th></tr></thead><tbody>
@foreach($users as $user)<tr class="border-b"><td class="p-3">{{ $user->name }}</td><td class="p-3">{{ $user->email }}</td><td class="p-3">{{ str_replace('_',' ',$user->role) }}</td><td class="p-3"><div class="flex gap-4">
<a class="underline" href="{{ route('admin.users.edit',$user) }}">Edit</a>
@if($user->id!==auth()->id())<form method="POST" action="{{ route('admin.users.destroy',$user) }}" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')<button class="underline" type="submit">Delete</button></form>@endif
</div></td></tr>@endforeach</tbody></table></div><div class="mt-6">{{ $users->links() }}</div>
</x-layouts.admin>
