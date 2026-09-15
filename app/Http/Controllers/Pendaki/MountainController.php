<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\Mountain;

class MountainController extends Controller
{
    /**
     * Detail gunung dan daftar jalur.
     */
    public function show(Mountain $mountain)
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD ACTIVE TRAILS
        |--------------------------------------------------------------------------
        */

        $mountain->load([
            'hikingTrails' => function ($query) {

                $query
                    ->where(
                        'is_active',
                        true
                    )

                    ->withCount(
                        'checkpoints'
                    )

                    ->with([
                        'checkpoints' => function ($checkpointQuery) {

                            $checkpointQuery
                                ->select([
                                    'id',
                                    'hiking_trail_id',
                                    'name',
                                    'latitude',
                                    'longitude',
                                    'elevation_m',
                                    'type',
                                ])
                                ->orderBy('id');

                        },
                    ])

                    ->orderBy('name');

            },
        ]);

        return view(
            'pendaki.mountains.show',
            compact(
                'mountain'
            )
        );
    }
}