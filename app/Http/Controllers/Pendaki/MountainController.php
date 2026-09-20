<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\Mountain;
use App\Services\MountainWeatherService;
use Illuminate\Http\JsonResponse;

class MountainController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        private readonly MountainWeatherService $weatherService
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL GUNUNG DAN DAFTAR JALUR
    |--------------------------------------------------------------------------
    */

    public function show(
        Mountain $mountain
    ) {

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
                                ->orderBy(
                                    'id'
                                );
                        },
                    ])

                    ->orderBy(
                        'name'
                    );
            },
        ]);


        /*
        |--------------------------------------------------------------------------
        | WEATHER
        |--------------------------------------------------------------------------
        */

        $weather =
            $this->weatherService
                ->getForecast(
                    $mountain
                );


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'pendaki.mountains.show',
            compact(
                'mountain',
                'weather'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | WEATHER API INTERNAL
    |--------------------------------------------------------------------------
    |
    | Endpoint:
    |
    | /pendaki/gunung/{mountain}/weather
    |
    |--------------------------------------------------------------------------
    */

    public function weather(
        Mountain $mountain
    ): JsonResponse {

        $weather =
            $this->weatherService
                ->getForecast(
                    $mountain
                );


        if (!$weather) {

            return response()->json(
                [
                    'success' =>
                        false,

                    'message' =>
                        'Perkiraan cuaca belum tersedia untuk gunung ini.',
                ],
                503
            );
        }


        return response()->json([
            'success' =>
                true,

            'weather' =>
                $weather,
        ]);
    }
}