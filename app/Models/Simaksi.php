<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Simaksi extends Model
{
    protected $fillable = [
        'user_id',
        'gunung',
        'tanggal_naik',
        'tanggal_turun',
        'jumlah_anggota',
        'nomor_darurat',
        'status',
        'catatan_admin',
        'approved_at',
        'approved_by',
    ];


    protected function casts(): array
    {
        return [
            'tanggal_naik' => 'date',
            'tanggal_turun' => 'date',
            'approved_at' => 'datetime',
        ];
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }


    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {

            'approved' =>
                'Disetujui',

            'rejected' =>
                'Ditolak',

            default =>
                'Pending',

        };
    }
}