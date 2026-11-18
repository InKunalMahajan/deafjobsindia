<x-layouts.admin :title="$article->exists ? 'Edit article' : 'Add article'">
<form method="POST" action="{{ $article->exists ? route('admin.articles.update',$article) : route('admin.articles.store') }}" class="max-w-3xl space-y-5">
@csrf @if($article->exists)@method('PUT')@endif
<flux:input label="Title" name="title" required :value="old('title',$article->title)" />
<flux:input label="URL slug" name="slug" required :value="old('slug',$article->slug)" />
<div>
<label class="mb-2 block text-sm font-medium" for="category">Article category</label>
<select class="w-full rounded-lg border border-neutral-300 bg-white p-3 text-neutral-900" id="category" name="category" required>
@foreach(['Jobs','Education','Training','Career','Government Schemes','Job Fairs','Community','Technology'] as $categoryOption)
<option value="{{ $categoryOption }}" @selected(old('category',$article->category)===$categoryOption)>{{ $categoryOption }}</option>
@endforeach
@if($article->category && !in_array($article->category, ['Jobs','Education','Training','Career','Government Schemes','Job Fairs','Community','Technology'], true))
<option value="{{ $article->category }}" selected>{{ $article->category }} (existing category)</option>
@endif
</select>
</div>
<flux:textarea label="Short description" name="description" required>{{ old('description',$article->description) }}</flux:textarea>
<flux:textarea label="Article body (Markdown)" name="body" required rows="12">{{ old('body',$article->body) }}</flux:textarea>
<flux:input label="Source name" name="source_name" :value="old('source_name',$article->source_name)" />
<flux:input label="Source URL (HTTPS)" name="source_url" type="url" :value="old('source_url',$article->source_url)" />
<div><label class="mb-2 block text-sm font-medium" for="status">Publication status</label>
<select class="w-full rounded-lg border border-neutral-300 bg-white p-3 text-neutral-900" id="status" name="status">
<option value="draft" @selected(old('status',$article->status ?? 'draft')==='draft')>Draft</option>
@can('articles.publish')<option value="published" @selected(old('status',$article->status)==='published')>Published</option>@endcan
</select></div>
<p class="text-xs text-neutral-600">Only authorized publishers can publish articles. Draft articles are not visible to the public.</p>
<flux:button type="submit" variant="primary">Save article</flux:button>
</form></x-layouts.admin>
