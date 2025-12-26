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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('INR');
            $table->string('donor_email');
            $table->string('donor_name')->nullable();
            $table->string('donor_phone')->nullable();
            $table->string('transaction_id')->unique();
            $table->string('payment_gateway'); // stripe, razorpay
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_interval')->nullable(); // monthly, quarterly, yearly
            $table->string('subscription_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->string('receipt_number')->nullable();
            $table->boolean('receipt_sent')->default(false);
            $table->timestamp('receipt_sent_at')->nullable();
            $table->text('donor_message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('donor_email');
            $table->index('status');
            $table->index('payment_gateway');
            $table->index('is_recurring');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
