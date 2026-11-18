<x-layouts.site title="Employer sign in" robots="noindex,nofollow">
<section class="dji-container max-w-md py-12"><h1 class="text-3xl font-bold">Employer sign in</h1>
@if($errors->any())<p role="alert" class="mt-4">{{ $errors->first() }}</p>@endif
<form method="POST" action="{{ route('employer.login.store') }}" class="mt-6 grid gap-4">@csrf
<label class="grid gap-2">Email<input class="min-h-12 rounded-lg border border-neutral-500 px-3" type="email" name="email" required autocomplete="email" value="{{ old('email') }}"></label>
<label class="grid gap-2">Password<input class="min-h-12 rounded-lg border border-neutral-500 px-3" type="password" name="password" required autocomplete="current-password"></label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Sign in</button></form>
<a class="mt-5 inline-block underline" href="{{ route('employer.password.request') }}">Forgot password?</a>
<a class="mt-5 inline-block underline" href="{{ route('employer.register') }}">Register your company</a>
</section></x-layouts.site>
