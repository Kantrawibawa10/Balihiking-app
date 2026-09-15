<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkpoint extends Model
{
    protected $table = 'checkpoints';

    protected $fillable = [
        'hiking_trail_id',
        'name',
        'latitude',
        'longitude',
        'elevation_m',
        'type',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'elevation_m' => 'integer',
    ];

    public function hikingTrail(): BelongsTo
    {
        return $this->belongsTo(
            HikingTrail::class,
            'hiking_trail_id'
        );
    }
}