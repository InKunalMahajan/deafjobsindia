<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->unsignedInteger('salary_min_annual')->nullable()->index();
            $table->unsignedInteger('salary_max_annual')->nullable()->index();
            $table->unsignedTinyInteger('experience_min_years')->nullable()->index();
            $table->unsignedTinyInteger('experience_max_years')->nullable();
        });
    }

    public function down(): void {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn([
                'salary_min_annual','salary_max_annual',
                'experience_min_years','experience_max_years',
            ]);
        });
    }
};
