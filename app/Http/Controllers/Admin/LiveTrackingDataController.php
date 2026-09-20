<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserLocation;
use App\Models\UserRoute;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiveTrackingDataController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Mengambil seluruh aktivitas pendakian:
    |
    | - sedang berlangsung
    | - sudah selesai
    | - history lokasi
    | - titik mulai
    | - titik terakhir
    | - titik selesai
    | - jalur resmi
    | - SOS
    |
    */

    public function index(
        Request $request
    ): JsonResponse {
        $status =
            $request->string(
                'status'
            )->toString();

        $status =
            in_array(
                $status,
                [
                    'all',
                    'active',
                    'completed',
                ],
                true
            )
                ?
                $status
                :
                'all';


        /*
        |--------------------------------------------------------------------------
        | ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $query =
            UserRoute::query()
                ->with([
                    'user:id,name,email',

                    'hikingTrail' => function (
                        $query
                    ) {
                        $query->select([
                            'id',
                            'mountain_id',
                            'name',
                            'map_geojson',
                            'distance_km',
                            'estimated_time_hours',
                        ]);
                    },

                    'hikingTrail.mountain:id,name',
                ])
                ->latest(
                    'created_at'
                );


        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $status ===
            'active'
        ) {
            $query->whereNull(
                'completed_at'
            );
        }


        if (
            $status ===
            'completed'
        ) {
            $query->whereNotNull(
                'completed_at'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'search'
            )
        ) {
            $search =
                trim(
                    $request->string(
                        'search'
                    )->toString()
                );


            $query->where(
                function (
                    $query
                ) use (
                    $search
                ) {

                    $query
                        ->whereHas(
                            'user',
                            function (
                                $userQuery
                            ) use (
                                $search
                            ) {
                                $userQuery->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        )

                        ->orWhereHas(
                            'hikingTrail',
                            function (
                                $trailQuery
                            ) use (
                                $search
                            ) {
                                $trailQuery->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        )

                        ->orWhereHas(
                            'hikingTrail.mountain',
                            function (
                                $mountainQuery
                            ) use (
                                $search
                            ) {
                                $mountainQuery->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LIMIT
        |--------------------------------------------------------------------------
        |
        | Supaya halaman monitoring tidak menarik ribuan aktivitas sekaligus.
        |
        */

        $activities =
            $query
                ->limit(
                    100
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | BUILD RESPONSE
        |--------------------------------------------------------------------------
        */

        $result =
            $activities
                ->map(
                    function (
                        UserRoute $activity
                    ) {

                        return $this
                            ->buildActivityData(
                                $activity
                            );
                    }
                )
                ->values();


        /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */

        $activeCount =
            $activities
                ->whereNull(
                    'completed_at'
                )
                ->count();


        $completedCount =
            $activities
                ->whereNotNull(
                    'completed_at'
                )
                ->count();


        $sosCount =
            $result
                ->filter(
                    function (
                        array $activity
                    ) {
                        return
                            $activity[
                                'has_sos'
                            ];
                    }
                )
                ->count();


        return response()->json([
            'success' =>
                true,

            'generated_at' =>
                now()
                    ->toIso8601String(),

            'stats' => [
                'total' =>
                    $activities
                        ->count(),

                'active' =>
                    $activeCount,

                'completed' =>
                    $completedCount,

                'sos' =>
                    $sosCount,
            ],

            'activities' =>
                $result,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD ACTIVITY
    |--------------------------------------------------------------------------
    */

    private function buildActivityData(
        UserRoute $activity
    ): array {
        $startTime =
            $activity
                ->created_at
                ?->copy();


        $endTime =
            $activity
                ->completed_at
                ?->copy()
                ??
                now();


        /*
        |--------------------------------------------------------------------------
        | LOCATION HISTORY
        |--------------------------------------------------------------------------
        |
        | Kita ambil GPS berdasarkan:
        |
        | user_id
        | hiking_trail_id
        | waktu session
        |
        */

        $locations =
            UserLocation::query()

                ->where(
                    'user_id',
                    $activity->user_id
                )

                ->where(
                    'hiking_trail_id',
                    $activity->hiking_trail_id
                )

                ->when(
                    $startTime,
                    function (
                        $query
                    ) use (
                        $startTime
                    ) {
                        $query->where(
                            'recorded_at',
                            '>=',
                            $startTime
                        );
                    }
                )

                ->when(
                    $endTime,
                    function (
                        $query
                    ) use (
                        $endTime
                    ) {
                        /*
                        |--------------------------------------------------------------------------
                        | + 5 detik agar titik completed yang dibuat hampir bersamaan
                        | dengan completed_at tetap terbaca.
                        |--------------------------------------------------------------------------
                        */

                        $query->where(
                            'recorded_at',
                            '<=',
                            $endTime
                                ->copy()
                                ->addSeconds(
                                    5
                                )
                        );
                    }
                )

                ->orderBy(
                    'recorded_at'
                )

                ->get([
                    'id',
                    'latitude',
                    'longitude',
                    'altitude_m',
                    'battery_level',
                    'status',
                    'recorded_at',
                ]);


        /*
        |--------------------------------------------------------------------------
        | VALID LOCATIONS
        |--------------------------------------------------------------------------
        */

        $locations =
            $locations
                ->filter(
                    function (
                        UserLocation $location
                    ) {
                        return
                            is_numeric(
                                $location->latitude
                            )
                            &&
                            is_numeric(
                                $location->longitude
                            );
                    }
                )
                ->values();


        /*
        |--------------------------------------------------------------------------
        | START / LAST / FINISH
        |--------------------------------------------------------------------------
        */

        $firstLocation =
            $locations
                ->first();


        $lastLocation =
            $locations
                ->last();


        /*
        |--------------------------------------------------------------------------
        | HISTORY
        |--------------------------------------------------------------------------
        */

        $history =
            $locations
                ->map(
                    function (
                        UserLocation $location
                    ) {
                        return [
                            'id' =>
                                $location->id,

                            'lat' =>
                                (float)
                                $location->latitude,

                            'lng' =>
                                (float)
                                $location->longitude,

                            'altitude' =>
                                $location->altitude_m
                                !==
                                null

                                    ?

                                    (float)
                                    $location
                                        ->altitude_m

                                    :

                                    null,

                            'battery' =>
                                $location->battery_level
                                !==
                                null

                                    ?

                                    (int)
                                    $location
                                        ->battery_level

                                    :

                                    null,

                            'status' =>
                                $location->status
                                ??
                                'tracking',

                            'recorded_at' =>
                                $location
                                    ->recorded_at
                                    ?->toIso8601String(),

                            'time' =>
                                $location
                                    ->recorded_at
                                    ?->format(
                                        'd M Y H:i:s'
                                    ),
                        ];
                    }
                )
                ->values()
                ->toArray();


        /*
        |--------------------------------------------------------------------------
        | SOS LOCATIONS
        |--------------------------------------------------------------------------
        */

        $sosLocations =
            collect(
                $history
            )
                ->filter(
                    fn (
                        array $point
                    ): bool =>
                        $point[
                            'status'
                        ]
                        ===
                        'sos'
                )
                ->values()
                ->toArray();


        /*
        |--------------------------------------------------------------------------
        | DISTANCE TRAVELLED
        |--------------------------------------------------------------------------
        */

        $distanceKm =
            $this
                ->calculateHistoryDistance(
                    $history
                );


        /*
        |--------------------------------------------------------------------------
        | PLANNED TRAIL
        |--------------------------------------------------------------------------
        */

        $plannedRoute =
            $this
                ->extractTrailCoordinates(
                    $activity
                        ->hikingTrail
                        ?->map_geojson
                );


        /*
        |--------------------------------------------------------------------------
        | DURATION
        |--------------------------------------------------------------------------
        */

        $duration =
            $this
                ->formatDuration(
                    $startTime,
                    $endTime
                );


        /*
        |--------------------------------------------------------------------------
        | LAST UPDATE
        |--------------------------------------------------------------------------
        */

        $lastUpdate =
            $lastLocation
                ?->recorded_at;


        /*
        |--------------------------------------------------------------------------
        | RESPONSE ITEM
        |--------------------------------------------------------------------------
        */

        return [
            'id' =>
                $activity->id,

            'user_id' =>
                $activity->user_id,

            'user_name' =>
                $activity
                    ->user
                    ?->name
                ??
                'Pendaki',

            'user_email' =>
                $activity
                    ->user
                    ?->email,

            'trail_id' =>
                $activity
                    ->hiking_trail_id,

            'trail_name' =>
                $activity
                    ->hikingTrail
                    ?->name
                ??
                '-',

            'mountain_name' =>
                $activity
                    ->hikingTrail
                    ?->mountain
                    ?->name
                ??
                '-',

            'status' =>
                $activity
                    ->completed_at

                    ?

                    'completed'

                    :

                    'active',

            'status_label' =>
                $activity
                    ->completed_at

                    ?

                    'Selesai'

                    :

                    'Berlangsung',

            'started_at' =>
                $startTime
                    ?->toIso8601String(),

            'started_at_label' =>
                $startTime
                    ?->format(
                        'd M Y H:i'
                    ),

            'completed_at' =>
                $activity
                    ->completed_at
                    ?->toIso8601String(),

            'completed_at_label' =>
                $activity
                    ->completed_at
                    ?->format(
                        'd M Y H:i'
                    ),

            'duration' =>
                $duration,

            'distance_km' =>
                round(
                    $distanceKm,
                    2
                ),

            'location_count' =>
                count(
                    $history
                ),

            'has_sos' =>
                count(
                    $sosLocations
                )
                >
                0,

            'sos_count' =>
                count(
                    $sosLocations
                ),

            'start_location' =>
                $firstLocation

                    ?

                    [
                        'lat' =>
                            (float)
                            $firstLocation
                                ->latitude,

                        'lng' =>
                            (float)
                            $firstLocation
                                ->longitude,

                        'time' =>
                            $firstLocation
                                ->recorded_at
                                ?->format(
                                    'd M Y H:i:s'
                                ),
                    ]

                    :

                    null,

            'last_location' =>
                $lastLocation

                    ?

                    [
                        'lat' =>
                            (float)
                            $lastLocation
                                ->latitude,

                        'lng' =>
                            (float)
                            $lastLocation
                                ->longitude,

                        'altitude' =>
                            $lastLocation
                                ->altitude_m,

                        'battery' =>
                            $lastLocation
                                ->battery_level,

                        'status' =>
                            $lastLocation
                                ->status,

                        'time' =>
                            $lastLocation
                                ->recorded_at
                                ?->format(
                                    'd M Y H:i:s'
                                ),

                        'recorded_at' =>
                            $lastLocation
                                ->recorded_at
                                ?->toIso8601String(),
                    ]

                    :

                    null,

            'finish_location' =>
                (
                    $activity
                        ->completed_at
                    &&
                    $lastLocation
                )

                    ?

                    [
                        'lat' =>
                            (float)
                            $lastLocation
                                ->latitude,

                        'lng' =>
                            (float)
                            $lastLocation
                                ->longitude,

                        'time' =>
                            $lastLocation
                                ->recorded_at
                                ?->format(
                                    'd M Y H:i:s'
                                ),
                    ]

                    :

                    null,

            'last_update' =>
                $lastUpdate
                    ?->toIso8601String(),

            'last_update_label' =>
                $lastUpdate
                    ?->diffForHumans(),

            'planned_route' =>
                $plannedRoute,

            'history' =>
                $history,

            'sos_locations' =>
                $sosLocations,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | HISTORY DISTANCE
    |--------------------------------------------------------------------------
    */

    private function calculateHistoryDistance(
        array $history
    ): float {
        if (
            count(
                $history
            )
            <
            2
        ) {
            return 0;
        }


        $distance =
            0;


        for (
            $i = 1;
            $i < count(
                $history
            );
            $i++
        ) {
            $previous =
                $history[
                    $i - 1
                ];


            $current =
                $history[
                    $i
                ];


            $distance +=
                $this
                    ->haversineKm(
                        $previous[
                            'lat'
                        ],
                        $previous[
                            'lng'
                        ],
                        $current[
                            'lat'
                        ],
                        $current[
                            'lng'
                        ]
                    );
        }


        return $distance;
    }


    /*
    |--------------------------------------------------------------------------
    | HAVERSINE
    |--------------------------------------------------------------------------
    */

    private function haversineKm(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $radius =
            6371;


        $deltaLat =
            deg2rad(
                $lat2
                -
                $lat1
            );


        $deltaLng =
            deg2rad(
                $lng2
                -
                $lng1
            );


        $a =
            sin(
                $deltaLat
                /
                2
            )
            **
            2
            +
            cos(
                deg2rad(
                    $lat1
                )
            )
            *
            cos(
                deg2rad(
                    $lat2
                )
            )
            *
            sin(
                $deltaLng
                /
                2
            )
            **
            2;


        return
            $radius
            *
            2
            *
            atan2(
                sqrt(
                    $a
                ),
                sqrt(
                    1
                    -
                    $a
                )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DURATION
    |--------------------------------------------------------------------------
    */

    private function formatDuration(
        ?Carbon $start,
        ?Carbon $end
    ): string {
        if (
            ! $start
            ||
            ! $end
        ) {
            return '-';
        }


        $minutes =
            (int)
            $start
                ->diffInMinutes(
                    $end,
                    true
                );


        $days =
            intdiv(
                $minutes,
                1440
            );


        $hours =
            intdiv(
                $minutes
                %
                1440,
                60
            );


        $remainingMinutes =
            $minutes
            %
            60;


        $parts =
            [];


        if (
            $days
            >
            0
        ) {
            $parts[] =
                "{$days} hari";
        }


        if (
            $hours
            >
            0
        ) {
            $parts[] =
                "{$hours} jam";
        }


        if (
            $remainingMinutes
            >
            0
            ||
            empty(
                $parts
            )
        ) {
            $parts[] =
                "{$remainingMinutes} menit";
        }


        return implode(
            ' ',
            $parts
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TRAIL GEOJSON
    |--------------------------------------------------------------------------
    */

    private function extractTrailCoordinates(
        mixed $geoJson
    ): array {
        if (
            empty(
                $geoJson
            )
        ) {
            return [];
        }


        if (
            is_string(
                $geoJson
            )
        ) {
            $decoded =
                json_decode(
                    $geoJson,
                    true
                );


            if (
                json_last_error()
                ===
                JSON_ERROR_NONE
            ) {
                $geoJson =
                    $decoded;
            }
        }


        if (
            ! is_array(
                $geoJson
            )
        ) {
            return [];
        }


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
            $result =
                [];


            foreach (
                $geoJson[
                    'features'
                ]
                ??
                []
                as $feature
            ) {
                $result =
                    array_merge(
                        $result,
                        $this
                            ->extractTrailCoordinates(
                                $feature
                            )
                    );
            }


            return $result;
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
            return $this
                ->extractTrailCoordinates(
                    $geoJson[
                        'geometry'
                    ]
                    ??
                    []
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
            return collect(
                $geoJson[
                    'coordinates'
                ]
                ??
                []
            )
                ->filter(
                    function (
                        $coordinate
                    ) {
                        return
                            is_array(
                                $coordinate
                            )
                            &&
                            count(
                                $coordinate
                            )
                            >=
                            2
                            &&
                            is_numeric(
                                $coordinate[
                                    0
                                ]
                            )
                            &&
                            is_numeric(
                                $coordinate[
                                    1
                                ]
                            );
                    }
                )
                ->map(
                    function (
                        $coordinate
                    ) {
                        /*
                        |--------------------------------------------------------------------------
                        | GeoJSON:
                        | [lng, lat]
                        |
                        | Leaflet:
                        | [lat, lng]
                        |--------------------------------------------------------------------------
                        */

                        return [
                            (float)
                            $coordinate[
                                1
                            ],

                            (float)
                            $coordinate[
                                0
                            ],
                        ];
                    }
                )
                ->values()
                ->toArray();
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
            $result =
                [];


            foreach (
                $geoJson[
                    'coordinates'
                ]
                ??
                []
                as $line
            ) {
                foreach (
                    $line
                    as $coordinate
                ) {
                    if (
                        is_array(
                            $coordinate
                        )
                        &&
                        count(
                            $coordinate
                        )
                        >=
                        2
                    ) {
                        $result[] = [
                            (float)
                            $coordinate[
                                1
                            ],

                            (float)
                            $coordinate[
                                0
                            ],
                        ];
                    }
                }
            }


            return $result;
        }


        return [];
    }
}