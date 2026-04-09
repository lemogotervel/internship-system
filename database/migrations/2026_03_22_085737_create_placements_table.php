<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('placements', function (Blueprint $table) {
            $table->id();
             $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('company_address');
            $table->string('company_supervisor_name');
            $table->string('company_supervisor_email')->nullable();
            $table->string('company_supervisor_phone')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();//internship description objectives

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamp('validated_at')->nullable;
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placements');
    }
};
