<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trail_guides', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('hiking_trail_id')
                ->constrained('hiking_trails')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->string(
                    'type',
                    30
                );

            $table
                ->string(
                    'title'
                );

            $table
                ->text(
                    'content'
                );

            $table
                ->unsignedInteger(
                    'sort_order'
                )
                ->default(0);

            $table
                ->boolean(
                    'is_active'
                )
                ->default(true);

            $table->timestamps();

            $table->index([
                'hiking_trail_id',
                'type',
                'is_active',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'trail_guides'
        );
    }
};