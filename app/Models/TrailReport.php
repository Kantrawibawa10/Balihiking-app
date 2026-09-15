<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrailReport extends Model
{
    protected $table =
        'trail_reports';

    protected $fillable = [
        'hiking_trail_id',
        'user_id',
        'status',
        'condition_note',
        'report_date',
    ];

    protected $casts = [
        'report_date' =>
            'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
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