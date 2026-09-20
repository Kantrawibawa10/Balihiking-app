<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('identity_documents', function (Blueprint $table) {

            $table->id();

            $table
                ->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | ktp / sim
            |--------------------------------------------------------------------------
            */

            $table->string('document_type', 20);

            $table
                ->string('document_number', 100)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Private storage file
            |--------------------------------------------------------------------------
            */

            $table->string('file_path');

            $table
                ->string('original_name')
                ->nullable();

            $table
                ->string('mime_type', 100)
                ->nullable();

            $table
                ->unsignedBigInteger('file_size')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Verification
            |--------------------------------------------------------------------------
            |
            | pending
            | verified
            | rejected
            |--------------------------------------------------------------------------
            */

            $table
                ->string('status', 20)
                ->default('pending');

            $table
                ->text('verification_note')
                ->nullable();

            $table
                ->timestamp('verified_at')
                ->nullable();

            $table
                ->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Satu KTP dan satu SIM per user
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'user_id',
                'document_type'
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'identity_documents'
        );
    }
};