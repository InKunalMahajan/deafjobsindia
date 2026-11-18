<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users',function(Blueprint $table) {
            $table->string('headline')->nullable();
            $table->string('education')->nullable();
            $table->unsignedSmallInteger('experience_years')->nullable();
            $table->text('skills')->nullable();
            $table->string('preferred_location')->nullable();
            $table->string('preferred_work_mode',20)->nullable();
            $table->boolean('requires_captions')->default(false);
            $table->boolean('prefers_isl')->default(false);
            $table->string('resume_path')->nullable();
            $table->string('resume_original_name')->nullable();
        });
        Schema::create('job_alerts',function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('keywords')->nullable();
            $table->string('location')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('job_alerts');
        Schema::table('users',fn(Blueprint $table)=>$table->dropColumn([
            'headline','education','experience_years','skills','preferred_location',
            'preferred_work_mode','requires_captions','prefers_isl','resume_path','resume_original_name',
        ]));
    }
};
