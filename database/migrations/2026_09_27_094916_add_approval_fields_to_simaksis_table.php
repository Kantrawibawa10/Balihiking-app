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
            function (
                Blueprint $table
            ): void {
                /*
                |--------------------------------------------------------------------------
                | APPROVAL
                |--------------------------------------------------------------------------
                */

                if (
                    ! Schema::hasColumn(
                        'simaksis',
                        'approved_by'
                    )
                ) {
                    $table
                        ->foreignId(
                            'approved_by'
                        )
                        ->nullable()
                        ->constrained(
                            'users'
                        )
                        ->nullOnDelete();
                }


                if (
                    ! Schema::hasColumn(
                        'simaksis',
                        'approved_at'
                    )
                ) {
                    $table
                        ->timestamp(
                            'approved_at'
                        )
                        ->nullable();
                }


                /*
                |--------------------------------------------------------------------------
                | REJECTION
                |--------------------------------------------------------------------------
                */

                if (
                    ! Schema::hasColumn(
                        'simaksis',
                        'rejected_by'
                    )
                ) {
                    $table
                        ->foreignId(
                            'rejected_by'
                        )
                        ->nullable()
                        ->constrained(
                            'users'
                        )
                        ->nullOnDelete();
                }


                if (
                    ! Schema::hasColumn(
                        'simaksis',
                        'rejected_at'
                    )
                ) {
                    $table
                        ->timestamp(
                            'rejected_at'
                        )
                        ->nullable();
                }


                if (
                    ! Schema::hasColumn(
                        'simaksis',
                        'rejection_reason'
                    )
                ) {
                    $table
                        ->text(
                            'rejection_reason'
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
            function (
                Blueprint $table
            ): void {
                if (
                    Schema::hasColumn(
                        'simaksis',
                        'approved_by'
                    )
                ) {
                    $table
                        ->dropConstrainedForeignId(
                            'approved_by'
                        );
                }


                if (
                    Schema::hasColumn(
                        'simaksis',
                        'approved_at'
                    )
                ) {
                    $table
                        ->dropColumn(
                            'approved_at'
                        );
                }


                if (
                    Schema::hasColumn(
                        'simaksis',
                        'rejected_by'
                    )
                ) {
                    $table
                        ->dropConstrainedForeignId(
                            'rejected_by'
                        );
                }


                if (
                    Schema::hasColumn(
                        'simaksis',
                        'rejected_at'
                    )
                ) {
                    $table
                        ->dropColumn(
                            'rejected_at'
                        );
                }


                if (
                    Schema::hasColumn(
                        'simaksis',
                        'rejection_reason'
                    )
                ) {
                    $table
                        ->dropColumn(
                            'rejection_reason'
                        );
                }
            }
        );
    }
};