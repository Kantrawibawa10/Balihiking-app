<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\HikingTrail;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TrailController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        HikingTrail $trail
    ): View {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI JALUR
        |--------------------------------------------------------------------------
        */

        $this->ensureTrailIsActive(
            $trail
        );

        /*
        |--------------------------------------------------------------------------
        | LOAD RELATIONS
        |--------------------------------------------------------------------------
        */

        $this->loadTrailRelations(
            $trail
        );

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $mountain =
            $trail->mountain;

        $checkpoints =
            $trail->checkpoints;

        $guides =
            $trail->guides;

        /*
        |--------------------------------------------------------------------------
        | ROUTE
        |--------------------------------------------------------------------------
        */

        $mapPoints =
            $this->resolveRoutePoints(
                $trail
            );

        $routeCoordinates =
            collect(
                $mapPoints
            )
                ->map(
                    fn (
                        array $point
                    ): array => [
                        $point[
                            'lat'
                        ],

                        $point[
                            'lng'
                        ],
                    ]
                )
                ->values()
                ->all();

        /*
        |--------------------------------------------------------------------------
        | START / FINISH
        |--------------------------------------------------------------------------
        */

        $startPoint =
            $routeCoordinates[
                0
            ]
            ??
            null;

        $finishPoint =
            ! empty(
                $routeCoordinates
            )
                ? $routeCoordinates[
                    count(
                        $routeCoordinates
                    )
                    -
                    1
                ]
                : null;

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'pendaki.trails.show',
            compact(
                'trail',
                'mountain',
                'checkpoints',
                'guides',
                'routeCoordinates',
                'startPoint',
                'finishPoint'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD OFFLINE
    |--------------------------------------------------------------------------
    |
    | GET /pendaki/jalur/{trail}/offline
    |
    | Halaman ini memberikan dua pilihan:
    |
    | 1. PNG Image Map
    | 2. GPX Route
    |
    | Proses pembuatan file dilakukan di browser agar ringan.
    |--------------------------------------------------------------------------
    */

    public function downloadOffline(
        HikingTrail $trail
    ): View {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI JALUR
        |--------------------------------------------------------------------------
        */

        $this->ensureTrailIsActive(
            $trail
        );

        /*
        |--------------------------------------------------------------------------
        | LOAD RELATIONS
        |--------------------------------------------------------------------------
        */

        $this->loadTrailRelations(
            $trail
        );

        /*
        |--------------------------------------------------------------------------
        | ROUTE POINTS
        |--------------------------------------------------------------------------
        */

        $mapPoints =
            $this->resolveRoutePoints(
                $trail
            );

        /*
        |--------------------------------------------------------------------------
        | CHECKPOINT EXPORT
        |--------------------------------------------------------------------------
        */

        $checkpointExport =
            $trail
                ->checkpoints
                ->filter(
                    fn (
                        $checkpoint
                    ): bool =>
                        is_numeric(
                            $checkpoint->latitude
                        )
                        &&
                        is_numeric(
                            $checkpoint->longitude
                        )
                )
                ->values()
                ->map(
                    function (
                        $checkpoint,
                        int $index
                    ): array {
                        return [
                            'id' =>
                                $checkpoint->id,

                            'name' =>
                                $checkpoint->name
                                ?:
                                'Checkpoint '
                                .
                                (
                                    $index + 1
                                ),

                            'lat' =>
                                (float)
                                $checkpoint->latitude,

                            'lng' =>
                                (float)
                                $checkpoint->longitude,

                            'ele' =>
                                is_numeric(
                                    $checkpoint->elevation_m
                                )
                                    ? (float)
                                        $checkpoint
                                            ->elevation_m
                                    : null,

                            'type' =>
                                $checkpoint->type
                                ??
                                null,
                        ];
                    }
                )
                ->all();

        /*
        |--------------------------------------------------------------------------
        | FILE NAME
        |--------------------------------------------------------------------------
        */

        $baseName =
            Str::slug(
                $trail->name
                ?:
                (
                    $trail
                        ->mountain
                        ?->name
                    ?:
                    'jalur-pendakian'
                )
            );

        $downloadBaseName =
            'balihiking-'
            .
            $baseName;

        /*
        |--------------------------------------------------------------------------
        | TRAIL EXPORT INFO
        |--------------------------------------------------------------------------
        */

        $trailExport = [
            'id' =>
                $trail->id,

            'name' =>
                $trail->name
                ?:
                'Jalur Pendakian',

            'mountain' =>
                $trail
                    ->mountain
                    ?->name
                ?:
                'BaliHiking',

            'download_base_name' =>
                $downloadBaseName,
        ];

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'pendaki.trails.offline',
            [
                'trail' =>
                    $trail,

                'trailExport' =>
                    $trailExport,

                'mapPoints' =>
                    $mapPoints,

                'checkpointExport' =>
                    $checkpointExport,

                'downloadBaseName' =>
                    $downloadBaseName,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE ACTIVE TRAIL
    |--------------------------------------------------------------------------
    */

    private function ensureTrailIsActive(
        HikingTrail $trail
    ): void {
        if (! $trail->is_active) {
            abort(
                404,
                'Jalur pendakian tidak tersedia.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD RELATIONS
    |--------------------------------------------------------------------------
    */

    private function loadTrailRelations(
        HikingTrail $trail
    ): void {
        $trail->load([
            /*
            |--------------------------------------------------------------------------
            | MOUNTAIN
            |--------------------------------------------------------------------------
            */

            'mountain',

            /*
            |--------------------------------------------------------------------------
            | CHECKPOINTS
            |--------------------------------------------------------------------------
            */

            'checkpoints' =>
                function (
                    $query
                ): void {
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
                        ->orderBy(
                            'id'
                        );
                },

            /*
            |--------------------------------------------------------------------------
            | GUIDES
            |--------------------------------------------------------------------------
            */

            'guides' =>
                function (
                    $query
                ): void {
                    $query
                        ->where(
                            'is_active',
                            true
                        )
                        ->orderBy(
                            'sort_order'
                        )
                        ->orderBy(
                            'id'
                        );
                },
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE ROUTE POINTS
    |--------------------------------------------------------------------------
    |
    | Prioritas:
    |
    | 1. map_geojson
    | 2. route_geojson
    | 3. geojson
    | 4. checkpoints
    |--------------------------------------------------------------------------
    */

    private function resolveRoutePoints(
        HikingTrail $trail
    ): array {
        $geoJsonCandidates = [
            $trail->getAttribute(
                'map_geojson'
            ),

            $trail->getAttribute(
                'route_geojson'
            ),

            $trail->getAttribute(
                'geojson'
            ),
        ];

        foreach (
            $geoJsonCandidates
            as $geoJson
        ) {
            if (empty($geoJson)) {
                continue;
            }

            $normalized =
                $this->normalizeGeoJson(
                    $geoJson
                );

            if (! $normalized) {
                continue;
            }

            $points =
                $this->extractGeoJsonPoints(
                    $normalized
                );

            if (
                count(
                    $points
                )
                >=
                2
            ) {
                return $points;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK CHECKPOINT
        |--------------------------------------------------------------------------
        */

        return $trail
            ->checkpoints
            ->filter(
                fn (
                    $checkpoint
                ): bool =>
                    is_numeric(
                        $checkpoint->latitude
                    )
                    &&
                    is_numeric(
                        $checkpoint->longitude
                    )
            )
            ->values()
            ->map(
                function (
                    $checkpoint
                ): array {
                    return [
                        'lat' =>
                            (float)
                            $checkpoint
                                ->latitude,

                        'lng' =>
                            (float)
                            $checkpoint
                                ->longitude,

                        'ele' =>
                            is_numeric(
                                $checkpoint
                                    ->elevation_m
                            )
                                ? (float)
                                    $checkpoint
                                        ->elevation_m
                                : null,
                    ];
                }
            )
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE GEOJSON
    |--------------------------------------------------------------------------
    */

    private function normalizeGeoJson(
        mixed $geoJson
    ): ?array {
        if (
            is_array(
                $geoJson
            )
        ) {
            return $geoJson;
        }

        if (
            is_object(
                $geoJson
            )
        ) {
            return json_decode(
                json_encode(
                    $geoJson
                ),
                true
            );
        }

        if (
            ! is_string(
                $geoJson
            )
        ) {
            return null;
        }

        $decoded =
            json_decode(
                $geoJson,
                true
            );

        if (
            json_last_error()
            !==
            JSON_ERROR_NONE
        ) {
            return null;
        }

        return is_array(
            $decoded
        )
            ? $decoded
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | EXTRACT GEOJSON
    |--------------------------------------------------------------------------
    */

    private function extractGeoJsonPoints(
        array $geoJson
    ): array {
        $type =
            $geoJson[
                'type'
            ]
            ??
            null;

        /*
        |--------------------------------------------------------------------------
        | FEATURE COLLECTION
        |--------------------------------------------------------------------------
        */

        if (
            $type
            ===
            'FeatureCollection'
        ) {
            $points = [];

            foreach (
                $geoJson[
                    'features'
                ]
                ??
                []
                as $feature
            ) {
                if (
                    ! is_array(
                        $feature
                    )
                ) {
                    continue;
                }

                $featurePoints =
                    $this
                        ->extractGeoJsonPoints(
                            $feature
                        );

                foreach (
                    $featurePoints
                    as $point
                ) {
                    $points[] =
                        $point;
                }
            }

            return $this
                ->removeDuplicatePoints(
                    $points
                );
        }

        /*
        |--------------------------------------------------------------------------
        | FEATURE
        |--------------------------------------------------------------------------
        */

        if (
            $type
            ===
            'Feature'
        ) {
            $geometry =
                $geoJson[
                    'geometry'
                ]
                ??
                null;

            if (
                ! is_array(
                    $geometry
                )
            ) {
                return [];
            }

            return $this
                ->extractGeoJsonPoints(
                    $geometry
                );
        }

        /*
        |--------------------------------------------------------------------------
        | LINE STRING
        |--------------------------------------------------------------------------
        */

        if (
            $type
            ===
            'LineString'
        ) {
            return $this
                ->coordinatesToPoints(
                    $geoJson[
                        'coordinates'
                    ]
                    ??
                    []
                );
        }

        /*
        |--------------------------------------------------------------------------
        | MULTI LINE STRING
        |--------------------------------------------------------------------------
        */

        if (
            $type
            ===
            'MultiLineString'
        ) {
            $points = [];

            foreach (
                $geoJson[
                    'coordinates'
                ]
                ??
                []
                as $line
            ) {
                if (
                    ! is_array(
                        $line
                    )
                ) {
                    continue;
                }

                foreach (
                    $this
                        ->coordinatesToPoints(
                            $line
                        )
                    as $point
                ) {
                    $points[] =
                        $point;
                }
            }

            return $this
                ->removeDuplicatePoints(
                    $points
                );
        }

        /*
        |--------------------------------------------------------------------------
        | RAW COORDINATES FALLBACK
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $geoJson[
                    'coordinates'
                ]
            )
            &&
            is_array(
                $geoJson[
                    'coordinates'
                ]
            )
        ) {
            return $this
                ->coordinatesToPoints(
                    $geoJson[
                        'coordinates'
                    ]
                );
        }

        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | COORDINATES TO POINTS
    |--------------------------------------------------------------------------
    |
    | GeoJSON:
    |
    | [longitude, latitude, elevation?]
    |--------------------------------------------------------------------------
    */

    private function coordinatesToPoints(
        array $coordinates
    ): array {
        $points =
            [];

        foreach (
            $coordinates
            as $coordinate
        ) {
            if (
                ! is_array(
                    $coordinate
                )
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | NESTED
            |--------------------------------------------------------------------------
            */

            if (
                isset(
                    $coordinate[
                        0
                    ]
                )
                &&
                is_array(
                    $coordinate[
                        0
                    ]
                )
            ) {
                foreach (
                    $this
                        ->coordinatesToPoints(
                            $coordinate
                        )
                    as $nestedPoint
                ) {
                    $points[] =
                        $nestedPoint;
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | VALID COORDINATE
            |--------------------------------------------------------------------------
            */

            if (
                count(
                    $coordinate
                )
                <
                2
            ) {
                continue;
            }

            if (
                ! is_numeric(
                    $coordinate[
                        0
                    ]
                )
                ||
                ! is_numeric(
                    $coordinate[
                        1
                    ]
                )
            ) {
                continue;
            }

            $points[] = [
                'lat' =>
                    (float)
                    $coordinate[
                        1
                    ],

                'lng' =>
                    (float)
                    $coordinate[
                        0
                    ],

                'ele' =>
                    isset(
                        $coordinate[
                            2
                        ]
                    )
                    &&
                    is_numeric(
                        $coordinate[
                            2
                        ]
                    )
                        ? (float)
                            $coordinate[
                                2
                            ]
                        : null,
            ];
        }

        return $this
            ->removeDuplicatePoints(
                $points
            );
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE DUPLICATE ROUTE POINT
    |--------------------------------------------------------------------------
    */

    private function removeDuplicatePoints(
        array $points
    ): array {
        $result =
            [];

        $lastKey =
            null;

        foreach (
            $points
            as $point
        ) {
            if (
                ! isset(
                    $point[
                        'lat'
                    ],
                    $point[
                        'lng'
                    ]
                )
            ) {
                continue;
            }

            $key =
                number_format(
                    (float)
                    $point[
                        'lat'
                    ],
                    7,
                    '.',
                    ''
                )
                .
                ':'
                .
                number_format(
                    (float)
                    $point[
                        'lng'
                    ],
                    7,
                    '.',
                    ''
                );

            if (
                $key
                ===
                $lastKey
            ) {
                continue;
            }

            $result[] =
                $point;

            $lastKey =
                $key;
        }

        return array_values(
            $result
        );
    }
}