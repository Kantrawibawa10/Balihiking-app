<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentityDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'status',
        'verification_note',
        'verified_at',
        'verified_by',
    ];


    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Verifier
    |--------------------------------------------------------------------------
    */

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getTypeLabelAttribute(): string
    {
        return match ($this->document_type) {
            'ktp' => 'KTP',
            'sim' => 'SIM',
            default => strtoupper(
                $this->document_type
            ),
        };
    }


    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }


    public function getIsImageAttribute(): bool
    {
        return str_starts_with(
            (string) $this->mime_type,
            'image/'
        );
    }
}