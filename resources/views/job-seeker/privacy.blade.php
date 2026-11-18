<x-layouts.site title="Privacy and my data" robots="noindex,nofollow">
<section class="dji-container max-w-3xl py-12">
<a class="underline" href="{{ route('seeker.dashboard') }}">Back to dashboard</a>
<h1 class="mt-5 text-3xl font-bold">Privacy and my data</h1>
<p class="mt-3">Your account information belongs to you. You can download a JSON copy of your profile, saved jobs, job alerts and application records.</p>
<p class="mt-3">Your resume file is not included in the JSON export. Download it separately from your profile page.</p>
<div class="mt-6 flex flex-wrap gap-4">
<a class="inline-flex min-h-12 items-center rounded-lg border border-black px-5 font-semibold" href="{{ route('seeker.privacy.export') }}">Download my data (JSON)</a>
<a class="inline-flex min-h-12 items-center rounded-lg border border-black px-5 font-semibold" href="{{ route('seeker.profile') }}">Manage my resume</a>
<a class="inline-flex min-h-12 items-center rounded-lg border border-black px-5 font-semibold" href="{{ route('seeker.alerts') }}">Manage email alerts</a>
</div>
<h2 class="mt-10 text-xl font-bold">Account deletion</h2>
<p class="mt-2">Self-service account deletion is not available yet. Please use the <a class="underline" href="{{ route('contact') }}">contact page</a> to request deletion. Your request must be verified before processing.</p>
<h2 class="mt-8 text-xl font-bold">Accessibility</h2>
<p class="mt-2">You can set your Indian Sign Language and caption preferences in your <a class="underline" href="{{ route('seeker.profile') }}">profile</a>. These preferences are optional.</p>
</section></x-layouts.site>
