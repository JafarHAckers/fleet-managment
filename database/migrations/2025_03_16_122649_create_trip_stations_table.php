<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->integer('order'); // This defines the sequence of stations in a trip
            $table->timestamps();
            
            // A trip can't have the same city twice
            $table->unique(['trip_id', 'city_id']);
            // A trip can't have two stations with the same order
            $table->unique(['trip_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_stations');
    }
};