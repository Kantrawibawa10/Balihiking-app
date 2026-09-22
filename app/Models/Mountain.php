<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Mountain extends Model
{
    protected $table = 'mountains';

    protected $fillable = [
        'name',
        'location',
        'elevation_m',
        'description',
        'cover_image',
    ];

    protected $casts = [
        'elevation_m' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function hikingTrails(): HasMany
    {
        return $this->hasMany(
            HikingTrail::class,
            'mountain_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE TRAILS
    |--------------------------------------------------------------------------
    */

    public function activeHikingTrails(): HasMany
    {
        return $this->hikingTrails()
            ->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | COVER IMAGE URL
    |--------------------------------------------------------------------------
    */

    public function getCoverImageUrlAttribute(): ?string
    {
        if (empty($this->cover_image)) {
            return null;
        }

        if (
            Str::startsWith(
                $this->cover_image,
                [
                    'http://',
                    'https://',
                ]
            )
        ) {
            return $this->cover_image;
        }

        return Storage::url(
            $this->cover_image
        );
    }

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'mountain_user'
        )->withTimestamps();
    }

    public function simaksis(): HasMany
    {
        return $this->hasMany(
            Simaksi::class
        );
    }
}
