<x-layouts.admin :title="$user->exists ? 'Edit user' : 'Add user'">
<form method="POST" action="{{ $user->exists ? route('admin.users.update',$user) : route('admin.users.store') }}" class="max-w-xl space-y-5">
@csrf @if($user->exists)@method('PUT')@endif
<flux:input name="name" label="Name" required :value="old('name',$user->name)" />
<flux:input name="email" label="Email" type="email" required :value="old('email',$user->email)" />
<flux:input name="password" label="Password (12+ characters)" type="password" :required="!$user->exists" />
<flux:input name="password_confirmation" label="Confirm password" type="password" :required="!$user->exists" />
<div><label class="mb-2 block text-sm font-medium" for="role">Role</label><select name="role" id="role" class="w-full rounded border border-neutral-300 p-3">
@foreach(['viewer','editor','admin','super_admin'] as $role)<option value="{{ $role }}" @selected(old('role',$user->role ?? 'viewer')===$role)>{{ ucwords(str_replace('_',' ',$role)) }}</option>@endforeach
</select></div>
<flux:button type="submit" variant="primary">Save user</flux:button>
</form></x-layouts.admin>
