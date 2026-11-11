<?php

namespace App\Livewire;

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
    public string $sort = 'newest';
    public bool $deafFriendly = false;
    public bool $pwdEligible = false;
    public bool $openOnly = true;

    public function mount(string $initialQuery = ''): void {
        $this->query = mb_substr(trim($initialQuery),0,120);
    }

    public function updated(string $property): void { $this->resetPage(); }

    public function clearFilters(): void {
        $this->reset(['query','location','sector','employmentType','workMode','sort','deafFriendly','pwdEligible']);
        $this->openOnly = true;
        $this->resetPage();
    }

    public function render(): View {
        $query = JobListing::query()->with('employer')->published()
            ->when($this->openOnly,fn ($q) => $q->open())
            ->when($this->sector !== '',fn ($q) => $q->where('sector',$this->sector))
            ->when($this->employmentType !== '',fn ($q) => $q->where('employment_type',$this->employmentType))
            ->when($this->workMode !== '',fn ($q) => $q->where('work_mode',$this->workMode))
            ->when($this->deafFriendly,fn ($q) => $q->where('is_deaf_friendly',true))
            ->when($this->pwdEligible,fn ($q) => $q->where('is_pwd_eligible',true))
            ->when(trim($this->location) !== '',function ($q) {
                $term = '%'.addcslashes(trim($this->location),'%_\\').'%';
                $q->where('location','like',$term);
            })
            ->when(trim($this->query) !== '',function ($q) {
                $term = '%'.addcslashes(trim($this->query),'%_\\').'%';
                $q->where(fn ($inner) => $inner->where('title','like',$term)
                    ->orWhere('summary','like',$term)
                    ->orWhereHas('employer',fn ($e) => $e->where('name','like',$term)));
            });

        if ($this->sort === 'deadline') {
            $query->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END')
                ->orderBy('deadline')->latest('published_at');
        } else {
            $query->latest('published_at');
        }

        return view('livewire.job-search',['jobs'=>$query->paginate(12)]);
    }
}
