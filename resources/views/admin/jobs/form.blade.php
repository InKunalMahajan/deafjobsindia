<x-layouts.admin :title="$job->exists ? 'Edit job' : 'Add job'">
<form method="POST" action="{{ $job->exists ? route('admin.jobs.update',$job) : route('admin.jobs.store') }}" class="max-w-3xl space-y-5">
@csrf @if($job->exists)@method('PUT')@endif
<div><label class="mb-2 block text-sm font-medium" for="employer_id">Employer</label><select class="w-full rounded border border-neutral-300 p-3" id="employer_id" name="employer_id" required>
<option value="">Select employer</option>@foreach($employers as $employer)<option value="{{ $employer->id }}" @selected(old('employer_id',$job->employer_id)==$employer->id)>{{ $employer->name }}</option>@endforeach</select></div>
<flux:input name="title" label="Job title" required :value="old('title',$job->title)" />
<flux:input name="slug" label="URL slug" required :value="old('slug',$job->slug)" />
<flux:input name="location" label="Location" required :value="old('location',$job->location)" />
<flux:input name="state" label="State" :value="old('state',$job->state)" />
@foreach([
'employment_type'=>['full-time','part-time','contract','internship'],
'work_mode'=>['onsite','hybrid','remote'],
'sector'=>['government','private','nonprofit'],
] as $field=>$options)
<div><label class="mb-2 block text-sm font-medium" for="{{ $field }}">{{ ucwords(str_replace('_',' ',$field)) }}</label><select id="{{ $field }}" name="{{ $field }}" required class="w-full rounded border border-neutral-300 p-3">
@foreach($options as $option)<option value="{{ $option }}" @selected(old($field,$job->$field)===$option)>{{ ucfirst($option) }}</option>@endforeach
</select></div>
@endforeach
<flux:textarea name="summary" label="Short summary" required>{{ old('summary',$job->summary) }}</flux:textarea>
<flux:textarea name="description" label="Full description (Markdown supported)" required>{{ old('description',$job->description) }}</flux:textarea>
<flux:textarea name="requirements" label="Requirements">{{ old('requirements',$job->requirements) }}</flux:textarea>
<flux:input name="salary_text" label="Salary information" :value="old('salary_text',$job->salary_text)" />
<flux:input name="source_url" label="Original source URL (HTTPS)" type="url" required :value="old('source_url',$job->source_url)" />
<flux:input name="apply_url" label="Application URL (HTTPS)" type="url" required :value="old('apply_url',$job->apply_url)" />
<flux:input name="deadline" label="Application deadline" type="date" :value="old('deadline',$job->deadline?->format('Y-m-d'))" />
<input type="hidden" name="is_deaf_friendly" value="0"><label class="flex gap-3"><input type="checkbox" name="is_deaf_friendly" value="1" @checked(old('is_deaf_friendly',$job->is_deaf_friendly))> Employer reports Deaf-friendly accommodations</label>
<input type="hidden" name="is_pwd_eligible" value="0"><label class="flex gap-3"><input type="checkbox" name="is_pwd_eligible" value="1" @checked(old('is_pwd_eligible',$job->is_pwd_eligible))> Source reports PwD eligibility</label>
<div><label class="mb-2 block text-sm font-medium" for="status">Publication status</label><select name="status" id="status" class="w-full rounded border border-neutral-300 p-3">
<option value="draft" @selected(old('status',$job->status ?? 'draft')==='draft')>Draft</option>
@can('jobs.publish')<option value="published" @selected(old('status',$job->status)==='published')>Published</option>@endcan
</select></div>
<flux:button type="submit" variant="primary">Save job</flux:button>
</form></x-layouts.admin>
