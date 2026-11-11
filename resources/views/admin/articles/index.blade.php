<x-layouts.admin title="Articles">
<a class="inline-block rounded bg-black px-4 py-3 font-semibold text-white" href="{{ route('admin.articles.create') }}">Add article</a>
<div class="mt-6 overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b"><th class="p-3">Title</th><th class="p-3">Category</th><th class="p-3">Status</th><th class="p-3">Actions</th></tr></thead><tbody>
@forelse($articles as $article)<tr class="border-b"><td class="p-3">{{ $article->title }}</td><td class="p-3">{{ $article->category }}</td><td class="p-3">{{ $article->status }}</td><td class="p-3">
<a class="underline" href="{{ route('admin.articles.edit',$article) }}">Edit</a>
@can('articles.publish')<form class="inline ml-3" method="POST" action="{{ route('admin.articles.destroy',$article) }}" onsubmit="return confirm('Delete article?')">@csrf @method('DELETE')<button class="underline" type="submit">Delete</button></form>@endcan
</td></tr>@empty<tr><td colspan="4" class="p-4">No articles yet.</td></tr>@endforelse
</tbody></table></div><div class="mt-6">{{ $articles->links() }}</div>
</x-layouts.admin>
