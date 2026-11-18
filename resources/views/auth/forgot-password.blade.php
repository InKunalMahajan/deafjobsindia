<x-layouts.site title="Reset password" robots="noindex,nofollow">
<section class="dji-container max-w-md py-12">
<h1 class="text-3xl font-bold">Reset your password</h1>
<p class="mt-3">Enter your {{ $portal === 'employer' ? 'employer' : 'job-seeker' }} account email.</p>
@if(session('status'))<p role="status" class="mt-4">{{ session('status') }}</p>@endif
@if($errors->any())<p role="alert" class="mt-4">{{ $errors->first() }}</p>@endif
<form method="POST" action="{{ route($portal.'.password.email') }}" class="mt-6 grid gap-4">@csrf
<label class="grid gap-2">Email<input type="email" name="email" required autocomplete="email" class="min-h-12 rounded border border-neutral-500 p-3"></label>
<button type="submit" class="min-h-12 rounded bg-black px-4 text-white">Send reset link</button>
</form></section></x-layouts.site>
