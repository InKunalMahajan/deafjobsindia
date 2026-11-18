<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('job_alerts',function(Blueprint $table) {
            $table->boolean('email_opt_in')->default(false);
            $table->string('frequency',20)->default('daily');
            $table->timestamp('last_sent_at')->nullable();
        });
        Schema::create('job_alert_deliveries',function(Blueprint $table) {
            $table->id();
            $table->foreignId('job_alert_id')->constrained('job_alerts')->cascadeOnDelete();
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['job_alert_id','job_listing_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('job_alert_deliveries');
        Schema::table('job_alerts',fn(Blueprint $table)=>$table->dropColumn(['email_opt_in','frequency','last_sent_at']));
    }
};
