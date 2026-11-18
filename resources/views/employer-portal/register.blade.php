<x-layouts.site title="Employer registration" robots="noindex,nofollow">
<section class="dji-container max-w-xl py-12"><h1 class="text-3xl font-bold">Register your company</h1>
<p class="mt-3">All companies start unverified. Jobs require administrator approval before publication.</p>
@if($errors->any())<p role="alert" class="mt-4">{{ $errors->first() }}</p>@endif
<form method="POST" action="{{ route('employer.register.store') }}" class="mt-6 grid gap-4">@csrf
@foreach(['name'=>'Contact person','email'=>'Work email','company_name'=>'Company name','company_location'=>'Company location','company_website'=>'Company website (HTTPS, optional)'] as $field=>$label)
<label class="grid gap-2">{{ $label }}<input class="min-h-12 rounded-lg border border-neutral-500 px-3" name="{{ $field }}" type="{{ $field==='email' ? 'email' : 'text' }}" maxlength="{{ $field==='company_website' ? 2048 : 255 }}" value="{{ old($field) }}" @required($field!=='company_website')></label>
@endforeach
<label class="grid gap-2">Password (minimum 12 characters)<input class="min-h-12 rounded-lg border border-neutral-500 px-3" type="password" name="password" autocomplete="new-password" required minlength="12"></label>
<label class="grid gap-2">Confirm password<input class="min-h-12 rounded-lg border border-neutral-500 px-3" type="password" name="password_confirmation" autocomplete="new-password" required></label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Register employer</button></form>
<a class="mt-5 inline-block underline" href="{{ route('employer.login') }}">Already registered? Sign in</a>
</section></x-layouts.site>
