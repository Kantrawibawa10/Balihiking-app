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
        Schema::create('trail_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiking_trail_id')->constrained('hiking_trails')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('condition_note'); // Laporan dari pendaki mengenai kondisi jalur
            $table->enum('status', ['safe', 'blocked', 'hazardous'])->default('safe');
            $table->timestamp('report_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trail_reports');
    }
};
