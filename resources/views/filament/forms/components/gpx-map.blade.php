@php

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE DATA
    |--------------------------------------------------------------------------
    */

    $rawCoordinates =
        $coordinates
        ??
        [];

    $rawGeoJson =
        $mapGeoJson
        ??
        [];


    /*
    |--------------------------------------------------------------------------
    | JSON STRING -> ARRAY
    |--------------------------------------------------------------------------
    */

    if (is_string($rawCoordinates)) {

        $decoded =
            json_decode(
                $rawCoordinates,
                true
            );

        if (json_last_error() === JSON_ERROR_NONE) {

            $rawCoordinates =
                $decoded;

        }

    }


    if (is_string($rawGeoJson)) {

        $decoded =
            json_decode(
                $rawGeoJson,
                true
            );

        if (json_last_error() === JSON_ERROR_NONE) {

            $rawGeoJson =
                $decoded;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZED POINTS
    |--------------------------------------------------------------------------
    */

    $points = [];


    if (is_array($rawCoordinates)) {

        foreach ($rawCoordinates as $point) {

            /*
            |--------------------------------------------------------------------------
            | NEW FORMAT
            |--------------------------------------------------------------------------
            */

            if (
                is_array($point)
                &&
                isset(
                    $point['lat'],
                    $point['lng']
                )
            ) {

                $points[] = [

                    'lat' =>
                        (float)
                        $point['lat'],

                    'lng' =>
                        (float)
                        $point['lng'],

                    'ele' =>
                        isset($point['ele'])
                            &&
                            is_numeric($point['ele'])

                                ?

                                (float)
                                $point['ele']

                                :

                                null,

                ];

                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | LEGACY [LAT, LNG]
            |--------------------------------------------------------------------------
            */

            if (
                is_array($point)
                &&
                isset(
                    $point[0],
                    $point[1]
                )
                &&
                is_numeric($point[0])
                &&
                is_numeric($point[1])
            ) {

                $points[] = [

                    'lat' =>
                        (float)
                        $point[0],

                    'lng' =>
                        (float)
                        $point[1],

                    'ele' =>
                        isset($point[2])
                            &&
                            is_numeric($point[2])

                                ?

                                (float)
                                $point[2]

                                :

                                null,

                ];

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | FALLBACK GEOJSON
    |--------------------------------------------------------------------------
    */

    if (
        empty($points)
        &&
        is_array($rawGeoJson)
    ) {

        $geometry =
            $rawGeoJson;


        if (
            ($rawGeoJson['type'] ?? null)
            ===
            'Feature'
        ) {

            $geometry =
                $rawGeoJson['geometry']
                ??
                [];

        }


        if (
            ($geometry['type'] ?? null)
            ===
            'LineString'
        ) {

            foreach (
                $geometry['coordinates']
                ??
                []
                as
                $coordinate
            ) {

                if (
                    ! is_array($coordinate)
                    ||
                    ! isset(
                        $coordinate[0],
                        $coordinate[1]
                    )
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | GEOJSON = LNG, LAT
                |--------------------------------------------------------------------------
                */

                $points[] = [

                    'lat' =>
                        (float)
                        $coordinate[1],

                    'lng' =>
                        (float)
                        $coordinate[0],

                    'ele' =>
                        isset($coordinate[2])
                            &&
                            is_numeric($coordinate[2])

                                ?

                                (float)
                                $coordinate[2]

                                :

                                null,

                ];

            }

        }

    }


    $mapKey =
        'gpx-map-'
        .
        \Illuminate\Support\Str::random(
            10
        );

@endphp


@once

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    ></script>

@endonce


<div
    wire:key="{{ $mapKey }}"
    x-data="{

        map: null,

        routeLayer: null,

        points: @js($points),


        initMap() {

            const boot = () => {

                /*
                |--------------------------------------------------------------------------
                | WAIT LEAFLET
                |--------------------------------------------------------------------------
                */

                if (! window.L) {

                    setTimeout(
                        boot,
                        100
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | PREVENT DOUBLE INIT
                |--------------------------------------------------------------------------
                */

                if (
                    this.map
                    ||
                    this.$refs.map._leaflet_id
                ) {

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | CREATE MAP
                |--------------------------------------------------------------------------
                */

                this.map =
                    L.map(
                        this.$refs.map,
                        {
                            zoomControl:
                                true,
                        }
                    )
                    .setView(
                        [
                            -8.4095,
                            115.1889
                        ],
                        9
                    );


                /*
                |--------------------------------------------------------------------------
                | OSM TILE
                |--------------------------------------------------------------------------
                */

                L.tileLayer(
                    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    {
                        maxZoom:
                            19,

                        attribution:
                            '© OpenStreetMap',
                    }
                )
                .addTo(
                    this.map
                );


                /*
                |--------------------------------------------------------------------------
                | DRAW ROUTE
                |--------------------------------------------------------------------------
                */

                this.drawRoute();


                /*
                |--------------------------------------------------------------------------
                | FIX FILAMENT RESIZE
                |--------------------------------------------------------------------------
                */

                setTimeout(
                    () => {

                        this.map
                            ?.invalidateSize();

                    },
                    250
                );


                setTimeout(
                    () => {

                        this.map
                            ?.invalidateSize();

                    },
                    700
                );

            };


            boot();

        },


        drawRoute() {

            if (
                !this.map
                ||
                !Array.isArray(
                    this.points
                )
                ||
                this.points.length === 0
            ) {

                return;

            }


            const latLngs =
                this.points
                    .filter(
                        point => {

                            return (
                                Number.isFinite(
                                    Number(point.lat)
                                )
                                &&
                                Number.isFinite(
                                    Number(point.lng)
                                )
                            );

                        }
                    )
                    .map(
                        point => [

                            Number(
                                point.lat
                            ),

                            Number(
                                point.lng
                            ),

                        ]
                    );


            if (
                latLngs.length === 0
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | WHITE OUTLINE
            |--------------------------------------------------------------------------
            */

            L.polyline(
                latLngs,
                {
                    color:
                        '#ffffff',

                    weight:
                        8,

                    opacity:
                        .9,

                    lineJoin:
                        'round',
                }
            )
            .addTo(
                this.map
            );


            /*
            |--------------------------------------------------------------------------
            | MAIN ROUTE
            |--------------------------------------------------------------------------
            */

            this.routeLayer =
                L.polyline(
                    latLngs,
                    {
                        color:
                            '#f06535',

                        weight:
                            5,

                        opacity:
                            1,

                        lineJoin:
                            'round',

                        lineCap:
                            'round',
                    }
                )
                .addTo(
                    this.map
                );


            /*
            |--------------------------------------------------------------------------
            | START
            |--------------------------------------------------------------------------
            */

            L.circleMarker(
                latLngs[0],
                {
                    radius:
                        7,

                    color:
                        '#ffffff',

                    weight:
                        3,

                    fillColor:
                        '#10b981',

                    fillOpacity:
                        1,
                }
            )
            .addTo(
                this.map
            )
            .bindTooltip(
                'Titik Mulai'
            );


            /*
            |--------------------------------------------------------------------------
            | FINISH
            |--------------------------------------------------------------------------
            */

            L.circleMarker(
                latLngs[
                    latLngs.length - 1
                ],
                {
                    radius:
                        7,

                    color:
                        '#ffffff',

                    weight:
                        3,

                    fillColor:
                        '#2563eb',

                    fillOpacity:
                        1,
                }
            )
            .addTo(
                this.map
            )
            .bindTooltip(
                'Titik Akhir'
            );


            /*
            |--------------------------------------------------------------------------
            | FIT ROUTE
            |--------------------------------------------------------------------------
            */

            this.map
                .fitBounds(
                    this.routeLayer
                        .getBounds(),
                    {
                        padding:
                            [
                                35,
                                35
                            ],
                    }
                );

        },

    }"
    x-init="initMap()"
    class="
        overflow-hidden
        rounded-2xl
        border
        border-gray-200
        bg-gray-100
    "
>

    <div
        x-ref="map"
        style="
            width: 100%;
            height: 420px;
            min-height: 420px;
        "
    ></div>


    @if (empty($points))

        <div
            class="
                border-t
                border-gray-200
                bg-amber-50
                px-4
                py-3
                text-xs
                text-amber-700
            "
        >
            Belum ada koordinat GPX yang tersimpan.
            Jika file GPX lama sudah ada, buka halaman Edit lalu simpan kembali.
        </div>

    @else

        <div
            class="
                border-t
                border-gray-200
                bg-white
                px-4
                py-2
                text-xs
                text-gray-500
            "
        >
            {{ number_format(count($points)) }}
            titik GPS berhasil dibaca.
        </div>

    @endif

</div>