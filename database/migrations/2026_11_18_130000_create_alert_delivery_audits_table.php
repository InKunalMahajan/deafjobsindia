<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alert_delivery_audits',function(Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained('job_alert_outbox')->cascadeOnDelete();
            $table->foreignId('actor_id')->constrained('users')->cascadeOnDelete();
            $table->string('action',40);
            $table->timestamp('created_at');
        });
    }
    public function down(): void { Schema::dropIfExists('alert_delivery_audits'); }
};
