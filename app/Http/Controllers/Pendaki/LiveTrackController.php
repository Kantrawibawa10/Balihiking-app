<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\HikingTrail;
use App\Models\UserLocation;
use App\Models\UserRoute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LiveTrackController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): View {
        $user =
            $request->user();

        $trailId =
            $request->integer(
                'trail_id'
            );

        /*
        |--------------------------------------------------------------------------
        | NO TRAIL
        |--------------------------------------------------------------------------
        */

        if (! $trailId) {
            return view(
                'pendaki.live-track',
                [
                    'user' =>
                        $user,

                    'trail' =>
                        null,

                    'userRoute' =>
                        null,

                    'routeCoordinates' =>
                        [],

                    'checkpoints' =>
                        [],

                    'startPoint' =>
                        null,

                    'finishPoint' =>
                        null,

                    'sessionStatus' =>
                        'idle',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TRAIL
        |--------------------------------------------------------------------------
        */

        $trail =
            HikingTrail::query()
                ->with([
                    'mountain',

                    'checkpoints' =>
                        function (
                            $query
                        ) {
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
                ])
                ->where(
                    'is_active',
                    true
                )
                ->findOrFail(
                    $trailId
                );

        /*
        |--------------------------------------------------------------------------
        | ACTIVE SESSION
        |--------------------------------------------------------------------------
        */

        $activeUserRoute =
            UserRoute::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'hiking_trail_id',
                    $trail->id
                )
                ->whereNull(
                    'completed_at'
                )
                ->latest(
                    'id'
                )
                ->first();

        /*
        |--------------------------------------------------------------------------
        | LAST SESSION
        |--------------------------------------------------------------------------
        */

        $lastUserRoute =
            UserRoute::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'hiking_trail_id',
                    $trail->id
                )
                ->latest(
                    'id'
                )
                ->first();

        /*
        |--------------------------------------------------------------------------
        | START NEW
        |--------------------------------------------------------------------------
        */

        $startNew =
            $request->boolean(
                'start'
            );

        /*
        |--------------------------------------------------------------------------
        | RESOLVE SESSION
        |--------------------------------------------------------------------------
        */

        if ($activeUserRoute) {
            $userRoute =
                $activeUserRoute;

            $sessionStatus =
                'active';

        } elseif (
            $startNew
            ||
            ! $lastUserRoute
        ) {
            $userRoute =
                new UserRoute();

            $userRoute->user_id =
                $user->id;

            $userRoute->hiking_trail_id =
                $trail->id;

            $userRoute->gpx_file_path =
                null;

            $userRoute->completed_at =
                null;

            $userRoute->save();

            $sessionStatus =
                'active';

        } else {
            /*
            |--------------------------------------------------------------------------
            | LAST HIKE ALREADY COMPLETED
            |--------------------------------------------------------------------------
            */

            $userRoute =
                $lastUserRoute;

            $sessionStatus =
                'completed';
        }

        /*
        |--------------------------------------------------------------------------
        | ROUTE COORDINATES
        |--------------------------------------------------------------------------
        */

        $routeCoordinates =
            $this->resolveRouteCoordinates(
                $trail
            );

        /*
        |--------------------------------------------------------------------------
        | CHECKPOINTS
        |--------------------------------------------------------------------------
        */

        $checkpoints =
            $trail
                ->checkpoints
                ->filter(
                    function (
                        $checkpoint
                    ) {
                        return
                            is_numeric(
                                $checkpoint->latitude
                            )
                            &&
                            is_numeric(
                                $checkpoint->longitude
                            );
                    }
                )
                ->values()
                ->map(
                    function (
                        $checkpoint,
                        $index
                    ) {
                        return [
                            'id' =>
                                $checkpoint->id,

                            'name' =>
                                $checkpoint->name
                                ?:
                                'Pos '
                                .
                                (
                                    $index + 1
                                ),

                            'latitude' =>
                                (float)
                                $checkpoint->latitude,

                            'longitude' =>
                                (float)
                                $checkpoint->longitude,

                            'elevation_m' =>
                                $checkpoint->elevation_m
                                !==
                                null
                                    ? (int)
                                        $checkpoint
                                            ->elevation_m
                                    : null,

                            'type' =>
                                $checkpoint->type,
                        ];
                    }
                )
                ->toArray();

        /*
        |--------------------------------------------------------------------------
        | START / FINISH
        |--------------------------------------------------------------------------
        */

        $startPoint =
            null;

        $finishPoint =
            null;

        if (
            count(
                $routeCoordinates
            )
            >=
            2
        ) {
            $startPoint =
                $routeCoordinates[
                    0
                ];

            $finishPoint =
                $routeCoordinates[
                    count(
                        $routeCoordinates
                    )
                    -
                    1
                ];
        }

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'pendaki.live-track',
            compact(
                'user',
                'trail',
                'userRoute',
                'routeCoordinates',
                'checkpoints',
                'startPoint',
                'finishPoint',
                'sessionStatus'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE LOCATION
    |--------------------------------------------------------------------------
    */

    public function storeLocation(
        Request $request
    ): JsonResponse {
        $validated =
            $request->validate([
                'trail_id' => [
                    'required',
                    'integer',
                    'exists:hiking_trails,id',
                ],

                'user_route_id' => [
                    'nullable',
                    'integer',
                    'exists:user_routes,id',
                ],

                'latitude' => [
                    'required',
                    'numeric',
                    'between:-90,90',
                ],

                'longitude' => [
                    'required',
                    'numeric',
                    'between:-180,180',
                ],

                'altitude_m' => [
                    'nullable',
                    'numeric',
                ],

                'battery_level' => [
                    'nullable',
                    'integer',
                    'between:0,100',
                ],

                'status' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
            ]);

        try {
            $user =
                $request->user();

            /*
            |--------------------------------------------------------------------------
            | TRAIL
            |--------------------------------------------------------------------------
            */

            $trail =
                HikingTrail::query()
                    ->whereKey(
                        $validated[
                            'trail_id'
                        ]
                    )
                    ->where(
                        'is_active',
                        true
                    )
                    ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | ACTIVE ROUTE
            |--------------------------------------------------------------------------
            */

            $userRoute =
                $this->findActiveRoute(
                    userId:
                        $user->id,

                    trailId:
                        $trail->id,

                    userRouteId:
                        $validated[
                            'user_route_id'
                        ]
                        ??
                        null
                );

            if (! $userRoute) {
                return response()->json(
                    [
                        'status' =>
                            'error',

                        'message' =>
                            'Sesi pendakian tidak ditemukan atau sudah selesai.',
                    ],
                    422
                );
            }

            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            */

            $location =
                new UserLocation();

            $location->user_id =
                $user->id;

            $location->hiking_trail_id =
                $trail->id;

            $location->latitude =
                (float)
                $validated[
                    'latitude'
                ];

            $location->longitude =
                (float)
                $validated[
                    'longitude'
                ];

            $location->altitude_m =
                isset(
                    $validated[
                        'altitude_m'
                    ]
                )
                    ? (float)
                        $validated[
                            'altitude_m'
                        ]
                    : null;

            $location->battery_level =
                isset(
                    $validated[
                        'battery_level'
                    ]
                )
                    ? (int)
                        $validated[
                            'battery_level'
                        ]
                    : null;

            $location->status =
                $validated[
                    'status'
                ]
                ??
                'tracking';

            $location->recorded_at =
                now();

            $location->save();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'status' =>
                    'success',

                'message' =>
                    'Lokasi berhasil diperbarui.',

                'data' => [
                    'id' =>
                        $location->id,

                    'user_route_id' =>
                        $userRoute->id,

                    'recorded_at' =>
                        $location
                            ->recorded_at
                            ?->toIso8601String(),
                ],
            ]);

        } catch (Throwable $exception) {
            return $this->errorResponse(
                exception:
                    $exception,

                action:
                    'STORE LOCATION',

                request:
                    $request
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETE HIKE
    |--------------------------------------------------------------------------
    */

    public function complete(
        Request $request
    ): JsonResponse {
        $validated =
            $request->validate([
                'trail_id' => [
                    'required',
                    'integer',
                    'exists:hiking_trails,id',
                ],

                'user_route_id' => [
                    'nullable',
                    'integer',
                    'exists:user_routes,id',
                ],

                'latitude' => [
                    'required',
                    'numeric',
                    'between:-90,90',
                ],

                'longitude' => [
                    'required',
                    'numeric',
                    'between:-180,180',
                ],

                'accuracy' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'altitude_m' => [
                    'nullable',
                    'numeric',
                ],

                'battery_level' => [
                    'nullable',
                    'integer',
                    'between:0,100',
                ],
            ]);

        try {
            $user =
                $request->user();

            /*
            |--------------------------------------------------------------------------
            | TRAIL
            |--------------------------------------------------------------------------
            */

            $trail =
                HikingTrail::query()
                    ->whereKey(
                        $validated[
                            'trail_id'
                        ]
                    )
                    ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | NORMALIZE LOCATION
            |--------------------------------------------------------------------------
            */

            $latitude =
                (float)
                $validated[
                    'latitude'
                ];

            $longitude =
                (float)
                $validated[
                    'longitude'
                ];

            $accuracy =
                isset(
                    $validated[
                        'accuracy'
                    ]
                )
                    ? (float)
                        $validated[
                            'accuracy'
                        ]
                    : null;

            $altitude =
                isset(
                    $validated[
                        'altitude_m'
                    ]
                )
                    ? (float)
                        $validated[
                            'altitude_m'
                        ]
                    : null;

            $batteryLevel =
                isset(
                    $validated[
                        'battery_level'
                    ]
                )
                    ? (int)
                        $validated[
                            'battery_level'
                        ]
                    : null;

            /*
            |--------------------------------------------------------------------------
            | IDEMPOTENT CHECK
            |--------------------------------------------------------------------------
            |
            | Kalau request offline pernah terkirim sebelumnya,
            | server tetap memberi success.
            |
            */

            $completedRoute =
                $this->findCompletedRoute(
                    userId:
                        $user->id,

                    trailId:
                        $trail->id,

                    userRouteId:
                        $validated[
                            'user_route_id'
                        ]
                        ??
                        null
                );

            if ($completedRoute) {
                return response()->json([
                    'status' =>
                        'success',

                    'message' =>
                        'Pendakian sudah diselesaikan.',

                    'data' => [
                        'user_route_id' =>
                            $completedRoute->id,

                        'completed_at' =>
                            $completedRoute
                                ->completed_at
                                ?->toIso8601String(),

                        'already_completed' =>
                            true,
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | TRANSACTION
            |--------------------------------------------------------------------------
            |
            | Final location dan completed_at HARUS berhasil bersama.
            |
            | Jika salah satu gagal:
            |
            | ROLLBACK semuanya.
            |
            */

            $result =
                DB::transaction(
                    function () use (
                        $user,
                        $trail,
                        $validated,
                        $latitude,
                        $longitude,
                        $altitude,
                        $batteryLevel
                    ) {
                        /*
                        |--------------------------------------------------------------------------
                        | LOCK ACTIVE ROUTE
                        |--------------------------------------------------------------------------
                        */

                        $routeQuery =
                            UserRoute::query()
                                ->where(
                                    'user_id',
                                    $user->id
                                )
                                ->where(
                                    'hiking_trail_id',
                                    $trail->id
                                )
                                ->whereNull(
                                    'completed_at'
                                );

                        if (
                            ! empty(
                                $validated[
                                    'user_route_id'
                                ]
                            )
                        ) {
                            $routeQuery
                                ->where(
                                    'id',
                                    $validated[
                                        'user_route_id'
                                    ]
                                );
                        }

                        $userRoute =
                            $routeQuery
                                ->lockForUpdate()
                                ->latest(
                                    'id'
                                )
                                ->first();

                        /*
                        |--------------------------------------------------------------------------
                        | ROUTE NOT FOUND
                        |--------------------------------------------------------------------------
                        */

                        if (! $userRoute) {
                            return null;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | FINAL LOCATION FIRST
                        |--------------------------------------------------------------------------
                        |
                        | Jika ini gagal, completed_at belum tersimpan.
                        |
                        */

                        $finalLocation =
                            new UserLocation();

                        $finalLocation->user_id =
                            $user->id;

                        $finalLocation->hiking_trail_id =
                            $trail->id;

                        $finalLocation->latitude =
                            $latitude;

                        $finalLocation->longitude =
                            $longitude;

                        $finalLocation->altitude_m =
                            $altitude;

                        $finalLocation->battery_level =
                            $batteryLevel;

                        $finalLocation->status =
                            'completed';

                        $finalLocation->recorded_at =
                            now();

                        $finalLocation->save();

                        /*
                        |--------------------------------------------------------------------------
                        | COMPLETE ROUTE
                        |--------------------------------------------------------------------------
                        */

                        $completedAt =
                            now();

                        $userRoute->completed_at =
                            $completedAt;

                        $userRoute->save();

                        /*
                        |--------------------------------------------------------------------------
                        | RESULT
                        |--------------------------------------------------------------------------
                        */

                        return [
                            'user_route_id' =>
                                $userRoute->id,

                            'location_id' =>
                                $finalLocation->id,

                            'completed_at' =>
                                $completedAt
                                    ->toIso8601String(),
                        ];
                    },
                    3
                );

            /*
            |--------------------------------------------------------------------------
            | ACTIVE ROUTE NOT FOUND
            |--------------------------------------------------------------------------
            */

            if (! $result) {
                /*
                |--------------------------------------------------------------------------
                | CHECK AGAIN IN CASE ANOTHER REQUEST COMPLETED IT
                |--------------------------------------------------------------------------
                */

                $completedRoute =
                    $this->findCompletedRoute(
                        userId:
                            $user->id,

                        trailId:
                            $trail->id,

                        userRouteId:
                            $validated[
                                'user_route_id'
                            ]
                            ??
                            null
                    );

                if ($completedRoute) {
                    return response()->json([
                        'status' =>
                            'success',

                        'message' =>
                            'Pendakian sudah diselesaikan.',

                        'data' => [
                            'user_route_id' =>
                                $completedRoute->id,

                            'completed_at' =>
                                $completedRoute
                                    ->completed_at
                                    ?->toIso8601String(),

                            'already_completed' =>
                                true,
                        ],
                    ]);
                }

                return response()->json(
                    [
                        'status' =>
                            'error',

                        'message' =>
                            'Sesi pendakian aktif tidak ditemukan.',
                    ],
                    422
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'status' =>
                    'success',

                'message' =>
                    'Pendakian berhasil diselesaikan.',

                'data' => [
                    'user_route_id' =>
                        $result[
                            'user_route_id'
                        ],

                    'location_id' =>
                        $result[
                            'location_id'
                        ],

                    'completed_at' =>
                        $result[
                            'completed_at'
                        ],

                    'last_location' => [
                        'latitude' =>
                            $latitude,

                        'longitude' =>
                            $longitude,

                        'accuracy' =>
                            $accuracy,

                        'altitude_m' =>
                            $altitude,

                        'battery_level' =>
                            $batteryLevel,
                    ],

                    'already_completed' =>
                        false,
                ],
            ]);

        } catch (Throwable $exception) {
            return $this->errorResponse(
                exception:
                    $exception,

                action:
                    'COMPLETE HIKE',

                request:
                    $request
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SOS
    |--------------------------------------------------------------------------
    */

    public function sendSos(
        Request $request
    ): JsonResponse {
        $validated =
            $request->validate([
                'trail_id' => [
                    'required',
                    'integer',
                    'exists:hiking_trails,id',
                ],

                'user_route_id' => [
                    'nullable',
                    'integer',
                    'exists:user_routes,id',
                ],

                'latitude' => [
                    'required',
                    'numeric',
                    'between:-90,90',
                ],

                'longitude' => [
                    'required',
                    'numeric',
                    'between:-180,180',
                ],

                'altitude_m' => [
                    'nullable',
                    'numeric',
                ],

                'battery_level' => [
                    'nullable',
                    'integer',
                    'between:0,100',
                ],
            ]);

        try {
            $user =
                $request->user();

            /*
            |--------------------------------------------------------------------------
            | ACTIVE ROUTE
            |--------------------------------------------------------------------------
            */

            $userRoute =
                $this->findActiveRoute(
                    userId:
                        $user->id,

                    trailId:
                        (int)
                        $validated[
                            'trail_id'
                        ],

                    userRouteId:
                        $validated[
                            'user_route_id'
                        ]
                        ??
                        null
                );

            if (! $userRoute) {
                return response()->json(
                    [
                        'status' =>
                            'error',

                        'message' =>
                            'Pendakian sudah selesai atau sesi aktif tidak ditemukan.',
                    ],
                    422
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SOS LOCATION
            |--------------------------------------------------------------------------
            */

            $location =
                new UserLocation();

            $location->user_id =
                $user->id;

            $location->hiking_trail_id =
                (int)
                $validated[
                    'trail_id'
                ];

            $location->latitude =
                (float)
                $validated[
                    'latitude'
                ];

            $location->longitude =
                (float)
                $validated[
                    'longitude'
                ];

            $location->altitude_m =
                isset(
                    $validated[
                        'altitude_m'
                    ]
                )
                    ? (float)
                        $validated[
                            'altitude_m'
                        ]
                    : null;

            $location->battery_level =
                isset(
                    $validated[
                        'battery_level'
                    ]
                )
                    ? (int)
                        $validated[
                            'battery_level'
                        ]
                    : null;

            $location->status =
                'sos';

            $location->recorded_at =
                now();

            $location->save();

            return response()->json([
                'status' =>
                    'success',

                'message' =>
                    'Sinyal SOS berhasil dikirim.',

                'data' => [
                    'location_id' =>
                        $location->id,

                    'user_route_id' =>
                        $userRoute->id,

                    'latitude' =>
                        $location->latitude,

                    'longitude' =>
                        $location->longitude,

                    'recorded_at' =>
                        $location
                            ->recorded_at
                            ?->toIso8601String(),
                ],
            ]);

        } catch (Throwable $exception) {
            return $this->errorResponse(
                exception:
                    $exception,

                action:
                    'SOS',

                request:
                    $request
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FIND ACTIVE ROUTE
    |--------------------------------------------------------------------------
    */

    private function findActiveRoute(
        int $userId,
        int $trailId,
        ?int $userRouteId = null
    ): ?UserRoute {
        $query =
            UserRoute::query()
                ->where(
                    'user_id',
                    $userId
                )
                ->where(
                    'hiking_trail_id',
                    $trailId
                )
                ->whereNull(
                    'completed_at'
                );

        if ($userRouteId) {
            $query->where(
                'id',
                $userRouteId
            );
        }

        return $query
            ->latest(
                'id'
            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND COMPLETED ROUTE
    |--------------------------------------------------------------------------
    */

    private function findCompletedRoute(
        int $userId,
        int $trailId,
        ?int $userRouteId = null
    ): ?UserRoute {
        $query =
            UserRoute::query()
                ->where(
                    'user_id',
                    $userId
                )
                ->where(
                    'hiking_trail_id',
                    $trailId
                )
                ->whereNotNull(
                    'completed_at'
                );

        if ($userRouteId) {
            $query->where(
                'id',
                $userRouteId
            );
        }

        return $query
            ->latest(
                'id'
            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | ERROR RESPONSE
    |--------------------------------------------------------------------------
    */

    private function errorResponse(
        Throwable $exception,
        string $action,
        Request $request
    ): JsonResponse {
        Log::error(
            'LIVE TRACK '
            .
            $action
            .
            ' ERROR',
            [
                'user_id' =>
                    $request
                        ->user()
                        ?->id,

                'message' =>
                    $exception
                        ->getMessage(),

                'file' =>
                    $exception
                        ->getFile(),

                'line' =>
                    $exception
                        ->getLine(),

                'payload' =>
                    $request
                        ->except([
                            '_token',
                        ]),
            ]
        );

        $response = [
            'status' =>
                'error',

            'message' =>
                'Terjadi kesalahan saat memproses live tracking.',
        ];

        /*
        |--------------------------------------------------------------------------
        | DEBUG LOCAL ONLY
        |--------------------------------------------------------------------------
        */

        if (
            config(
                'app.debug'
            )
        ) {
            $response[
                'debug'
            ] = [
                'message' =>
                    $exception
                        ->getMessage(),

                'file' =>
                    $exception
                        ->getFile(),

                'line' =>
                    $exception
                        ->getLine(),
            ];
        }

        return response()->json(
            $response,
            500
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE ROUTE COORDINATES
    |--------------------------------------------------------------------------
    */

    private function resolveRouteCoordinates(
        HikingTrail $trail
    ): array {
        /*
        |--------------------------------------------------------------------------
        | MAP GEOJSON
        |--------------------------------------------------------------------------
        */

        if (
            ! empty(
                $trail->map_geojson
            )
        ) {
            $geoJson =
                $trail->map_geojson;

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

            $coordinates =
                $this
                    ->extractGeoJsonCoordinates(
                        $geoJson
                    );

            if (
                ! empty(
                    $coordinates
                )
            ) {
                return $coordinates;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CHECKPOINT FALLBACK
        |--------------------------------------------------------------------------
        */

        $trail
            ->loadMissing(
                'checkpoints'
            );

        return $trail
            ->checkpoints
            ->filter(
                function (
                    $checkpoint
                ) {
                    return
                        is_numeric(
                            $checkpoint->latitude
                        )
                        &&
                        is_numeric(
                            $checkpoint->longitude
                        );
                }
            )
            ->map(
                function (
                    $checkpoint
                ) {
                    return [
                        (float)
                        $checkpoint->latitude,

                        (float)
                        $checkpoint->longitude,
                    ];
                }
            )
            ->values()
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | EXTRACT GEOJSON
    |--------------------------------------------------------------------------
    */

    private function extractGeoJsonCoordinates(
        mixed $geoJson
    ): array {
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
            $type ===
            'FeatureCollection'
        ) {
            foreach (
                $geoJson[
                    'features'
                ]
                ??
                []
                as $feature
            ) {
                $result =
                    $this
                        ->extractGeoJsonCoordinates(
                            $feature
                        );

                if (
                    ! empty(
                        $result
                    )
                ) {
                    return $result;
                }
            }

            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | FEATURE
        |--------------------------------------------------------------------------
        */

        if (
            $type ===
            'Feature'
        ) {
            return $this
                ->extractGeoJsonCoordinates(
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
            $type ===
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
                        |
                        | [longitude, latitude]
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
            $type ===
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
                        )
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