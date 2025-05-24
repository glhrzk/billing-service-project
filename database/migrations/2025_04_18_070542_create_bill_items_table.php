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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_bill_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();

            // Snapshot Information when the bill is created
            $table->string('billed_package_name')->nullable();
            $table->decimal('billed_package_price', 10, 2)->nullable();
            $table->string('billed_package_speed')->nullable();
            $table->text('billed_package_description')->nullable();

            // Promo New Package or Something like that
            $table->decimal('package_discount_amount', 10, 2)->nullable();
            $table->string('package_discount_reason')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
