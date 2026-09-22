<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use RuntimeException;

class GpxParserService
{
    /**
     * Parse GPX content.
     */
    public static function parseFromContent(
        string $content
    ): array {
        $content = trim($content);

        if ($content === '') {
            throw new RuntimeException(
                'File GPX kosong.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD XML
        |--------------------------------------------------------------------------
        */

        $previous =
            libxml_use_internal_errors(
                true
            );

        $dom =
            new DOMDocument();

        $loaded =
            $dom->loadXML(
                $content,
                LIBXML_NONET
                |
                LIBXML_NOCDATA
            );

        if (! $loaded) {
            $errors =
                libxml_get_errors();

            libxml_clear_errors();

            libxml_use_internal_errors(
                $previous
            );

            $message =
                ! empty($errors)
                    ?
                    trim(
                        $errors[0]->message
                    )
                    :
                    'XML tidak valid.';

            throw new RuntimeException(
                'File GPX tidak valid: '
                .
                $message
            );
        }

        libxml_clear_errors();

        libxml_use_internal_errors(
            $previous
        );


        /*
        |--------------------------------------------------------------------------
        | XPATH
        |--------------------------------------------------------------------------
        */

        $xpath =
            new DOMXPath(
                $dom
            );


        /*
        |--------------------------------------------------------------------------
        | TRAIL NAME
        |--------------------------------------------------------------------------
        */

        $name =
            self::extractName(
                $xpath
            );


        /*
        |--------------------------------------------------------------------------
        | TRACK POINT
        |--------------------------------------------------------------------------
        |
        | Support:
        |
        | GPX 1.0
        | GPX 1.1
        | trkpt
        | rtept
        |
        */

        $nodes =
            $xpath->query(
                '//*[local-name()="trkpt"]'
            );


        if (
            ! $nodes
            ||
            $nodes->length === 0
        ) {
            $nodes =
                $xpath->query(
                    '//*[local-name()="rtept"]'
                );
        }


        if (
            ! $nodes
            ||
            $nodes->length === 0
        ) {
            throw new RuntimeException(
                'File GPX tidak memiliki track point (trkpt/rtept).'
            );
        }


        $coordinates = [];

        $geoJsonCoordinates = [];

        $elevations = [];


        foreach (
            $nodes as $node
        ) {
            if (! $node instanceof DOMElement) {
                continue;
            }


            $latitude =
                $node->getAttribute(
                    'lat'
                );

            $longitude =
                $node->getAttribute(
                    'lon'
                );


            if (
                ! is_numeric(
                    $latitude
                )
                ||
                ! is_numeric(
                    $longitude
                )
            ) {
                continue;
            }


            $latitude =
                (float)
                $latitude;

            $longitude =
                (float)
                $longitude;


            /*
            |--------------------------------------------------------------------------
            | ELEVATION
            |--------------------------------------------------------------------------
            */

            $elevation =
                null;


            $elevationNode =
                $xpath
                    ->query(
                        './*[local-name()="ele"]',
                        $node
                    )
                    ?->item(
                        0
                    );


            if (
                $elevationNode
                &&
                is_numeric(
                    trim(
                        $elevationNode->textContent
                    )
                )
            ) {
                $elevation =
                    (float)
                    trim(
                        $elevationNode->textContent
                    );


                $elevations[] =
                    $elevation;
            }


            /*
            |--------------------------------------------------------------------------
            | TIME
            |--------------------------------------------------------------------------
            */

            $time =
                null;


            $timeNode =
                $xpath
                    ->query(
                        './*[local-name()="time"]',
                        $node
                    )
                    ?->item(
                        0
                    );


            if ($timeNode) {
                $time =
                    trim(
                        $timeNode->textContent
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | NORMALIZED COORDINATE
            |--------------------------------------------------------------------------
            */

            $coordinates[] = [
                'lat' =>
                    $latitude,

                'lng' =>
                    $longitude,

                'ele' =>
                    $elevation,

                'time' =>
                    $time,
            ];


            /*
            |--------------------------------------------------------------------------
            | GEOJSON
            |--------------------------------------------------------------------------
            |
            | GeoJSON = [longitude, latitude]
            |--------------------------------------------------------------------------
            */

            $geoCoordinate = [
                $longitude,
                $latitude,
            ];


            if ($elevation !== null) {
                $geoCoordinate[] =
                    $elevation;
            }


            $geoJsonCoordinates[] =
                $geoCoordinate;
        }


        if (
            count(
                $coordinates
            )
            <
            2
        ) {
            throw new RuntimeException(
                'GPX harus memiliki minimal 2 titik koordinat.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DISTANCE
        |--------------------------------------------------------------------------
        */

        $distanceKm =
            self::calculateDistance(
                $coordinates
            );


        /*
        |--------------------------------------------------------------------------
        | ELEVATION
        |--------------------------------------------------------------------------
        */

        $maxElevation =
            ! empty(
                $elevations
            )
                ?
                max(
                    $elevations
                )
                :
                null;


        $minElevation =
            ! empty(
                $elevations
            )
                ?
                min(
                    $elevations
                )
                :
                null;


        /*
        |--------------------------------------------------------------------------
        | GEOJSON
        |--------------------------------------------------------------------------
        */

        $geoJson = [
            'type' =>
                'Feature',

            'properties' => [
                'name' =>
                    $name,
            ],

            'geometry' => [
                'type' =>
                    'LineString',

                'coordinates' =>
                    $geoJsonCoordinates,
            ],
        ];


        return [
            'name' =>
                $name,

            'coordinates' =>
                $coordinates,

            'map_geojson' =>
                $geoJson,

            'distance_km' =>
                round(
                    $distanceKm,
                    2
                ),

            'max_elevation' =>
                $maxElevation !== null
                    ?
                    (int)
                    round(
                        $maxElevation
                    )
                    :
                    null,

            'min_elevation' =>
                $minElevation !== null
                    ?
                    (int)
                    round(
                        $minElevation
                    )
                    :
                    null,

            'points_count' =>
                count(
                    $coordinates
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | EXTRACT NAME
    |--------------------------------------------------------------------------
    */

    private static function extractName(
        DOMXPath $xpath
    ): string {
        $queries = [

            '(//*[local-name()="trk"]/*[local-name()="name"])[1]',

            '(//*[local-name()="rte"]/*[local-name()="name"])[1]',

            '(//*[local-name()="metadata"]/*[local-name()="name"])[1]',
        ];


        foreach (
            $queries as $query
        ) {
            $node =
                $xpath
                    ->query(
                        $query
                    )
                    ?->item(
                        0
                    );


            if ($node instanceof DOMNode) {
                $value =
                    trim(
                        $node->textContent
                    );


                if ($value !== '') {
                    return $value;
                }
            }
        }


        return 'Jalur Pendakian';
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE DISTANCE
    |--------------------------------------------------------------------------
    */

    private static function calculateDistance(
        array $coordinates
    ): float {
        $total =
            0.0;


        for (
            $index = 1;
            $index < count(
                $coordinates
            );
            $index++
        ) {
            $previous =
                $coordinates[
                    $index - 1
                ];

            $current =
                $coordinates[
                    $index
                ];


            $total +=
                self::haversine(
                    (float)
                    $previous['lat'],
                    (float)
                    $previous['lng'],
                    (float)
                    $current['lat'],
                    (float)
                    $current['lng']
                );
        }


        return $total;
    }


    /*
    |--------------------------------------------------------------------------
    | HAVERSINE
    |--------------------------------------------------------------------------
    */

    private static function haversine(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius =
            6371;


        $latDifference =
            deg2rad(
                $lat2
                -
                $lat1
            );


        $lonDifference =
            deg2rad(
                $lon2
                -
                $lon1
            );


        $a =
            sin(
                $latDifference
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
                $lonDifference
                /
                2
            )
            **
            2;


        $c =
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


        return
            $earthRadius
            *
            $c;
    }
}