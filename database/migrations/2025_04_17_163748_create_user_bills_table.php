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
        Schema::create('user_bills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('billing_month');


            $table->decimal('amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->string('discount_reason')->nullable();

            $table->enum('status', ['paid', 'pending', 'unpaid', 'rejected'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'bank_transfer'])->nullable();
            $table->date('transfer_date')->nullable();
            $table->string('transfer_proof')->nullable();
            $table->date('paid_at')->nullable();

            $table->string('invoice_number')->unique()->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'billing_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bills');
    }
};
