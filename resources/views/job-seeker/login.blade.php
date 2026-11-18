<x-layouts.site title="Job-seeker sign in" robots="noindex,nofollow">
<section class="dji-container py-12"><div class="mx-auto max-w-md rounded-xl border border-neutral-300 p-6 sm:p-8">
<h1 class="text-3xl font-bold">Job-seeker sign in</h1>
<p class="mt-3 text-neutral-700">Sign in to save jobs and track applications.</p>
@if($errors->any())<p role="alert" class="mt-4 font-semibold">{{ $errors->first() }}</p>@endif
<form method="POST" action="{{ route('seeker.login.store') }}" class="mt-6 grid gap-4">@csrf
<label class="grid gap-2 font-semibold">Email<input class="min-h-12 rounded-lg border border-neutral-500 px-3" type="email" name="email" autocomplete="email" required value="{{ old('email') }}"></label>
<label class="grid gap-2 font-semibold">Password<input class="min-h-12 rounded-lg border border-neutral-500 px-3" type="password" name="password" autocomplete="current-password" required></label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Sign in</button></form>
<a class="mt-4 inline-block underline" href="{{ route('seeker.google.redirect') }}">Continue with Google</a>
<p class="mt-6">New here? <a class="font-semibold underline" href="{{ route('seeker.register') }}">Create an account</a></p>
</div></section></x-layouts.site>
