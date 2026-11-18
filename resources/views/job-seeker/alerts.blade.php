<x-layouts.site title="Job alerts" robots="noindex,nofollow">
<section class="dji-container max-w-2xl py-12"><h1 class="text-3xl font-bold">Job alerts</h1>
<p class="mt-3">Save up to 10 searches. Email delivery requires your explicit opt-in and a verified email address.</p>
@if(session('status'))<p role="status" class="mt-3">{{ session('status') }}</p>@endif
@if($errors->any())<p role="alert" class="mt-3">{{ $errors->first() }}</p>@endif
<form method="POST" action="{{ route('seeker.alerts.store') }}" class="mt-6 grid gap-3">@csrf
<label class="grid gap-1">Keywords<input class="min-h-12 rounded-lg border border-neutral-500 p-3" name="keywords" maxlength="150" value="{{ old('keywords') }}"></label>
<label class="grid gap-1">Location<input class="min-h-12 rounded-lg border border-neutral-500 p-3" name="location" maxlength="150" value="{{ old('location') }}"></label>
<label class="grid gap-1">Email frequency<select name="frequency" class="min-h-12 rounded-lg border border-neutral-500 p-3"><option value="daily">Daily</option><option value="weekly">Weekly</option></select></label>
<label class="flex items-center gap-2"><input type="checkbox" name="email_opt_in" value="1"> Send me matching jobs by email (optional)</label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Save alert</button></form>
<h2 class="mt-8 text-xl font-bold">My saved alerts</h2>
@forelse($alerts as $alert)
<div class="mt-3 flex items-center justify-between gap-3 rounded-lg border border-neutral-300 p-4">
<p>{{ $alert->keywords ?: 'All keywords' }} · {{ $alert->location ?: 'Any location' }}</p>
<form method="POST" action="{{ route('seeker.alerts.update',$alert->id) }}" class="grid gap-2">@csrf @method('PATCH')
<label>Email delivery <input type="checkbox" name="email_opt_in" value="1" @checked($alert->email_opt_in)></label>
<label>Frequency <select name="frequency" class="rounded border border-neutral-500 p-2"><option value="daily" @selected($alert->frequency==='daily')>Daily</option><option value="weekly" @selected($alert->frequency==='weekly')>Weekly</option></select></label>
<button class="underline" type="submit">Save preferences</button></form>
<form method="POST" action="{{ route('seeker.alerts.delete',$alert->id) }}">@csrf @method('DELETE')<button class="underline" type="submit">Delete</button></form>
</div>
@empty<p class="mt-3">No alerts saved yet.</p>@endforelse
<h2 class="mt-10 text-xl font-bold">Email delivery history</h2>
<p class="mt-2 text-sm">Only you can see the delivery status of your saved alerts. No email addresses or error details are shown.</p>
<div class="mt-4 overflow-x-auto"><table class="w-full border-collapse text-left text-sm">
<thead><tr><th scope="col" class="border-b p-3">Alert</th><th scope="col" class="border-b p-3">Status</th><th scope="col" class="border-b p-3">Created</th><th scope="col" class="border-b p-3">Sent</th></tr></thead>
<tbody>
@forelse($history as $item)
<tr><td class="border-b p-3">{{ $item->keywords ?: $item->location ?: 'Saved alert' }}</td>
<td class="border-b p-3">{{ ucfirst($item->status) }}</td>
<td class="border-b p-3">{{ $item->created_at }}</td>
<td class="border-b p-3">{{ $item->sent_at ?: '—' }}</td></tr>
@empty<tr><td colspan="4" class="p-3">No email history yet.</td></tr>@endforelse
</tbody></table></div>
<div class="mt-4">{{ $history->links() }}</div>
</section></x-layouts.site>
