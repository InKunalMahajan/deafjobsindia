<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->string('reason', 80);
            $table->text('details')->nullable();
            $table->string('reporter_email')->nullable();
            $table->string('status', 20)->default('pending')->index();
            $table->timestamps();
        });
        Schema::create('contact_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email');
            $table->string('subject', 200);
            $table->text('message');
            $table->string('status', 20)->default('new')->index();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('contact_enquiries');
        Schema::dropIfExists('job_reports');
    }
};
