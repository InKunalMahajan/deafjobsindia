<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            // Older jobs can have no review status; new jobs default to unreviewed.
            $table->string('application_review_status', 20)
                ->nullable()
                ->default('unreviewed')
                ->change();
        });
    }

    public function down(): void
    {
        // A rollback must not silently replace or discard legacy NULL values.
        // Deliberately leave the compatible nullable schema in place.
    }
};
