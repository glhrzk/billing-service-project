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
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');

            /*
             *  make snapshot of the package at the time of assignment
             *  this is to prevent the package from being changed in the future
             *  and to keep the package data consistent
             */
            $table->string('package_name_snapshot')->nullable();
            $table->decimal('package_price_snapshot', 10, 2)->nullable();
            $table->string('package_speed_snapshot')->nullable();
            $table->text('package_description_snapshot')->nullable();

            // Initial discount details with dynamic values
            $table->decimal('active_discount_amount', 10, 2)->nullable();
            $table->string('active_discount_reason')->nullable();
            $table->tinyInteger('active_discount_duration')->nullable(); // Duration in months

            $table->enum('is_active', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Adding a unique constraint to ensure a user can only have one package at a time
            // and a package can only be assigned to one user at a time
            $table->unique(['user_id', 'package_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user-packages');
    }
};
