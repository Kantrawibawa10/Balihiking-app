<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLocation extends Model
{
    protected $table = 'user_locations';

    protected $fillable = [
        'user_id',
        'hiking_trail_id',
        'latitude',
        'longitude',
        'altitude_m',
        'battery_level',
        'status',
        'recorded_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'altitude_m' => 'float',
        'battery_level' => 'integer',
        'recorded_at' => 'datetime',
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
}