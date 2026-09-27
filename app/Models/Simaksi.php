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

    protected $table = 'simaksis';

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

        /*
        |--------------------------------------------------------------------------
        | APPROVAL
        |--------------------------------------------------------------------------
        */

        'approved_by',

        'approved_at',

        /*
        |--------------------------------------------------------------------------
        | REJECTION
        |--------------------------------------------------------------------------
        */

        'rejected_by',

        'rejected_at',

        'rejection_reason',

        /*
        |--------------------------------------------------------------------------
        | COMPLETED
        |--------------------------------------------------------------------------
        */

        'completed_by',

        'completed_at',
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
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS LABEL
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' =>
                'Pending',

            'approved' =>
                'Disetujui',

            'rejected' =>
                'Ditolak',

            'completed' =>
                'Selesai',

            default =>
                ucfirst(
                    (string) $this->status
                ),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | MOUNTAIN NAME
    |--------------------------------------------------------------------------
    */

    public function getMountainNameAttribute(): string
    {
        return $this
            ->mountain
            ?->name
            ??
            $this->gunung
            ??
            '-';
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVER NAME
    |--------------------------------------------------------------------------
    */

    public function getApprovedByNameAttribute(): string
    {
        return $this
            ->approvedBy
            ?->name
            ??
            '-';
    }

    /*
    |--------------------------------------------------------------------------
    | REJECTER NAME
    |--------------------------------------------------------------------------
    */

    public function getRejectedByNameAttribute(): string
    {
        return $this
            ->rejectedBy
            ?->name
            ??
            '-';
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETER NAME
    |--------------------------------------------------------------------------
    */

    public function getCompletedByNameAttribute(): string
    {
        return $this
            ->completedBy
            ?->name
            ??
            '-';
    }
}