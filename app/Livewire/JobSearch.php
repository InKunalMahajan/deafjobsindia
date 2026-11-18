<?php

namespace App\Livewire;

use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class JobSearch extends Component {
    use WithPagination;

    public string $query = '';
    public string $location = '';
    public string $sector = '';
    public string $employmentType = '';
    public string $workMode = '';
    public string $category = '';
    public string $salary = '';
    public string $experience = '';
    public string $sort = 'newest';
    public bool $featuredOnly = false;
    public bool $deafFriendly = false;
    public bool $pwdEligible = false;
    public bool $openOnly = true;

    public function mount(string $initialQuery = ''): void {
        $this->query = mb_substr(trim($initialQuery), 0, 120);
    }

    public function updated(string $property): void { $this->resetPage(); }

    public function clearFilters(): void {
        $this->reset([
            'query','location','sector','employmentType','workMode',
            'category','salary','experience','sort','featuredOnly',
            'deafFriendly','pwdEligible',
        ]);
        $this->openOnly = true;
        $this->resetPage();
    }

    public function render(): View {
        $salary = in_array($this->salary, ['200000','400000','600000','1000000'], true)
            ? (int) $this->salary : null;
        $experience = in_array($this->experience, ['0','1','3','5','10'], true)
            ? (int) $this->experience : null;
        $sort = in_array($this->sort, ['newest','deadline','salary_high','salary_low'], true)
            ? $this->sort : 'newest';

        $query = JobListing::query()->with('employer')->published()
            ->when($this->openOnly, fn ($q) => $q->open())
            ->when($this->sector !== '', fn ($q) => $q->where('sector', $this->sector))
            ->when($this->employmentType !== '', fn ($q) => $q->where('employment_type', $this->employmentType))
            ->when($this->workMode !== '', fn ($q) => $q->where('work_mode', $this->workMode))
            ->when($this->featuredOnly, fn ($q) => $q->where('is_featured', true))
            ->when($this->deafFriendly, fn ($q) => $q->where('is_deaf_friendly', true))
            ->when($this->pwdEligible, fn ($q) => $q->where('is_pwd_eligible', true))
            ->when($this->category !== '' && mb_strlen($this->category) <= 150, function ($q) {
                $q->whereHas('category', fn ($category) =>
                    $category->where('slug', $this->category)->where('is_active', true));
            })
            ->when($salary !== null, fn ($q) => $q->whereNotNull('salary_max_annual')
                ->where('salary_max_annual', '>=', $salary))
            ->when($experience !== null, fn ($q) => $q->whereNotNull('experience_min_years')
                ->where('experience_min_years', '<=', $experience)
                ->where(fn ($inner) => $inner->whereNull('experience_max_years')
                    ->orWhere('experience_max_years', '>=', $experience)))
            ->when(trim($this->location) !== '', function ($q) {
                $term = '%'.addcslashes(mb_substr(trim($this->location), 0, 120), '%_\\').'%';
                $q->where('location', 'like', $term);
            })
            ->when(trim($this->query) !== '', function ($q) {
                $term = '%'.addcslashes(mb_substr(trim($this->query), 0, 120), '%_\\').'%';
                $q->where(fn ($inner) => $inner->where('title', 'like', $term)
                    ->orWhere('summary', 'like', $term)
                    ->orWhereHas('employer', fn ($employer) => $employer->where('name', 'like', $term)));
            });

        if ($sort === 'deadline') {
            $query->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END')
                ->orderBy('deadline')->latest('published_at');
        } elseif ($sort === 'salary_high') {
            $query->orderByRaw('CASE WHEN salary_max_annual IS NULL THEN 1 ELSE 0 END')
                ->orderByDesc('salary_max_annual')->latest('published_at');
        } elseif ($sort === 'salary_low') {
            $query->orderByRaw('CASE WHEN salary_min_annual IS NULL THEN 1 ELSE 0 END')
                ->orderBy('salary_min_annual')->latest('published_at');
        } else {
            $query->latest('published_at');
        }

        $query->orderByDesc('id');

        return view('livewire.job-search', [
            'jobs' => $query->paginate(12),
            'categories' => JobCategory::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
