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

            // make snapshot of the package at the time of assignment
            $table->string('locked_name')->nullable();
            $table->string('locked_price')->nullable();
            $table->string('locked_speed')->nullable();
            $table->string('locked_description')->nullable();


            $table->decimal('initial_discount_amount', 10, 2)->nullable();
            $table->string('initial_discount_reason')->nullable();
            $table->tinyInteger('initial_discount_duration')->nullable();
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
