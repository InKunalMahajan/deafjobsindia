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

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $articles = Article::published()
            ->when($this->category !== '', fn ($builder) => $builder->where('category', $this->category))
            ->when(trim($this->query) !== '', function ($builder): void {
                $term = '%'.trim($this->query).'%';

                $builder->where(function ($inner) use ($term): void {
                    $inner->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('location', 'like', $term);
                });
            })
            ->latest('published_at')
            ->paginate(10);

        return view('livewire.article-search', [
            'articles' => $articles,
        ]);
    }
}
