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
        Schema::create('hiking_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mountain_id')->constrained('mountains')->onDelete('cascade');
            $table->string('name'); // Contoh: Jalur Pasar Agung, Jalur Besakih
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->decimal('distance_km', 5, 2); // Panjang jalur dalam KM
            $table->decimal('estimated_time_hours', 4, 1); // Estimasi waktu tempuh
            $table->json('map_geojson')->nullable(); // Koordinat GeoJSON rute jalur
            $table->enum('status', ['open', 'closed', 'warning'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hiking_trails');
    }
};
