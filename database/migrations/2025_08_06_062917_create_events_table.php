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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->dateTime('date');
            $table->dateTime('end_date')->nullable();
            $table->string('location');
            $table->string('venue')->nullable();
            $table->text('address')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('country')->default('USA');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('vip_price', 8, 2)->nullable();
            $table->decimal('premium_price', 8, 2)->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('available_tickets')->nullable();
            $table->string('image')->nullable();
            $table->json('performers')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'cancelled'])->default('pending');
            $table->boolean('featured')->default(false);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('booking_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
