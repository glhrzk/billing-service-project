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
        Schema::create('package_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_bill_id')->constrained()->onDelete('cascade');
            $table->string('locked_name')->nullable();
            $table->string('locked_speed')->nullable();
            $table->string('locked_price')->nullable();
            $table->string('locked_description')->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->string('discount_reason')->nullable();
            $table->decimal('final_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_bills');
    }
};
