<x-layouts.site title="Unsubscribe from job alerts" robots="noindex,nofollow">
<section class="dji-container max-w-xl py-12">
<h1 class="text-3xl font-bold">Stop job alert emails?</h1>
<p class="mt-4">You can stop emails for this saved alert. Your saved search will remain in your account.</p>
<form method="POST" action="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('seeker.alerts.unsubscribe.apply', now()->addMinutes(15), ['alert'=>$alert,'user'=>$user]) }}" class="mt-6">
@csrf
<button type="submit" class="min-h-12 rounded-lg bg-black px-5 text-white">Stop these emails</button>
</form>
</section></x-layouts.site>
