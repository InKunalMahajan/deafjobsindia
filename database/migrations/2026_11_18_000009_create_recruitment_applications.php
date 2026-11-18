<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('recruitment_applications',function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->text('cover_note')->nullable();
            $table->string('status',30)->default('submitted');
            $table->timestamp('interview_at')->nullable();
            $table->text('interview_instructions')->nullable();
            $table->timestamps();
            $table->unique(['user_id','job_listing_id']);
            $table->index(['job_listing_id','status']);
        });
    }
    public function down(): void { Schema::dropIfExists('recruitment_applications'); }
};
