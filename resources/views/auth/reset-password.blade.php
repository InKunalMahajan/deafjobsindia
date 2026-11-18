<x-layouts.site title="Choose a new password" robots="noindex,nofollow">
<section class="dji-container max-w-md py-12"><h1 class="text-3xl font-bold">Choose a new password</h1>
@if($errors->any())<p role="alert" class="mt-4">{{ $errors->first() }}</p>@endif
<form method="POST" action="{{ route($portal.'.password.update') }}" class="mt-6 grid gap-4">@csrf
<input type="hidden" name="token" value="{{ $token }}">
<label class="grid gap-2">Email<input type="email" name="email" value="{{ old('email',$email) }}" required autocomplete="email" class="min-h-12 rounded border border-neutral-500 p-3"></label>
<label class="grid gap-2">New password (12 characters minimum)<input type="password" name="password" required minlength="12" autocomplete="new-password" class="min-h-12 rounded border border-neutral-500 p-3"></label>
<label class="grid gap-2">Confirm password<input type="password" name="password_confirmation" required minlength="12" autocomplete="new-password" class="min-h-12 rounded border border-neutral-500 p-3"></label>
<button type="submit" class="min-h-12 rounded bg-black px-4 text-white">Save new password</button>
</form></section></x-layouts.site>
