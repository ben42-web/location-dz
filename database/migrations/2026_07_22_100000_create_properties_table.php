<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['apartment', 'house', 'room', 'studio', 'villa', 'other'])->default('apartment');
            $table->string('address');
            $table->string('city');
            $table->string('wilaya');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('price_per_night', 10, 2);
            $table->unsignedInteger('max_guests')->default(1);
            $table->unsignedInteger('bedrooms')->default(1);
            $table->unsignedInteger('bathrooms')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
