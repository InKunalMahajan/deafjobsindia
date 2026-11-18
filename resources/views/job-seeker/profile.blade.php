<x-layouts.site title="My profile" robots="noindex,nofollow">
<section class="dji-container max-w-2xl py-12"><h1 class="text-3xl font-bold">My profile</h1>
<p class="mt-2">Email: {{ $user->email }}</p>
@if(session('status'))<p role="status" class="mt-4">{{ session('status') }}</p>@endif
@if($errors->any())<p role="alert" class="mt-4">{{ $errors->first() }}</p>@endif
<form method="POST" action="{{ route('seeker.profile.update') }}" class="mt-6 grid gap-4">@csrf @method('PATCH')
<label class="grid gap-2">Name<input class="min-h-12 rounded-lg border border-neutral-500 px-3" name="name" required maxlength="255" value="{{ old('name',$user->name) }}"></label>
<label class="grid gap-2">Location<input class="min-h-12 rounded-lg border border-neutral-500 px-3" name="location" maxlength="255" value="{{ old('location',$user->location) }}"></label>
<label class="grid gap-2">About me<textarea class="rounded-lg border border-neutral-500 p-3" name="bio" maxlength="2000" rows="5">{{ old('bio',$user->bio) }}</textarea></label>
<label class="grid gap-2">Professional headline<input class="min-h-12 rounded-lg border border-neutral-500 px-3" name="headline" maxlength="255" value="{{ old('headline',$user->headline) }}"></label>
<label class="grid gap-2">Highest education<input class="min-h-12 rounded-lg border border-neutral-500 px-3" name="education" maxlength="255" value="{{ old('education',$user->education) }}"></label>
<label class="grid gap-2">Years of experience<input class="min-h-12 rounded-lg border border-neutral-500 px-3" type="number" min="0" max="80" name="experience_years" value="{{ old('experience_years',$user->experience_years) }}"></label>
<label class="grid gap-2">Skills (comma separated)<textarea class="rounded-lg border border-neutral-500 p-3" name="skills" maxlength="1000" rows="3">{{ old('skills',$user->skills) }}</textarea></label>
<label class="grid gap-2">Preferred job location<input class="min-h-12 rounded-lg border border-neutral-500 px-3" name="preferred_location" maxlength="255" value="{{ old('preferred_location',$user->preferred_location) }}"></label>
<label class="grid gap-2">Preferred work mode<select class="min-h-12 rounded-lg border border-neutral-500 p-3" name="preferred_work_mode">
<option value="">Any</option>
@foreach(['remote'=>'Remote','hybrid'=>'Hybrid','on-site'=>'On-site'] as $value=>$label)
<option value="{{ $value }}" @selected(old('preferred_work_mode',$user->preferred_work_mode)===$value)>{{ $label }}</option>
@endforeach
</select></label>
<label class="flex items-center gap-3"><input type="checkbox" name="requires_captions" value="1" @checked(old('requires_captions',$user->requires_captions))> I prefer captions</label>
<label class="flex items-center gap-3"><input type="checkbox" name="prefers_isl" value="1" @checked(old('prefers_isl',$user->prefers_isl))> I prefer Indian Sign Language communication</label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Save profile</button></form>
<section class="mt-10 border-t border-neutral-300 pt-6"><h2 class="text-xl font-bold">Private resume</h2>
<p class="mt-2 text-sm">PDF, DOC or DOCX. Maximum 3 MB. Only you can download it from your account.</p>
@if($user->resume_path)
<p class="mt-3">Current file: {{ $user->resume_original_name }}</p>
<a class="mt-3 inline-block underline" href="{{ route('seeker.resume.download') }}">Download resume</a>
<form class="mt-3" method="POST" action="{{ route('seeker.resume.destroy') }}">@csrf @method('DELETE')<button class="underline" type="submit">Delete resume</button></form>
@endif
<form class="mt-4 grid gap-3" method="POST" enctype="multipart/form-data" action="{{ route('seeker.resume.store') }}">@csrf
<label class="grid gap-2">Upload resume<input type="file" name="resume" accept=".pdf,.doc,.docx" required></label>
<button class="min-h-12 rounded-lg bg-black px-4 text-white" type="submit">Upload resume</button></form>
</section>
<a class="mt-6 inline-block underline" href="{{ route('seeker.dashboard') }}">Back to dashboard</a>
</section></x-layouts.site>
