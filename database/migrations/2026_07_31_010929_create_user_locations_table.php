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
        Schema::create('user_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hiking_trail_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('altitude_m')->nullable(); // Ketinggian saat ini
            $table->integer('battery_level')->nullable(); // Persentase baterai HP pendaki
            $table->enum('status', ['moving', 'resting', 'sos'])->default('moving'); // Indikator bahaya
            $table->timestamp('recorded_at');
            $table->timestamps();

            // Indexing agar query tracking cepat
            $table->index(['user_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_locations');
    }
};
