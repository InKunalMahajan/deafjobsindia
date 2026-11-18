<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_alert_outbox',function(Blueprint $table) {
            $table->id();
            $table->foreignId('job_alert_id')->constrained('job_alerts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('dedupe_key',64)->unique();
            $table->json('job_ids');
            $table->string('status',20)->default('pending')->index();
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('next_attempt_at')->nullable()->index();
            $table->timestamp('sent_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('job_alert_outbox'); }
};
