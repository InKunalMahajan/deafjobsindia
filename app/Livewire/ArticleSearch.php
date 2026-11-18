<?php

namespace App\Livewire;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleSearch extends Component
{
    use WithPagination;

    public string $query = '';
    public string $category = '';

    public function mount(string $initialQuery = '', string $category = ''): void
    {
        $this->query = mb_substr(trim($initialQuery), 0, 120);
        $this->category = mb_substr(trim($category), 0, 100);
    }

    public function updatedQuery(): void { $this->resetPage(); }
    public function updatedCategory(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->query = '';
        $this->category = '';
        $this->resetPage();
    }

    public function render(): View
    {
        $query = mb_substr(trim($this->query), 0, 120);
        $category = mb_substr(trim($this->category), 0, 100);

        $articles = Article::published()
            ->when($category !== '', fn ($builder) => $builder->where('category', $category))
            ->when($query !== '', function ($builder) use ($query): void {
                $term = '%'.addcslashes($query, '%_\\').'%';
                $builder->where(function ($inner) use ($term): void {
                    $inner->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('location', 'like', $term);
                });
            })
            ->latest('published_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.article-search', ['articles' => $articles]);
    }
}
