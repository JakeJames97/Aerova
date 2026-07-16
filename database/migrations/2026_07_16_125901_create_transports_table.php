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
        Schema::create('transports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('destination_id')->constrained()->cascadeOnDelete();

            $table->enum('type', ['flight', 'train', 'car', 'other'])->index();
            $table->string('from');
            $table->string('to');
            $table->string('identifier')->nullable();
            $table->dateTime('departure_at');
            $table->dateTime('arrival_at');
            $table->unsignedInteger('price');

            $table->string('airline')->nullable();
            $table->string('from_iata', 3)->nullable();
            $table->string('to_iata', 3)->nullable();

            $table->unsignedInteger('current_price')->nullable();
            $table->timestamp('price_checked_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
