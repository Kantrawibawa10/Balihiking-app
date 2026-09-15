<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\HikingTrail;
use App\Models\UserLocation;
use App\Models\UserRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LiveTrackController extends Controller
{
    /**
     * Akurasi GPS maksimum yang dianggap layak.
     */
    private const MAX_GPS_ACCURACY_M = 50;

    /**
     * Radius maksimal dari titik finish.
     */
    private const FINISH_RADIUS_M = 30;

    /**
     * Menampilkan halaman Live Tracking.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $trailId = $request->integer('trail_id');

        /*
        |--------------------------------------------------------------------------
        | BELUM MEMILIH JALUR
        |--------------------------------------------------------------------------
        */

        if (! $trailId) {
            return view('pendaki.live-track', [
                'user' => $user,
                'trail' => null,
                'userRoute' => null,
                'routeCoordinates' => [],
                'checkpoints' => [],
                'startPoint' => null,
                'finishPoint' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL JALUR
        |--------------------------------------------------------------------------
        */

        $trail = HikingTrail::query()
            ->with([
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
            ])
            ->where('is_active', true)
            ->findOrFail($trailId);

        /*
        |--------------------------------------------------------------------------
        | CARI SESI PENDAKIAN AKTIF
        |--------------------------------------------------------------------------
        |
        | Jika user sebelumnya sudah memulai tracking tetapi belum selesai,
        | jangan membuat user_routes baru lagi.
        |
        */

        $userRoute = UserRoute::query()
            ->where('user_id', $user->id)
            ->where(
                'hiking_trail_id',
                $trail->id
            )
            ->whereNull('completed_at')
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | BUAT SESI BARU
        |--------------------------------------------------------------------------
        |
        | gpx_file_path memang NULL di awal.
        |
        | File GPX user baru bisa dibuat nanti setelah ada rekaman perjalanan.
        |
        */

        if (! $userRoute) {
            $userRoute = UserRoute::create([
                'user_id' => $user->id,
                'hiking_trail_id' => $trail->id,
                'gpx_file_path' => null,
                'completed_at' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | KOORDINAT JALUR
        |--------------------------------------------------------------------------
        */

        $routeCoordinates =
            $this->resolveRouteCoordinates(
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
                    is_numeric($checkpoint->latitude)
                    &&
                    is_numeric($checkpoint->longitude);
            })
            ->values()
            ->map(function ($checkpoint, $index) {
                return [
                    'id' => $checkpoint->id,

                    'name' => $checkpoint->name
                        ?: 'Pos ' . ($index + 1),

                    'latitude' =>
                        (float) $checkpoint->latitude,

                    'longitude' =>
                        (float) $checkpoint->longitude,

                    'elevation_m' =>
                        $checkpoint->elevation_m !== null
                            ? (int) $checkpoint->elevation_m
                            : null,

                    'type' =>
                        $checkpoint->type,
                ];
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | START & FINISH
        |--------------------------------------------------------------------------
        */

        $startPoint = null;
        $finishPoint = null;

        if (
            count($routeCoordinates) >= 2
        ) {
            $startPoint =
                $routeCoordinates[0];

            $finishPoint =
                $routeCoordinates[
                    count($routeCoordinates) - 1
                ];
        }

        return view(
            'pendaki.live-track',
            compact(
                'user',
                'trail',
                'userRoute',
                'routeCoordinates',
                'checkpoints',
                'startPoint',
                'finishPoint'
            )
        );
    }

    /**
     * Simpan lokasi realtime pengguna.
     */
    public function storeLocation(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'trail_id' => [
                'required',
                'integer',
                'exists:hiking_trails,id',
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

        $user =
            $request->user();

        $trail = HikingTrail::query()
            ->whereKey(
                $validated['trail_id']
            )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN ADA SESI AKTIF
        |--------------------------------------------------------------------------
        */

        $userRoute = UserRoute::query()
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
            ->latest('id')
            ->first();

        if (! $userRoute) {
            return response()->json([
                'status' => 'error',

                'message' =>
                    'Sesi pendakian tidak ditemukan atau sudah selesai.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN GPS
        |--------------------------------------------------------------------------
        */

        $location = UserLocation::create([
            'user_id' =>
                $user->id,

            'hiking_trail_id' =>
                $trail->id,

            'latitude' =>
                $validated['latitude'],

            'longitude' =>
                $validated['longitude'],

            'altitude_m' =>
                $validated['altitude_m']
                ?? null,

            'battery_level' =>
                $validated['battery_level']
                ?? null,

            'status' =>
                $validated['status']
                ?? 'tracking',

            'recorded_at' =>
                now(),
        ]);

        return response()->json([
            'status' => 'success',

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
    }

    /**
     * Menyelesaikan pendakian.
     */
    public function complete(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'trail_id' => [
                'required',
                'integer',
                'exists:hiking_trails,id',
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
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        $user =
            $request->user();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI AKURASI GPS
        |--------------------------------------------------------------------------
        */

        if (
            (float) $validated['accuracy']
            >
            self::MAX_GPS_ACCURACY_M
        ) {
            return response()->json([
                'status' => 'error',

                'message' =>
                    'Akurasi GPS belum cukup baik untuk menyelesaikan pendakian.',

                'data' => [
                    'accuracy' =>
                        (float) $validated['accuracy'],

                    'required_accuracy' =>
                        self::MAX_GPS_ACCURACY_M,
                ],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL JALUR
        |--------------------------------------------------------------------------
        */

        $trail = HikingTrail::query()
            ->with('checkpoints')
            ->where(
                'is_active',
                true
            )
            ->findOrFail(
                $validated['trail_id']
            );

        /*
        |--------------------------------------------------------------------------
        | KOORDINAT
        |--------------------------------------------------------------------------
        */

        $routeCoordinates =
            $this->resolveRouteCoordinates(
                $trail
            );

        if (
            count($routeCoordinates) < 2
        ) {
            return response()->json([
                'status' => 'error',

                'message' =>
                    'Data koordinat jalur belum lengkap.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | FINISH POINT
        |--------------------------------------------------------------------------
        */

        $finishPoint =
            $routeCoordinates[
                count($routeCoordinates) - 1
            ];

        $latitude =
            (float) $validated['latitude'];

        $longitude =
            (float) $validated['longitude'];

        /*
        |--------------------------------------------------------------------------
        | JARAK KE FINISH
        |--------------------------------------------------------------------------
        */

        $distanceToFinish =
            $this->haversineMeters(
                $latitude,
                $longitude,
                (float) $finishPoint[0],
                (float) $finishPoint[1]
            );

        /*
        |--------------------------------------------------------------------------
        | JARAK DARI JALUR
        |--------------------------------------------------------------------------
        */

        $distanceFromRoute =
            $this->distancePointToPolylineMeters(
                $latitude,
                $longitude,
                $routeCoordinates
            );

        /*
        |--------------------------------------------------------------------------
        | TOLERANSI
        |--------------------------------------------------------------------------
        */

        $routeTolerance =
            max(
                30,
                (float) $validated['accuracy']
                * 1.5
            );

        /*
        |--------------------------------------------------------------------------
        | HARUS DEKAT FINISH
        |--------------------------------------------------------------------------
        */

        if (
            $distanceToFinish
            >
            self::FINISH_RADIUS_M
        ) {
            return response()->json([
                'status' => 'error',

                'message' =>
                    'Anda belum berada cukup dekat dengan titik akhir jalur.',

                'data' => [
                    'distance_to_finish_m' =>
                        round(
                            $distanceToFinish,
                            1
                        ),

                    'required_radius_m' =>
                        self::FINISH_RADIUS_M,
                ],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | HARUS DEKAT JALUR
        |--------------------------------------------------------------------------
        */

        if (
            $distanceFromRoute
            >
            $routeTolerance
        ) {
            return response()->json([
                'status' => 'error',

                'message' =>
                    'Posisi Anda berada terlalu jauh dari jalur.',

                'data' => [
                    'distance_from_route_m' =>
                        round(
                            $distanceFromRoute,
                            1
                        ),

                    'allowed_distance_m' =>
                        round(
                            $routeTolerance,
                            1
                        ),
                ],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | CARI SESI AKTIF
        |--------------------------------------------------------------------------
        */

        $userRoute = UserRoute::query()
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
            ->latest('id')
            ->first();

        if (! $userRoute) {
            return response()->json([
                'status' => 'error',

                'message' =>
                    'Sesi pendakian aktif tidak ditemukan.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | COMPLETE
        |--------------------------------------------------------------------------
        */

        $userRoute->update([
            'completed_at' =>
                now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN TITIK FINISH
        |--------------------------------------------------------------------------
        */

        UserLocation::create([
            'user_id' =>
                $user->id,

            'hiking_trail_id' =>
                $trail->id,

            'latitude' =>
                $latitude,

            'longitude' =>
                $longitude,

            'altitude_m' =>
                null,

            'battery_level' =>
                null,

            'status' =>
                'completed',

            'recorded_at' =>
                now(),
        ]);

        return response()->json([
            'status' => 'success',

            'message' =>
                'Pendakian berhasil diselesaikan.',

            'data' => [
                'user_route_id' =>
                    $userRoute->id,

                'completed_at' =>
                    $userRoute
                        ->completed_at
                        ?->toIso8601String(),

                'distance_to_finish_m' =>
                    round(
                        $distanceToFinish,
                        1
                    ),
            ],
        ]);
    }

    /**
     * Mengirim SOS.
     */
    public function sendSos(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'trail_id' => [
                'required',
                'integer',
                'exists:hiking_trails,id',
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

        $user =
            $request->user();

        $location = UserLocation::create([
            'user_id' =>
                $user->id,

            'hiking_trail_id' =>
                $validated['trail_id'],

            'latitude' =>
                $validated['latitude'],

            'longitude' =>
                $validated['longitude'],

            'altitude_m' =>
                $validated['altitude_m']
                ?? null,

            'battery_level' =>
                $validated['battery_level']
                ?? null,

            'status' =>
                'sos',

            'recorded_at' =>
                now(),
        ]);

        return response()->json([
            'status' => 'success',

            'message' =>
                'Sinyal SOS berhasil dikirim. Tetap berada di lokasi yang aman dan tunggu bantuan.',

            'data' => [
                'location_id' =>
                    $location->id,

                'user' =>
                    $user->name,

                'trail_id' =>
                    $validated['trail_id'],

                'latitude' =>
                    $validated['latitude'],

                'longitude' =>
                    $validated['longitude'],

                'time' =>
                    now()->format(
                        'H:i:s d-m-Y'
                    ),
            ],
        ]);
    }

    /**
     * Ambil koordinat jalur.
     *
     * Prioritas:
     *
     * 1. map_geojson
     * 2. coordinates
     * 3. checkpoints
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
        | COORDINATES
        |--------------------------------------------------------------------------
        */

        if (
            ! empty(
                $trail->coordinates
            )
        ) {
            $coordinates =
                $trail->coordinates;

            if (
                is_string(
                    $coordinates
                )
            ) {
                $decoded =
                    json_decode(
                        $coordinates,
                        true
                    );

                if (
                    json_last_error()
                    ===
                    JSON_ERROR_NONE
                ) {
                    $coordinates =
                        $decoded;
                }
            }

            $normalized =
                $this
                    ->normalizeCoordinateArray(
                        $coordinates
                    );

            if (
                ! empty(
                    $normalized
                )
            ) {
                return $normalized;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CHECKPOINT FALLBACK
        |--------------------------------------------------------------------------
        */

        return $trail
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
            ->map(function ($checkpoint) {
                return [
                    (float)
                        $checkpoint->latitude,

                    (float)
                        $checkpoint->longitude,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Extract GeoJSON menjadi format Leaflet:
     *
     * [
     *   [lat, lng],
     *   ...
     * ]
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

        /*
         * FeatureCollection
         */
        if (
            ($geoJson['type'] ?? null)
            ===
            'FeatureCollection'
        ) {
            foreach (
                $geoJson['features']
                ?? []
                as $feature
            ) {
                $coordinates =
                    $this
                        ->extractGeoJsonCoordinates(
                            $feature
                        );

                if (
                    ! empty(
                        $coordinates
                    )
                ) {
                    return $coordinates;
                }
            }

            return [];
        }

        /*
         * Feature
         */
        if (
            ($geoJson['type'] ?? null)
            ===
            'Feature'
        ) {
            return
                $this
                    ->extractGeoJsonCoordinates(
                        $geoJson['geometry']
                        ?? []
                    );
        }

        /*
         * LineString
         */
        if (
            ($geoJson['type'] ?? null)
            ===
            'LineString'
        ) {
            return collect(
                $geoJson['coordinates']
                ?? []
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
                                $coordinate[0]
                            )
                            &&
                            is_numeric(
                                $coordinate[1]
                            );
                    }
                )
                ->map(
                    function (
                        $coordinate
                    ) {
                        /*
                         * GeoJSON:
                         *
                         * [lng, lat]
                         *
                         * Leaflet:
                         *
                         * [lat, lng]
                         */

                        return [
                            (float)
                                $coordinate[1],

                            (float)
                                $coordinate[0],
                        ];
                    }
                )
                ->values()
                ->toArray();
        }

        /*
         * MultiLineString
         */
        if (
            ($geoJson['type'] ?? null)
            ===
            'MultiLineString'
        ) {
            $result = [];

            foreach (
                $geoJson['coordinates']
                ?? []
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
                            $coordinate[0]
                        )
                        &&
                        is_numeric(
                            $coordinate[1]
                        )
                    ) {
                        $result[] = [
                            (float)
                                $coordinate[1],

                            (float)
                                $coordinate[0],
                        ];
                    }
                }
            }

            return $result;
        }

        return [];
    }

    /**
     * Normalisasi kolom coordinates.
     */
    private function normalizeCoordinateArray(
        mixed $coordinates
    ): array {
        if (
            ! is_array(
                $coordinates
            )
        ) {
            return [];
        }

        $result = [];

        foreach (
            $coordinates
            as $coordinate
        ) {
            /*
             * Format associative:
             *
             * {
             *   lat: ...,
             *   lng: ...
             * }
             */
            if (
                is_array(
                    $coordinate
                )
                &&
                isset(
                    $coordinate['lat'],
                    $coordinate['lng']
                )
            ) {
                if (
                    is_numeric(
                        $coordinate['lat']
                    )
                    &&
                    is_numeric(
                        $coordinate['lng']
                    )
                ) {
                    $result[] = [
                        (float)
                            $coordinate['lat'],

                        (float)
                            $coordinate['lng'],
                    ];
                }

                continue;
            }

            /*
             * Format:
             *
             * [lat, lng]
             */
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
                    $coordinate[0]
                )
                &&
                is_numeric(
                    $coordinate[1]
                )
            ) {
                $result[] = [
                    (float)
                        $coordinate[0],

                    (float)
                        $coordinate[1],
                ];
            }
        }

        return $result;
    }

    /**
     * Hitung jarak antar koordinat dalam meter.
     */
    private function haversineMeters(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $earthRadius =
            6371000;

        $lat1Rad =
            deg2rad(
                $lat1
            );

        $lat2Rad =
            deg2rad(
                $lat2
            );

        $deltaLat =
            deg2rad(
                $lat2 - $lat1
            );

        $deltaLng =
            deg2rad(
                $lng2 - $lng1
            );

        $a =
            sin(
                $deltaLat / 2
            ) ** 2
            +
            cos(
                $lat1Rad
            )
            *
            cos(
                $lat2Rad
            )
            *
            sin(
                $deltaLng / 2
            ) ** 2;

        $c =
            2
            *
            atan2(
                sqrt($a),
                sqrt(
                    1 - $a
                )
            );

        return
            $earthRadius
            *
            $c;
    }

    /**
     * Hitung jarak titik user ke polyline jalur.
     */
    private function distancePointToPolylineMeters(
        float $latitude,
        float $longitude,
        array $routeCoordinates
    ): float {
        if (
            count(
                $routeCoordinates
            )
            <
            2
        ) {
            return INF;
        }

        $bestDistance =
            INF;

        for (
            $i = 0;
            $i
            <
            count(
                $routeCoordinates
            ) - 1;
            $i++
        ) {
            $a =
                $routeCoordinates[$i];

            $b =
                $routeCoordinates[
                    $i + 1
                ];

            $distance =
                $this
                    ->distancePointToSegmentMeters(
                        $latitude,
                        $longitude,

                        (float)
                            $a[0],

                        (float)
                            $a[1],

                        (float)
                            $b[0],

                        (float)
                            $b[1]
                    );

            if (
                $distance
                <
                $bestDistance
            ) {
                $bestDistance =
                    $distance;
            }
        }

        return $bestDistance;
    }

    /**
     * Jarak titik ke satu segmen polyline.
     */
    private function distancePointToSegmentMeters(
        float $pLat,
        float $pLng,
        float $aLat,
        float $aLng,
        float $bLat,
        float $bLng
    ): float {
        $earthRadius =
            6371000;

        $refLat =
            deg2rad(
                $pLat
            );

        $ax =
            deg2rad(
                $aLng - $pLng
            )
            *
            cos(
                $refLat
            )
            *
            $earthRadius;

        $ay =
            deg2rad(
                $aLat - $pLat
            )
            *
            $earthRadius;

        $bx =
            deg2rad(
                $bLng - $pLng
            )
            *
            cos(
                $refLat
            )
            *
            $earthRadius;

        $by =
            deg2rad(
                $bLat - $pLat
            )
            *
            $earthRadius;

        $vx =
            $bx - $ax;

        $vy =
            $by - $ay;

        $lengthSquared =
            ($vx * $vx)
            +
            ($vy * $vy);

        $t = 0;

        if (
            $lengthSquared > 0
        ) {
            $t =
                -(
                    ($ax * $vx)
                    +
                    ($ay * $vy)
                )
                /
                $lengthSquared;

            $t =
                max(
                    0,
                    min(
                        1,
                        $t
                    )
                );
        }

        $closestX =
            $ax
            +
            ($vx * $t);

        $closestY =
            $ay
            +
            ($vy * $t);

        return sqrt(
            ($closestX * $closestX)
            +
            ($closestY * $closestY)
        );
    }
}