<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrailGuide extends Model
{
    protected $fillable = [
        'hiking_trail_id',
        'type',
        'title',
        'content',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function hikingTrail(): BelongsTo
    {
        return $this->belongsTo(
            HikingTrail::class,
            'hiking_trail_id'
        );
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'guide' =>
                'Panduan Perjalanan',

            'safety' =>
                'Informasi Keamanan',

            'warning' =>
                'Peringatan',

            'equipment' =>
                'Perlengkapan',

            'emergency' =>
                'Informasi Darurat',

            default =>
                ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        (string) $this->type
                    )
                ),
        };
    }
}