<x-layouts.site title="Create job-seeker account" robots="noindex,nofollow">
<section class="dji-container py-12"><div class="mx-auto max-w-md rounded-xl border border-neutral-300 p-6 sm:p-8">
<h1 class="text-3xl font-bold">Create a job-seeker account</h1>
@if($errors->any())<div role="alert" class="mt-4">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('seeker.register.store') }}" class="mt-6 grid gap-4">@csrf
<label class="grid gap-2 font-semibold">Full name<input class="min-h-12 rounded-lg border border-neutral-500 px-3" name="name" required maxlength="255" autocomplete="name" value="{{ old('name') }}"></label>
<label class="grid gap-2 font-semibold">Email<input class="min-h-12 rounded-lg border border-neutral-500 px-3" type="email" name="email" required autocomplete="email" value="{{ old('email') }}"></label>
<label class="grid gap-2 font-semibold">Password (12 characters minimum)<input class="min-h-12 rounded-lg border border-neutral-500 px-3" type="password" name="password" required minlength="12" autocomplete="new-password"></label>
<label class="grid gap-2 font-semibold">Confirm password<input class="min-h-12 rounded-lg border border-neutral-500 px-3" type="password" name="password_confirmation" required autocomplete="new-password"></label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Create account</button></form>
<a class="mt-4 inline-block underline" href="{{ route('seeker.google.redirect') }}">Continue with Google</a>
<p class="mt-6">Already registered? <a class="underline" href="{{ route('seeker.login') }}">Sign in</a></p>
</div></section></x-layouts.site>
