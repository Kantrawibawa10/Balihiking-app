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
        Schema::create('checkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiking_trail_id')->constrained('hiking_trails')->onDelete('cascade');
            $table->string('name'); // Contoh: Pos 1, Sumber Air Kiri, Puncak
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('elevation_m')->nullable(); // Ketinggian dalam mdpl
            $table->enum('type', ['pos', 'water_source', 'campsite', 'peak', 'danger_zone'])->default('pos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkpoints');
    }
};
