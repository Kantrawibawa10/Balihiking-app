<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRoute extends Model
{
    protected $table = 'user_routes';

    protected $fillable = [
        'user_id',
        'hiking_trail_id',
        'gpx_file_path',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function hikingTrail(): BelongsTo
    {
        return $this->belongsTo(
            HikingTrail::class,
            'hiking_trail_id'
        );
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->completed_at
            ? 'Selesai'
            : 'Berlangsung';
    }
}