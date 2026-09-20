<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simaksis', function (Blueprint $table) {

            $table->id();

            $table
                ->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('gunung', 150);

            $table->date('tanggal_naik');

            $table->date('tanggal_turun');

            $table
                ->unsignedInteger('jumlah_anggota')
                ->default(1);

            $table->string('nomor_darurat', 30);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            |
            | pending
            | approved
            | rejected
            |
            */

            $table
                ->string('status', 20)
                ->default('pending');

            $table
                ->text('catatan_admin')
                ->nullable();

            $table
                ->timestamp('approved_at')
                ->nullable();

            $table
                ->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index([
                'user_id',
                'status'
            ]);

            $table->index('tanggal_naik');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('simaksis');
    }
};