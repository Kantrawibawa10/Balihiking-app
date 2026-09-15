<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HikingTrail extends Model
{
    protected $table = 'hiking_trails';

    protected $fillable = [
        'mountain_id',
        'name',
        'is_active',
        'gpx_file_path',
        'difficulty',
        'distance_km',
        'max_elevation',
        'min_elevation',
        'estimated_time_hours',
        'map_geojson',
        'coordinates',
        'status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'distance_km' => 'float',
        'max_elevation' => 'integer',
        'min_elevation' => 'integer',
        'estimated_time_hours' => 'float',
    ];

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
    | CHECKPOINT
    |--------------------------------------------------------------------------
    */

    public function checkpoints(): HasMany
    {
        return $this->hasMany(
            Checkpoint::class,
            'hiking_trail_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USER ROUTES
    |--------------------------------------------------------------------------
    */

    public function userRoutes(): HasMany
    {
        return $this->hasMany(
            UserRoute::class,
            'hiking_trail_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USER LOCATIONS
    |--------------------------------------------------------------------------
    */

    public function userLocations(): HasMany
    {
        return $this->hasMany(
            UserLocation::class,
            'hiking_trail_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TRAIL REPORTS
    |--------------------------------------------------------------------------
    */

    public function trailReports(): HasMany
    {
        return $this->hasMany(
            TrailReport::class,
            'hiking_trail_id'
        );
    }

    public function guides(): HasMany
    {
        return $this->hasMany(
            TrailGuide::class,
            'hiking_trail_id'
        )
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
