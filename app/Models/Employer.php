<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer extends Model {
    protected $fillable = ['name','slug','website','description','verified'];
    protected function casts(): array { return ['verified' => 'boolean']; }
    public function jobs(): HasMany { return $this->hasMany(JobListing::class); }
}
