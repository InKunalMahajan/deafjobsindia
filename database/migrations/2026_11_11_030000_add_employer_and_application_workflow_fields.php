<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('employers',function(Blueprint $t){$t->string('contact_email')->nullable();$t->string('location')->nullable();});
  Schema::table('job_listings',function(Blueprint $t){
   $t->string('application_method',20)->default('external');
   $t->text('application_instructions')->nullable();
   $t->string('application_review_status',20)->default('unreviewed')->index();
   $t->timestamp('application_reviewed_at')->nullable();
   $t->foreignId('application_reviewed_by')->nullable()->constrained('users')->nullOnDelete();
  });
 }
 public function down(): void {
  Schema::table('job_listings',function(Blueprint $t){$t->dropConstrainedForeignId('application_reviewed_by');$t->dropColumn(['application_method','application_instructions','application_review_status','application_reviewed_at']);});
  Schema::table('employers',fn(Blueprint $t)=>$t->dropColumn(['contact_email','location']));
 }
};
