<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Simaksi extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table =
        'simaksis';

    /*
    |--------------------------------------------------------------------------
    | GUARDED
    |--------------------------------------------------------------------------
    |
    | Controller sekarang tidak memakai mass assignment lagi.
    | Ini tetap dibuat aman agar bagian admin lain tidak mudah error.
    |
    */

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'tanggal_naik' =>
                'date',

            'tanggal_turun' =>
                'date',

            'jumlah_anggota' =>
                'integer',

            'approved_at' =>
                'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MOUNTAIN
    |--------------------------------------------------------------------------
    */

    public function mountain(): BelongsTo
    {
        return $this->belongsTo(
            Mountain::class,
            'mountain_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVED BY
    |--------------------------------------------------------------------------
    */

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}