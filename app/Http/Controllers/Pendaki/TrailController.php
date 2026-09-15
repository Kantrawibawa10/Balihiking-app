<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\HikingTrail;
use App\Services\TrailMapService;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TrailController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DETAIL JALUR
    |--------------------------------------------------------------------------
    */

    public function show(
        HikingTrail $trail,
        TrailMapService $trailMapService
    ): View {
        abort_unless(
            $trail->is_active,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | RELATION
        |--------------------------------------------------------------------------
        */

        $trail->load([
            'mountain',

            'checkpoints' => function ($query) {
                $query
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

            'guides' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },

            'trailReports' => function ($query) {
                $query
                    ->with([
                        'user:id,name',
                    ])
                    ->latest('report_date')
                    ->limit(10);
            },

            
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROUTE COORDINATES
        |--------------------------------------------------------------------------
        */

        $routeCoordinates =
            $trailMapService
                ->resolveRouteCoordinates(
                    $trail
                );

        /*
        |--------------------------------------------------------------------------
        | CHECKPOINT
        |--------------------------------------------------------------------------
        */

        $checkpoints = $trail
            ->checkpoints
            ->filter(function ($checkpoint) {
                return
                    is_numeric(
                        $checkpoint->latitude
                    )
                    &&
                    is_numeric(
                        $checkpoint->longitude
                    );
            })
            ->values()
            ->map(function ($checkpoint, $index) {
                return [
                    'id' => $checkpoint->id,

                    'name' => $checkpoint->name
                        ?: 'Pos '.($index + 1),

                    'latitude' => (float) $checkpoint->latitude,

                    'longitude' => (float) $checkpoint->longitude,

                    'elevation_m' => $checkpoint->elevation_m !== null
                            ? (int) $checkpoint->elevation_m
                            : null,

                    'type' => $checkpoint->type,
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | START / FINISH
        |--------------------------------------------------------------------------
        */

        $startPoint = null;
        $finishPoint = null;

        if (
            count($routeCoordinates)
            >= 2
        ) {
            $startPoint =
                $routeCoordinates[0];

            $finishPoint =
                $routeCoordinates[
                    count($routeCoordinates) - 1
                ];
        }

        $reports =
            $trail->trailReports;

        return view(
            'pendaki.trails.show',
            compact(
                'trail',
                'routeCoordinates',
                'checkpoints',
                'startPoint',
                'finishPoint',
                'reports'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD OFFLINE MAP
    |--------------------------------------------------------------------------
    */

    public function downloadOffline(
        HikingTrail $trail,
        TrailMapService $trailMapService
    ): Response {
        abort_unless(
            $trail->is_active,
            404
        );

        $trail->load([
            'mountain',

            'checkpoints' => function ($query) {
                $query
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
        ]);

        $routeCoordinates =
            $trailMapService
                ->resolveRouteCoordinates(
                    $trail
                );

        $checkpoints = $trail
            ->checkpoints
            ->filter(function ($checkpoint) {
                return
                    is_numeric(
                        $checkpoint->latitude
                    )
                    &&
                    is_numeric(
                        $checkpoint->longitude
                    );
            })
            ->values()
            ->map(function ($checkpoint, $index) {
                return [
                    'name' => $checkpoint->name
                        ?: 'Pos '.($index + 1),

                    'latitude' => (float) $checkpoint->latitude,

                    'longitude' => (float) $checkpoint->longitude,

                    'elevation_m' => $checkpoint->elevation_m !== null
                            ? (int) $checkpoint->elevation_m
                            : null,

                    'type' => $checkpoint->type,
                ];
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD NAME
        |--------------------------------------------------------------------------
        */

        $filename =
            'peta-offline-'
            .
            Str::slug(
                $trail->mountain?->name
                .'-'
                .$trail->name
            )
            .
            '.html';

        return response()
            ->view(
                'pendaki.trails.offline',
                compact(
                    'trail',
                    'routeCoordinates',
                    'checkpoints'
                )
            )
            ->header(
                'Content-Type',
                'text/html; charset=UTF-8'
            )
            ->header(
                'Content-Disposition',
                'attachment; filename="'.$filename.'"'
            );
    }
}
