<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\Mountain;
use App\Services\MountainWeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class MountainController extends Controller
{
    public function __construct(
        protected MountainWeatherService $weatherService
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL GUNUNG
    |--------------------------------------------------------------------------
    */

    public function show(
        Mountain $mountain
    ): View {
        $mountain->load([

            'hikingTrails' =>
                function (
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
                            'checkpoints' =>
                                function (
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


        return view(
            'pendaki.mountains.show',
            compact(
                'mountain'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | WEATHER JSON
    |--------------------------------------------------------------------------
    */

    public function weather(
        Request $request,
        Mountain $mountain
    ): JsonResponse {
        try {
            $weather =
                $this
                    ->weatherService
                    ->getWeather(
                        $mountain,
                        $request->boolean(
                            'refresh'
                        )
                    );


            if (! $weather) {
                return response()->json(
                    [
                        'success' =>
                            false,

                        'message' =>
                            'Perkiraan cuaca belum tersedia untuk gunung ini.',

                        'mountain' =>
                            $mountain->name,

                        'hint' =>
                            app()->isLocal()
                                ?
                                'Periksa storage/logs/laravel.log.'
                                :
                                null,
                    ],
                    503
                );
            }


            return response()->json(
                $weather
            );

        } catch (Throwable $e) {
            report(
                $e
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
                                'class' =>
                                    get_class($e),

                                'message' =>
                                    $e->getMessage(),
                            ]
                            :
                            null,
                ],
                503
            );
        }
    }
}