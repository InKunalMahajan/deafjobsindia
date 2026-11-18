<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employer_memberships',function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employer_id')->constrained()->cascadeOnDelete();
            $table->string('role',20)->default('owner');
            $table->timestamps();
            $table->unique(['user_id','employer_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('employer_memberships'); }
};
