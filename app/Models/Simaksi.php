<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Simaksi extends Model
{
    use HasFactory;
    use SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table =
        'simaksis';


    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'code',

        'user_id',

        'mountain_id',

        'gunung',

        'tanggal_naik',

        'tanggal_turun',

        'jumlah_anggota',

        'nomor_darurat',

        'status',

        'approved_by',

        'approved_at',

        'rejected_by',

        'rejected_at',

        'rejection_reason',

        'completed_by',

        'completed_at',

        'admin_notes',

    ];


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

            'rejected_at' =>
                'datetime',

            'completed_at' =>
                'datetime',

            'deleted_at' =>
                'datetime',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | USER / PENDAKI
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


    /*
    |--------------------------------------------------------------------------
    | REJECTED BY
    |--------------------------------------------------------------------------
    */

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'rejected_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETED BY
    |--------------------------------------------------------------------------
    */

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'completed_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MOUNTAIN NAME
    |--------------------------------------------------------------------------
    */

    public function getMountainNameAttribute(): string
    {
        return
            $this->mountain?->name
            ??
            $this->gunung
            ??
            '-';
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS LABEL
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return match (
            $this->status
        ) {

            'pending' =>
                'Menunggu',

            'approved' =>
                'Disetujui',

            'rejected' =>
                'Ditolak',

            'completed' =>
                'Selesai',

            'cancelled' =>
                'Dibatalkan',

            default =>
                ucfirst(
                    (string)
                    $this->status
                ),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status
            ===
            'pending';
    }


    public function isApproved(): bool
    {
        return $this->status
            ===
            'approved';
    }


    public function isRejected(): bool
    {
        return $this->status
            ===
            'rejected';
    }


    public function isCompleted(): bool
    {
        return $this->status
            ===
            'completed';
    }


    public function isCancelled(): bool
    {
        return $this->status
            ===
            'cancelled';
    }
}