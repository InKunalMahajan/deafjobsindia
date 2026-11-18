<x-layouts.site title="Verify email" robots="noindex,nofollow">
<section class="dji-container max-w-lg py-12"><h1 class="text-3xl font-bold">Verify your email address</h1>
<p class="mt-4">Open the verification link sent to your email. If it has not arrived, request another link below.</p>
@if(session('status'))<p role="status" class="mt-4">{{ session('status') }}</p>@endif
<form method="POST" action="{{ route('verification.send') }}" class="mt-6">@csrf
<button class="min-h-12 rounded bg-black px-4 text-white" type="submit">Resend verification email</button>
</form></section></x-layouts.site>
