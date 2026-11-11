<x-layouts.admin :title="$article->exists ? 'Edit article' : 'Add article'">
<form method="POST" action="{{ $article->exists ? route('admin.articles.update',$article) : route('admin.articles.store') }}" class="max-w-3xl space-y-5">
@csrf @if($article->exists)@method('PUT')@endif
<flux:input label="Title" name="title" required :value="old('title',$article->title)" />
<flux:input label="URL slug" name="slug" required :value="old('slug',$article->slug)" />
<flux:input label="Category" name="category" required :value="old('category',$article->category)" />
<flux:textarea label="Short description" name="description" required>{{ old('description',$article->description) }}</flux:textarea>
<flux:textarea label="Article body (Markdown)" name="body" required rows="12">{{ old('body',$article->body) }}</flux:textarea>
<flux:input label="Source name" name="source_name" :value="old('source_name',$article->source_name)" />
<flux:input label="Source URL (HTTPS)" name="source_url" type="url" :value="old('source_url',$article->source_url)" />
<div><label class="mb-2 block text-sm font-medium" for="status">Publication status</label><select class="w-full rounded border p-3" id="status" name="status">
<option value="draft" @selected(old('status',$article->status ?? 'draft')==='draft')>Draft</option>
@can('articles.publish')<option value="published" @selected(old('status',$article->status)==='published')>Published</option>@endcan
</select></div>
<flux:button type="submit" variant="primary">Save article</flux:button>
</form></x-layouts.admin>
