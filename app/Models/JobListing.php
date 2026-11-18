<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobListing extends Model {
    protected $fillable = [
        'employer_id','slug','title','location','state','employment_type',
        'work_mode','sector','is_deaf_friendly','is_pwd_eligible','summary',
        'description','requirements','salary_text','source_url','apply_url',
        'deadline','status','published_at','job_category_id','is_featured',
        'salary_min_annual','salary_max_annual','experience_min_years','experience_max_years','application_method','application_instructions','application_review_status','application_reviewed_at','application_reviewed_by',
    ];
    protected function casts(): array {
        return ['is_deaf_friendly'=>'boolean','is_pwd_eligible'=>'boolean',
            'deadline'=>'date','is_featured'=>'boolean','published_at'=>'datetime','application_reviewed_at'=>'datetime'];
    }
    public function employer(): BelongsTo { return $this->belongsTo(Employer::class); }
    public function category(): BelongsTo { return $this->belongsTo(JobCategory::class, 'job_category_id'); }
    public function reports(): HasMany { return $this->hasMany(JobReport::class); }
    public function scopePublished(Builder $query): Builder {
        return $query->where('status','published')->whereNotNull('published_at')
            ->where('published_at','<=',now());
    }
    public function scopeOpen(Builder $query): Builder {
        return $query->where(fn (Builder $q) =>
            $q->whereNull('deadline')->orWhereDate('deadline','>=',today()));
    }
    public function getIsClosedAttribute(): bool {
        return $this->deadline !== null && $this->deadline->endOfDay()->isPast();
    }
}
