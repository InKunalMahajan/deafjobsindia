<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
        });
        Schema::create('saved_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id','job_listing_id']);
        });
        Schema::create('job_application_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->string('status',20)->default('interested');
            $table->date('applied_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['user_id','job_listing_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('job_application_trackers');
        Schema::dropIfExists('saved_jobs');
        Schema::table('users',fn (Blueprint $table)=>$table->dropColumn(['google_id','bio','location']));
    }
};
