<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_application_status_history',function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->string('status',20);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id','job_listing_id','created_at'],'tracker_history_lookup');
        });
    }
    public function down(): void {
        Schema::dropIfExists('job_application_status_history');
    }
};
