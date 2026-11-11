<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobListing extends Model {
    protected $fillable = [
        'employer_id','slug','title','location','state','employment_type',
        'work_mode','sector','is_deaf_friendly','is_pwd_eligible','summary',
        'description','requirements','salary_text','source_url','apply_url',
        'deadline','status','published_at',
    ];
    protected function casts(): array {
        return ['is_deaf_friendly'=>'boolean','is_pwd_eligible'=>'boolean',
            'deadline'=>'date','published_at'=>'datetime'];
    }
    public function employer(): BelongsTo { return $this->belongsTo(Employer::class); }
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
