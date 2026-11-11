<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category', 80)->index();
            $table->longText('body');
            $table->string('status', 20)->default('draft')->index();
            $table->boolean('is_demo')->default(false);
            $table->string('verification_status', 30)->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->string('source_name')->nullable();
            $table->text('source_url')->nullable();
            $table->string('source_type')->nullable();
            $table->text('source_note')->nullable();
            $table->string('action_label')->nullable();
            $table->text('action_url')->nullable();
            $table->date('deadline')->nullable()->index();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->text('image_alt')->nullable();
            $table->string('author')->default('DeafJobsIndia Desk');
            $table->json('tags')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_breaking')->default(false);
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
