<x-layouts.admin title="Articles">
<div class="flex flex-wrap items-center justify-between gap-4">
  <p class="text-sm text-neutral-600">Manage drafts and published updates.</p>
  <a class="inline-block rounded-lg bg-black px-4 py-3 font-semibold text-white" href="{{ route('admin.articles.create') }}">Add article</a>
</div>
<div class="mt-6 overflow-x-auto rounded-xl border border-neutral-200">
<table class="w-full min-w-[620px] text-left text-sm">
<thead class="bg-neutral-50"><tr class="border-b"><th class="p-3">Title</th><th class="p-3">Category</th><th class="p-3">Publication</th><th class="p-3">Actions</th></tr></thead>
<tbody>
@forelse($articles as $article)
<tr class="border-b last:border-0">
<td class="p-3 font-medium">{{ $article->title }}</td>
<td class="p-3">{{ $article->category }}</td>
<td class="p-3">
  {{ ucfirst($article->status) }}
  @if($article->is_demo)<span class="block text-xs text-neutral-600">Demo — hidden publicly</span>@endif
  @if($article->status === 'published' && $article->published_at && $article->published_at->isFuture())
    <span class="block text-xs text-neutral-600">Scheduled {{ $article->published_at->format('d M Y') }}</span>
  @endif
</td>
<td class="p-3">
  <a class="underline underline-offset-4" href="{{ route('admin.articles.edit',$article) }}">Edit</a>
  @can('articles.publish')
  <form class="ml-3 inline" method="POST" action="{{ route('admin.articles.destroy',$article) }}" onsubmit="return confirm('Delete this article permanently?')">
    @csrf @method('DELETE')<button class="underline underline-offset-4" type="submit">Delete</button>
  </form>
  @endcan
</td></tr>
@empty<tr><td colspan="4" class="p-6 text-neutral-600">No articles yet.</td></tr>@endforelse
</tbody></table></div>
<div class="mt-6">{{ $articles->links() }}</div>
</x-layouts.admin>
