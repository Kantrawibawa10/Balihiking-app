<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HikingTrail extends Model
{
    use HasFactory;


    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'is_active' =>
                'boolean',

            'distance_km' =>
                'decimal:2',

            'estimated_time_hours' =>
                'decimal:2',

            'max_elevation' =>
                'integer',

            'min_elevation' =>
                'integer',

            'map_geojson' =>
                'array',

            'coordinates' =>
                'array',
        ];
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
    | CHECKPOINTS
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
    | TRAIL GUIDES
    |--------------------------------------------------------------------------
    |
    | Relasi utama.
    |
    */

    public function trailGuides(): HasMany
    {
        return $this->hasMany(
            TrailGuide::class,
            'hiking_trail_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GUIDES ALIAS
    |--------------------------------------------------------------------------
    |
    | Ini sengaja dipertahankan supaya kode seperti:
    |
    | $trail->load('guides')
    | $trail->guides
    |
    | tetap dapat berjalan.
    |
    */

    public function guides(): HasMany
    {
        return $this->hasMany(
            TrailGuide::class,
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


    /*
    |--------------------------------------------------------------------------
    | ACTIVE GUIDES
    |--------------------------------------------------------------------------
    |
    | Bisa digunakan bila nanti ingin mengambil panduan aktif saja.
    |
    */

    public function activeGuides(): HasMany
    {
        return $this
            ->hasMany(
                TrailGuide::class,
                'hiking_trail_id'
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy(
                'sort_order'
            );
    }
}