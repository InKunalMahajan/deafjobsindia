<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 150)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::table('job_listings', function (Blueprint $table) {
            $table->foreignId('job_category_id')->nullable()->constrained('job_categories')->nullOnDelete();
            $table->boolean('is_featured')->default(false)->index();
        });
    }

    public function down(): void {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('job_category_id');
            $table->dropColumn('is_featured');
        });
        Schema::dropIfExists('job_categories');
    }
};
