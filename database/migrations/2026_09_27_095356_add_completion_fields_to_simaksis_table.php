<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | UP
    |--------------------------------------------------------------------------
    */

    public function up(): void
    {
        Schema::table(
            'simaksis',
            function (Blueprint $table): void {
                /*
                |--------------------------------------------------------------------------
                | COMPLETED BY
                |--------------------------------------------------------------------------
                */

                if (
                    ! Schema::hasColumn(
                        'simaksis',
                        'completed_by'
                    )
                ) {
                    $table
                        ->foreignId(
                            'completed_by'
                        )
                        ->nullable()
                        ->constrained(
                            'users'
                        )
                        ->nullOnDelete();
                }

                /*
                |--------------------------------------------------------------------------
                | COMPLETED AT
                |--------------------------------------------------------------------------
                */

                if (
                    ! Schema::hasColumn(
                        'simaksis',
                        'completed_at'
                    )
                ) {
                    $table
                        ->timestamp(
                            'completed_at'
                        )
                        ->nullable();
                }
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DOWN
    |--------------------------------------------------------------------------
    */

    public function down(): void
    {
        Schema::table(
            'simaksis',
            function (Blueprint $table): void {
                if (
                    Schema::hasColumn(
                        'simaksis',
                        'completed_by'
                    )
                ) {
                    $table
                        ->dropConstrainedForeignId(
                            'completed_by'
                        );
                }

                if (
                    Schema::hasColumn(
                        'simaksis',
                        'completed_at'
                    )
                ) {
                    $table
                        ->dropColumn(
                            'completed_at'
                        );
                }
            }
        );
    }
};