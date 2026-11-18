<x-layouts.site title="Job alerts" robots="noindex,nofollow">
<section class="dji-container max-w-2xl py-12"><h1 class="text-3xl font-bold">Job alerts</h1>
<p class="mt-3">Save up to 10 searches. Email notifications are not yet active.</p>
@if(session('status'))<p role="status" class="mt-3">{{ session('status') }}</p>@endif
@if($errors->any())<p role="alert" class="mt-3">{{ $errors->first() }}</p>@endif
<form method="POST" action="{{ route('seeker.alerts.store') }}" class="mt-6 grid gap-3">@csrf
<label class="grid gap-1">Keywords<input class="min-h-12 rounded-lg border border-neutral-500 p-3" name="keywords" maxlength="150" value="{{ old('keywords') }}"></label>
<label class="grid gap-1">Location<input class="min-h-12 rounded-lg border border-neutral-500 p-3" name="location" maxlength="150" value="{{ old('location') }}"></label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Save alert</button></form>
<h2 class="mt-8 text-xl font-bold">My saved alerts</h2>
@forelse($alerts as $alert)
<div class="mt-3 flex items-center justify-between gap-3 rounded-lg border border-neutral-300 p-4">
<p>{{ $alert->keywords ?: 'All keywords' }} · {{ $alert->location ?: 'Any location' }}</p>
<form method="POST" action="{{ route('seeker.alerts.delete',$alert->id) }}">@csrf @method('DELETE')<button class="underline" type="submit">Delete</button></form>
</div>
@empty<p class="mt-3">No alerts saved yet.</p>@endforelse
</section></x-layouts.site>
