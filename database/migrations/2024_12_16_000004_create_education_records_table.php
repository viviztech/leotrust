<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('education_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->string('school_name')->nullable();
            $table->string('grade')->nullable();
            $table->string('section')->nullable();
            $table->string('academic_year')->nullable();
            $table->decimal('attendance_percentage', 5, 2)->nullable();
            $table->enum('performance', ['excellent', 'good', 'average', 'below_average', 'poor'])->nullable();
            $table->text('subjects')->nullable();
            $table->text('achievements')->nullable();
            $table->text('areas_of_improvement')->nullable();
            $table->string('teacher_contact')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('academic_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_records');
    }
};
