<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrailGuide extends Model
{
    use HasFactory;


    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'hiking_trail_id',
        'type',
        'title',
        'content',
        'sort_order',
        'is_active',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'sort_order' =>
                'integer',

            'is_active' =>
                'boolean',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | HIKING TRAIL
    |--------------------------------------------------------------------------
    */

    public function hikingTrail(): BelongsTo
    {
        return $this->belongsTo(
            HikingTrail::class,
            'hiking_trail_id'
        );
    }
}