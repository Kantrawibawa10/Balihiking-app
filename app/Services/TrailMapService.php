<?php

namespace App\Services;

use App\Models\HikingTrail;

class TrailMapService
{
    /**
     * Menghasilkan koordinat Leaflet:
     *
     * [
     *     [latitude, longitude],
     *     [latitude, longitude],
     * ]
     */
    public function resolveRouteCoordinates(
        HikingTrail $trail
    ): array {
        /*
        |--------------------------------------------------------------------------
        | 1. MAP GEOJSON
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
                    === JSON_ERROR_NONE
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
        | 2. COORDINATES
        |--------------------------------------------------------------------------
        */

        if (! empty($trail->coordinates)) {
            $coordinates = $trail->coordinates;

            if (is_string($coordinates)) {
                $decoded = json_decode(
                    $coordinates,
                    true
                );

                if (
                    json_last_error()
                    === JSON_ERROR_NONE
                ) {
                    $coordinates = $decoded;
                }
            }

            $normalized =
                $this->normalizeCoordinateArray(
                    $coordinates
                );

            if (! empty($normalized)) {
                return $normalized;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3. CHECKPOINT FALLBACK
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
                    (float) $checkpoint->latitude,
                    (float) $checkpoint->longitude,
                ];
            })
            ->values()
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | GEOJSON
    |--------------------------------------------------------------------------
    */

    private function extractGeoJsonCoordinates(
        mixed $geoJson
    ): array {
        if (! is_array($geoJson)) {
            return [];
        }

        /*
         * FeatureCollection
         */
        if (
            ($geoJson['type'] ?? null)
            === 'FeatureCollection'
        ) {
            foreach (
                $geoJson['features'] ?? []
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

        /*
         * Feature
         */
        if (
            ($geoJson['type'] ?? null)
            === 'Feature'
        ) {
            return
                $this->extractGeoJsonCoordinates(
                    $geoJson['geometry'] ?? []
                );
        }

        /*
         * LineString
         */
        if (
            ($geoJson['type'] ?? null)
            === 'LineString'
        ) {
            return collect(
                $geoJson['coordinates'] ?? []
            )
                ->filter(function ($coordinate) {
                    return
                        is_array($coordinate)
                        &&
                        count($coordinate) >= 2
                        &&
                        is_numeric($coordinate[0])
                        &&
                        is_numeric($coordinate[1]);
                })
                ->map(function ($coordinate) {
                    /*
                     * GeoJSON:
                     * [longitude, latitude]
                     *
                     * Leaflet:
                     * [latitude, longitude]
                     */

                    return [
                        (float) $coordinate[1],
                        (float) $coordinate[0],
                    ];
                })
                ->values()
                ->toArray();
        }

        /*
         * MultiLineString
         */
        if (
            ($geoJson['type'] ?? null)
            === 'MultiLineString'
        ) {
            $result = [];

            foreach (
                $geoJson['coordinates'] ?? []
                as $line
            ) {
                foreach (
                    $line
                    as $coordinate
                ) {
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

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE
    |--------------------------------------------------------------------------
    */

    private function normalizeCoordinateArray(
        mixed $coordinates
    ): array {
        if (! is_array($coordinates)) {
            return [];
        }

        $result = [];

        foreach (
            $coordinates
            as $coordinate
        ) {
            /*
             * {
             *    "lat": -8.x,
             *    "lng": 115.x
             * }
             */
            if (
                is_array($coordinate)
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
                        (float) $coordinate['lat'],
                        (float) $coordinate['lng'],
                    ];
                }

                continue;
            }

            /*
             * [lat, lng]
             */
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
                    (float) $coordinate[0],
                    (float) $coordinate[1],
                ];
            }
        }

        return $result;
    }
}