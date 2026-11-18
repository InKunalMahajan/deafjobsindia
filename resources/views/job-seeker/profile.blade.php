<x-layouts.site title="My profile" robots="noindex,nofollow">
<section class="dji-container max-w-2xl py-12"><h1 class="text-3xl font-bold">My profile</h1>
<p class="mt-2">Email: {{ $user->email }}</p>
@if(session('status'))<p role="status" class="mt-4">{{ session('status') }}</p>@endif
@if($errors->any())<p role="alert" class="mt-4">{{ $errors->first() }}</p>@endif
<form method="POST" action="{{ route('seeker.profile.update') }}" class="mt-6 grid gap-4">@csrf @method('PATCH')
<label class="grid gap-2">Name<input class="min-h-12 rounded-lg border border-neutral-500 px-3" name="name" required maxlength="255" value="{{ old('name',$user->name) }}"></label>
<label class="grid gap-2">Location<input class="min-h-12 rounded-lg border border-neutral-500 px-3" name="location" maxlength="255" value="{{ old('location',$user->location) }}"></label>
<label class="grid gap-2">About me<textarea class="rounded-lg border border-neutral-500 p-3" name="bio" maxlength="2000" rows="5">{{ old('bio',$user->bio) }}</textarea></label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Save profile</button></form>
<a class="mt-6 inline-block underline" href="{{ route('seeker.dashboard') }}">Back to dashboard</a>
</section></x-layouts.site>
