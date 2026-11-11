<x-layouts.site title="Administrator sign in">
<section class="dji-container py-16"><div class="mx-auto max-w-md">
<h1 class="text-3xl font-bold">Administrator sign in</h1>
<p class="mt-3 text-neutral-600">Authorised DeafJobsIndia team members only.</p>
@if($errors->any())<p role="alert" class="mt-5 text-red-700">{{ $errors->first() }}</p>@endif
<form action="{{ route('admin.login.store') }}" method="POST" class="mt-8 space-y-5">
@csrf
<flux:input label="Email address" name="email" type="email" required autocomplete="username" :value="old('email')" />
<flux:input label="Password" name="password" type="password" required autocomplete="current-password" />
<flux:button type="submit" variant="primary">Sign in</flux:button>
</form></div></section>
</x-layouts.site>
