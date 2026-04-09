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
        Schema::create('supervisor_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supervisor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('placement_id')->constrained()->cascadeOnDelete();
            //academic or professional supervisor
            $table->enum('type', ['academic', 'professional']);

            $table->timestamps();
            $table->unique(
                ['student_id', 'supervisor_id', 'placement_id', 'type'],
                'sup_stu_unique'
                );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisor_student');
    }
};
