<x-layouts.admin :title="$employer->exists ? 'Edit employer' : 'Add employer'">
<form method="POST" action="{{ $employer->exists ? route('admin.employers.update',$employer) : route('admin.employers.store') }}" class="max-w-2xl space-y-5">
@csrf @if($employer->exists)@method('PUT')@endif
<flux:input label="Employer name" name="name" required :value="old('name',$employer->name)" />
<flux:input label="Slug" name="slug" required :value="old('slug',$employer->slug)" />
<flux:input label="Website (HTTPS)" name="website" type="url" :value="old('website',$employer->website)" />
<flux:textarea label="Description" name="description">{{ old('description',$employer->description) }}</flux:textarea>
<input type="hidden" name="verified" value="0">
<label class="flex items-center gap-3"><input type="checkbox" name="verified" value="1" @checked(old('verified',$employer->verified))> Verified employer (only after manual review)</label>
<flux:button type="submit" variant="primary">Save employer</flux:button>
</form></x-layouts.admin>
