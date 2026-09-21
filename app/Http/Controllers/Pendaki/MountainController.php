<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\Mountain;
use App\Services\MountainWeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Throwable;

class MountainController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WEATHER SERVICE
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected MountainWeatherService $weatherService
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW MOUNTAIN
    |--------------------------------------------------------------------------
    */

    public function show(
        Mountain $mountain
    ): View {
        /*
        |--------------------------------------------------------------------------
        | LOAD ACTIVE TRAILS
        |--------------------------------------------------------------------------
        */

        $mountain->load([
            'hikingTrails' => function (
                $query
            ) {
                $query
                    ->where(
                        'is_active',
                        true
                    )

                    ->withCount(
                        'checkpoints'
                    )

                    ->with([
                        'checkpoints' => function (
                            $checkpointQuery
                        ) {
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
        | Jangan paksa weather request pada render awal.
        |--------------------------------------------------------------------------
        |
        | Frontend akan mengambilnya melalui endpoint AJAX:
        |
        | /pendaki/gunung/{mountain}/weather
        |
        | Jadi detail gunung tetap cepat dibuka.
        |--------------------------------------------------------------------------
        */

        return view(
            'pendaki.mountains.show',
            compact(
                'mountain'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | WEATHER
    |--------------------------------------------------------------------------
    */

    public function weather(
        Mountain $mountain
    ): JsonResponse {
        try {
            /*
            |--------------------------------------------------------------------------
            | Tombol "Perbarui"
            |--------------------------------------------------------------------------
            |
            | ?refresh=1 akan memaksa request baru.
            |--------------------------------------------------------------------------
            */

            $forceRefresh =
                request()->boolean(
                    'refresh'
                );


            $weather =
                $this
                    ->weatherService
                    ->getWeather(
                        $mountain,
                        $forceRefresh
                    );


            /*
            |--------------------------------------------------------------------------
            | WEATHER UNAVAILABLE
            |--------------------------------------------------------------------------
            */

            if (! $weather) {
                return response()->json(
                    [
                        'success' =>
                            false,

                        'message' =>
                            'Perkiraan cuaca belum tersedia untuk gunung ini.',

                        'debug_hint' =>
                            app()->isLocal()
                                ?
                                'Periksa storage/logs/laravel.log untuk detail kegagalan geocoding/weather API.'
                                :
                                null,
                    ],
                    503
                );
            }


            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            return response()->json(
                $weather
            );

        } catch (Throwable $exception) {
            report(
                $exception
            );


            return response()->json(
                [
                    'success' =>
                        false,

                    'message' =>
                        'Terjadi kendala saat mengambil prakiraan cuaca.',

                    'debug' =>
                        app()->isLocal()
                            ?
                            [
                                'exception' =>
                                    get_class(
                                        $exception
                                    ),

                                'message' =>
                                    $exception->getMessage(),
                            ]
                            :
                            null,
                ],
                503
            );
        }
    }
}