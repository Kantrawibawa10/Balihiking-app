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
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $user = $request->user();
        $trailId = $request->integer('trail_id');

        if (! $trailId) {
            return view('pendaki.live-track', [
                'user' => $user,
                'trail' => null,
                'userRoute' => null,
                'routeCoordinates' => [],
                'checkpoints' => [],
                'startPoint' => null,
                'finishPoint' => null,
                'sessionStatus' => 'idle',
            ]);
        }

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
        | SESSION AKTIF
        |--------------------------------------------------------------------------
        */

        $activeUserRoute = UserRoute::query()
            ->where('user_id', $user->id)
            ->where('hiking_trail_id', $trail->id)
            ->whereNull('completed_at')
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | SESSION TERAKHIR
        |--------------------------------------------------------------------------
        */

        $lastUserRoute = UserRoute::query()
            ->where('user_id', $user->id)
            ->where('hiking_trail_id', $trail->id)
            ->latest('id')
            ->first();

        $startNew = $request->boolean('start');

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN SESSION
        |--------------------------------------------------------------------------
        */

        if ($activeUserRoute) {
            /*
            | Masih ada pendakian aktif.
            */
            $userRoute = $activeUserRoute;
            $sessionStatus = 'active';
        } elseif ($startNew || ! $lastUserRoute) {
            /*
            | Mulai pendakian baru.
            */
            $userRoute = UserRoute::create([
                'user_id' => $user->id,
                'hiking_trail_id' => $trail->id,
                'gpx_file_path' => null,
                'completed_at' => null,
            ]);

            $sessionStatus = 'active';
        } else {
            /*
            | Pendakian sebelumnya sudah selesai.
            | Jangan otomatis membuat session baru.
            */
            $userRoute = $lastUserRoute;
            $sessionStatus = 'completed';
        }

        /*
        |--------------------------------------------------------------------------
        | ROUTE COORDINATES
        |--------------------------------------------------------------------------
        */

        $routeCoordinates = $this->resolveRouteCoordinates($trail);

        /*
        |--------------------------------------------------------------------------
        | CHECKPOINTS
        |--------------------------------------------------------------------------
        */

        $checkpoints = $trail->checkpoints
            ->filter(function ($checkpoint) {
                return is_numeric($checkpoint->latitude)
                    && is_numeric($checkpoint->longitude);
            })
            ->values()
            ->map(function ($checkpoint, $index) {
                return [
                    'id' => $checkpoint->id,
                    'name' => $checkpoint->name ?: 'Pos ' . ($index + 1),
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
        | START / FINISH
        |--------------------------------------------------------------------------
        */

        $startPoint = null;
        $finishPoint = null;

        if (count($routeCoordinates) >= 2) {
            $startPoint = $routeCoordinates[0];
            $finishPoint = $routeCoordinates[count($routeCoordinates) - 1];
        }

        return view('pendaki.live-track', compact(
            'user',
            'trail',
            'userRoute',
            'routeCoordinates',
            'checkpoints',
            'startPoint',
            'finishPoint',
            'sessionStatus'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE LOCATION
    |--------------------------------------------------------------------------
    */

    public function storeLocation(Request $request): JsonResponse
    {
        $validated = $request->validate([
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

        $user = $request->user();

        $trail = HikingTrail::query()
            ->whereKey($validated['trail_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $query = UserRoute::query()
            ->where('user_id', $user->id)
            ->where('hiking_trail_id', $trail->id)
            ->whereNull('completed_at');

        if (! empty($validated['user_route_id'])) {
            $query->where('id', $validated['user_route_id']);
        }

        $userRoute = $query
            ->latest('id')
            ->first();

        if (! $userRoute) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi pendakian tidak ditemukan atau sudah selesai.',
            ], 422);
        }

        $location = UserLocation::create([
            'user_id' => $user->id,
            'hiking_trail_id' => $trail->id,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'altitude_m' => $validated['altitude_m'] ?? null,
            'battery_level' => $validated['battery_level'] ?? null,
            'status' => $validated['status'] ?? 'tracking',
            'recorded_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi berhasil diperbarui.',

            'data' => [
                'id' => $location->id,
                'user_route_id' => $userRoute->id,
                'recorded_at' => now()->toIso8601String(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETE HIKE
    |--------------------------------------------------------------------------
    |
    | Pendakian boleh diselesaikan kapan saja.
    |
    | Tidak perlu berada di finish.
    | Tidak perlu GPS <= 50 meter.
    |
    | Yang penting:
    | - session masih aktif
    | - lokasi terakhir tersedia
    |
    |--------------------------------------------------------------------------
    */

    public function complete(Request $request): JsonResponse
    {
        $validated = $request->validate([
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

        $user = $request->user();

        $trail = HikingTrail::query()
            ->whereKey($validated['trail_id'])
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | CARI SESSION AKTIF
        |--------------------------------------------------------------------------
        */

        $activeQuery = UserRoute::query()
            ->where('user_id', $user->id)
            ->where('hiking_trail_id', $trail->id)
            ->whereNull('completed_at');

        if (! empty($validated['user_route_id'])) {
            $activeQuery->where(
                'id',
                $validated['user_route_id']
            );
        }

        $userRoute = $activeQuery
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | IDEMPOTENT
        |--------------------------------------------------------------------------
        |
        | Penting saat request offline disinkron ulang.
        |
        | Jika sebenarnya sudah selesai, return success.
        |--------------------------------------------------------------------------
        */

        if (! $userRoute) {
            $completedQuery = UserRoute::query()
                ->where('user_id', $user->id)
                ->where('hiking_trail_id', $trail->id)
                ->whereNotNull('completed_at');

            if (! empty($validated['user_route_id'])) {
                $completedQuery->where(
                    'id',
                    $validated['user_route_id']
                );
            }

            $completedRoute = $completedQuery
                ->latest('id')
                ->first();

            if ($completedRoute) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pendakian sudah diselesaikan.',

                    'data' => [
                        'user_route_id' => $completedRoute->id,
                        'completed_at' => optional(
                            $completedRoute->completed_at
                        )->toIso8601String(),
                        'already_completed' => true,
                    ],
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Sesi pendakian aktif tidak ditemukan.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | LOKASI TERAKHIR
        |--------------------------------------------------------------------------
        */

        $latitude = (float) $validated['latitude'];
        $longitude = (float) $validated['longitude'];

        $accuracy = isset($validated['accuracy'])
            ? (float) $validated['accuracy']
            : null;

        $altitude = isset($validated['altitude_m'])
            ? (float) $validated['altitude_m']
            : null;

        $batteryLevel = isset($validated['battery_level'])
            ? (int) $validated['battery_level']
            : null;

        /*
        |--------------------------------------------------------------------------
        | COMPLETE
        |--------------------------------------------------------------------------
        */

        $completedAt = now();

        $userRoute->update([
            'completed_at' => $completedAt,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN POSISI TERAKHIR
        |--------------------------------------------------------------------------
        */

        $finalLocation = UserLocation::create([
            'user_id' => $user->id,
            'hiking_trail_id' => $trail->id,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'altitude_m' => $altitude,
            'battery_level' => $batteryLevel,
            'status' => 'completed',
            'recorded_at' => $completedAt,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pendakian berhasil diselesaikan.',

            'data' => [
                'user_route_id' => $userRoute->id,
                'location_id' => $finalLocation->id,

                'completed_at' =>
                    $completedAt->toIso8601String(),

                'last_location' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'accuracy' => $accuracy,
                    'altitude_m' => $altitude,
                    'battery_level' => $batteryLevel,
                ],

                'already_completed' => false,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SOS
    |--------------------------------------------------------------------------
    */

    public function sendSos(Request $request): JsonResponse
    {
        $validated = $request->validate([
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

        $user = $request->user();

        $query = UserRoute::query()
            ->where('user_id', $user->id)
            ->where(
                'hiking_trail_id',
                $validated['trail_id']
            )
            ->whereNull('completed_at');

        if (! empty($validated['user_route_id'])) {
            $query->where(
                'id',
                $validated['user_route_id']
            );
        }

        $userRoute = $query
            ->latest('id')
            ->first();

        if (! $userRoute) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pendakian sudah selesai atau sesi aktif tidak ditemukan.',
            ], 422);
        }

        $location = UserLocation::create([
            'user_id' => $user->id,
            'hiking_trail_id' => $validated['trail_id'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'altitude_m' => $validated['altitude_m'] ?? null,
            'battery_level' => $validated['battery_level'] ?? null,
            'status' => 'sos',
            'recorded_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Sinyal SOS berhasil dikirim.',

            'data' => [
                'location_id' => $location->id,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'recorded_at' => now()->toIso8601String(),
            ],
        ]);
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

        if (! empty($trail->map_geojson)) {
            $geoJson = $trail->map_geojson;

            if (is_string($geoJson)) {
                $decoded = json_decode(
                    $geoJson,
                    true
                );

                if (
                    json_last_error()
                    ===
                    JSON_ERROR_NONE
                ) {
                    $geoJson = $decoded;
                }
            }

            $coordinates =
                $this->extractGeoJsonCoordinates(
                    $geoJson
                );

            if (! empty($coordinates)) {
                return $coordinates;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CHECKPOINT FALLBACK
        |--------------------------------------------------------------------------
        */

        $trail->loadMissing(
            'checkpoints'
        );

        return $trail->checkpoints
            ->filter(function ($checkpoint) {
                return is_numeric(
                    $checkpoint->latitude
                )
                    &&
                    is_numeric(
                        $checkpoint->longitude
                    );
            })
            ->map(function ($checkpoint) {
                return [
                    (float) $checkpoint->latitude,
                    (float) $checkpoint->longitude,
                ];
            })
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
        if (! is_array($geoJson)) {
            return [];
        }

        $type =
            $geoJson['type']
            ??
            null;

        if ($type === 'FeatureCollection') {
            foreach (
                $geoJson['features']
                ??
                []
                as $feature
            ) {
                $result =
                    $this->extractGeoJsonCoordinates(
                        $feature
                    );

                if (! empty($result)) {
                    return $result;
                }
            }

            return [];
        }

        if ($type === 'Feature') {
            return $this->extractGeoJsonCoordinates(
                $geoJson['geometry']
                ??
                []
            );
        }

        if ($type === 'LineString') {
            return collect(
                $geoJson['coordinates']
                ??
                []
            )
                ->filter(function ($coordinate) {
                    return is_array($coordinate)
                        &&
                        count($coordinate) >= 2
                        &&
                        is_numeric($coordinate[0])
                        &&
                        is_numeric($coordinate[1]);
                })
                ->map(function ($coordinate) {
                    /*
                    |--------------------------------------------------------------------------
                    | GeoJSON = [longitude, latitude]
                    |--------------------------------------------------------------------------
                    */

                    return [
                        (float) $coordinate[1],
                        (float) $coordinate[0],
                    ];
                })
                ->values()
                ->toArray();
        }

        if ($type === 'MultiLineString') {
            $result = [];

            foreach (
                $geoJson['coordinates']
                ??
                []
                as $line
            ) {
                foreach ($line as $coordinate) {
                    if (
                        is_array($coordinate)
                        &&
                        count($coordinate) >= 2
                        &&
                        is_numeric($coordinate[0])
                        &&
                        is_numeric($coordinate[1])
                    ) {
                        $result[] = [
                            (float) $coordinate[1],
                            (float) $coordinate[0],
                        ];
                    }
                }
            }

            return $result;
        }

        return [];
    }
}