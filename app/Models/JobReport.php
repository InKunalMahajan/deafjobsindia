<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobReport extends Model {
    protected $fillable = ['job_listing_id', 'reason', 'details', 'reporter_email', 'status'];
    public function jobListing(): BelongsTo { return $this->belongsTo(JobListing::class); }
}
