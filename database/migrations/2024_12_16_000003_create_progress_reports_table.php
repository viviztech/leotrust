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
        Schema::create('progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('report_date');
            $table->string('title');
            $table->text('summary');
            $table->text('observations')->nullable();
            $table->text('recommendations')->nullable();
            $table->enum('overall_status', ['excellent', 'good', 'satisfactory', 'needs_attention', 'critical'])->default('good');
            $table->decimal('health_score', 3, 1)->nullable(); // 0.0 to 10.0
            $table->decimal('behavior_score', 3, 1)->nullable();
            $table->decimal('progress_score', 3, 1)->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->index('report_date');
            $table->index('overall_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_reports');
    }
};
