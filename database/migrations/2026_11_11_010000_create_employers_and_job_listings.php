<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained()->restrictOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('location')->index();
            $table->string('state')->nullable()->index();
            $table->string('employment_type', 30)->index();
            $table->string('work_mode', 20)->index();
            $table->string('sector', 30)->index();
            $table->boolean('is_deaf_friendly')->default(false);
            $table->boolean('is_pwd_eligible')->default(false);
            $table->text('summary');
            $table->longText('description');
            $table->longText('requirements')->nullable();
            $table->string('salary_text')->nullable();
            $table->string('source_url', 2048);
            $table->string('apply_url', 2048);
            $table->date('deadline')->nullable()->index();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('job_listings');
        Schema::dropIfExists('employers');
    }
};
