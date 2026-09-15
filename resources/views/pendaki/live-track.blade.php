<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        Live Tracking - Jalur Bali
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    ></script>

    <script
        src="https://cdn.jsdelivr.net/npm/sweetalert2@11"
    ></script>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#1a382b',
                            orange: '#f06535',
                            cream: '#fbfbfa',
                        }
                    },

                    fontFamily: {
                        sans: [
                            'Plus Jakarta Sans',
                            'sans-serif'
                        ],
                    }
                }
            }
        };
    </script>

    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;

            font-family:
                'Plus Jakarta Sans',
                sans-serif;

            background:
                #fbfbfa;
        }

        body {
            overflow: hidden;
        }

        #map {
            position: fixed;

            top: 64px;
            right: 0;
            bottom: 0;
            left: 0;

            z-index: 1;

            background: #edf1ed;
        }

        .leaflet-control-zoom {
            display: none !important;
        }

        .leaflet-control-attribution {
            font-size: 8px !important;
        }

        /*
        |--------------------------------------------------------------------------
        | USER MARKER
        |--------------------------------------------------------------------------
        */

        .navigation-marker {
            background: transparent !important;
            border: none !important;
        }

        .navigation-arrow-wrapper {
            position: relative;

            display: flex;

            width: 46px;
            height: 46px;

            align-items: center;
            justify-content: center;
        }

        .navigation-pulse {
            position: absolute;

            width: 46px;
            height: 46px;

            border-radius: 999px;

            background:
                rgba(
                    240,
                    101,
                    53,
                    .16
                );

            animation:
                gps-pulse
                2s
                infinite;
        }

        .navigation-arrow {
            position: relative;

            z-index: 2;

            display: flex;

            width: 32px;
            height: 32px;

            align-items: center;
            justify-content: center;

            border:
                3px solid
                white;

            border-radius:
                999px;

            background:
                #f06535;

            color:
                white;

            box-shadow:
                0 4px 15px
                rgba(
                    0,
                    0,
                    0,
                    .25
                );

            transition:
                transform
                .25s ease;
        }

        @keyframes gps-pulse {
            0% {
                transform:
                    scale(.65);

                opacity:
                    1;
            }

            100% {
                transform:
                    scale(1.75);

                opacity:
                    0;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ROUTE
        |--------------------------------------------------------------------------
        */

        .route-marker {
            background:
                transparent !important;

            border:
                none !important;
        }

        .route-dot {
            width: 19px;
            height: 19px;

            border:
                3px solid
                #ffffff;

            border-radius:
                999px;

            background:
                #1a382b;

            box-shadow:
                0 2px 8px
                rgba(
                    0,
                    0,
                    0,
                    .25
                );
        }

        .route-dot.start {
            background:
                #f06535;
        }

        .checkpoint-marker {
            display: flex;

            width: 24px;
            height: 24px;

            align-items: center;
            justify-content: center;

            border:
                3px solid
                white;

            border-radius:
                999px;

            background:
                #1a382b;

            color:
                white;

            font-size:
                9px;

            font-weight:
                800;
        }

        /*
        |--------------------------------------------------------------------------
        | SWEET ALERT
        |--------------------------------------------------------------------------
        */

        .swal2-popup {
            font-family:
                'Plus Jakarta Sans',
                sans-serif !important;

            border-radius:
                18px !important;
        }

        @media (max-width: 640px) {
            .swal2-popup {
                width:
                    calc(
                        100% - 28px
                    ) !important;
            }
        }
    </style>
</head>

<body
    class="
        bg-brand-cream
        text-brand-dark
        antialiased
    "
>

@if(! $trail)

    <div
        class="
            flex
            min-h-screen
            items-center
            justify-center
            px-5
        "
    >
        <div
            class="
                w-full
                max-w-sm
                rounded-2xl
                border
                border-brand-dark/10
                bg-white
                p-7
                text-center
            "
        >
            <h1
                class="
                    text-lg
                    font-bold
                "
            >
                Pilih jalur terlebih dahulu
            </h1>

            <p
                class="
                    mt-2
                    text-sm
                    text-brand-dark/50
                "
            >
                Live Tracking hanya dapat dimulai
                setelah Anda memilih jalur pendakian.
            </p>

            <a
                href="{{ route('pendaki.dashboard') }}"
                class="
                    mt-6
                    flex
                    h-11
                    items-center
                    justify-center
                    rounded-xl
                    bg-brand-dark
                    text-sm
                    font-bold
                    text-white
                "
            >
                Pilih Jalur
            </a>
        </div>
    </div>

@else

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <header
        class="
            fixed
            left-0
            right-0
            top-0
            z-[1000]
            h-16
            border-b
            border-brand-dark/10
            bg-white/95
            backdrop-blur-md
        "
    >
        <div
            class="
                mx-auto
                flex
                h-full
                max-w-3xl
                items-center
                justify-between
                px-4
            "
        >
            <a
                href="{{ route('pendaki.trail.show', $trail) }}"
                class="
                    flex
                    h-10
                    w-10
                    items-center
                    justify-center
                    rounded-lg
                "
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m15 18-6-6 6-6"
                    />
                </svg>
            </a>

            <div
                class="
                    min-w-0
                    flex-1
                    px-3
                    text-center
                "
            >
                <h1
                    class="
                        truncate
                        text-sm
                        font-bold
                    "
                >
                    {{ $trail->name }}
                </h1>

                <div
                    class="
                        mt-0.5
                        flex
                        items-center
                        justify-center
                        gap-1.5
                    "
                >
                    <span
                        id="gpsIndicator"
                        class="
                            h-2
                            w-2
                            rounded-full
                            bg-amber-400
                        "
                    ></span>

                    <span
                        id="gpsStatus"
                        class="
                            text-[10px]
                            font-semibold
                            text-brand-dark/50
                        "
                    >
                        Mencari GPS...
                    </span>
                </div>
            </div>

            <button
                type="button"
                onclick="enableAutoFollow()"
                class="
                    flex
                    h-10
                    w-10
                    items-center
                    justify-center
                    rounded-lg
                    bg-brand-orange/10
                    text-brand-orange
                "
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle
                        cx="12"
                        cy="12"
                        r="3"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 2v3m0 14v3M2 12h3m14 0h3"
                    />
                </svg>
            </button>
        </div>
    </header>


    {{-- MAP --}}

    <div id="map"></div>


    {{-- =====================================================
        DIRECTION PANEL
    ====================================================== --}}

    <div
        class="
            pointer-events-none
            fixed
            left-3
            right-3
            top-[76px]
            z-[900]
            mx-auto
            max-w-md
        "
    >
        <div
            class="
                pointer-events-auto
                flex
                items-center
                gap-4
                rounded-2xl
                border
                border-brand-dark/10
                bg-white/95
                px-4
                py-3
                shadow-xl
                backdrop-blur-md
            "
        >
            <div
                class="
                    flex
                    h-11
                    w-11
                    shrink-0
                    items-center
                    justify-center
                    rounded-xl
                    bg-brand-dark
                    text-white
                "
            >
                <svg
                    id="directionIcon"
                    class="
                        h-6
                        w-6
                        transition-transform
                    "
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2.3"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 19V5m0 0-5 5m5-5 5 5"
                    />
                </svg>
            </div>

            <div
                class="
                    min-w-0
                    flex-1
                "
            >
                <p
                    id="directionText"
                    class="
                        text-sm
                        font-bold
                    "
                >
                    Mencari posisi Anda...
                </p>

                <p
                    id="nextPointText"
                    class="
                        mt-0.5
                        truncate
                        text-xs
                        text-brand-dark/50
                    "
                >
                    Tunggu GPS terhubung
                </p>
            </div>

            <div
                class="
                    shrink-0
                    text-right
                "
            >
                <p
                    id="nextDistance"
                    class="
                        text-lg
                        font-extrabold
                        text-brand-orange
                    "
                >
                    --
                </p>

                <p
                    class="
                        text-[9px]
                        text-brand-dark/40
                    "
                >
                    jarak
                </p>
            </div>
        </div>
    </div>


    {{-- =====================================================
        WARNING
    ====================================================== --}}

    <div
        id="routeWarning"
        class="
            hidden
            fixed
            left-3
            right-3
            top-[166px]
            z-[900]
            mx-auto
            max-w-md
            rounded-xl
            border
            px-4
            py-3
            shadow-lg
            backdrop-blur-md
        "
    >
        <div
            class="
                flex
                items-start
                gap-3
            "
        >
            <svg
                class="
                    mt-0.5
                    h-5
                    w-5
                    shrink-0
                "
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0ZM12 15.75h.008"
                />
            </svg>

            <div>
                <p
                    id="routeWarningTitle"
                    class="
                        text-xs
                        font-bold
                    "
                >
                </p>

                <p
                    id="routeWarningText"
                    class="
                        mt-0.5
                        text-[11px]
                    "
                >
                </p>
            </div>
        </div>
    </div>


    {{-- =====================================================
        BOTTOM PANEL
    ====================================================== --}}

    <div
        class="
            fixed
            bottom-3
            left-3
            right-3
            z-[900]
            mx-auto
            max-w-md
        "
    >
        <div
            class="
                overflow-hidden
                rounded-2xl
                border
                border-brand-dark/10
                bg-white/95
                shadow-2xl
                backdrop-blur-md
            "
        >

            <div
                class="
                    px-4
                    pb-3
                    pt-4
                "
            >
                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-3
                    "
                >
                    <div>
                        <p
                            id="targetLabel"
                            class="
                                text-[10px]
                                font-semibold
                                uppercase
                                text-brand-dark/40
                            "
                        >
                            Pos berikutnya
                        </p>

                        <p
                            id="nextCheckpointName"
                            class="
                                mt-1
                                text-sm
                                font-bold
                            "
                        >
                            Menunggu GPS
                        </p>
                    </div>

                    <p
                        id="nextCheckpointMeta"
                        class="
                            text-right
                            text-xs
                            font-semibold
                            text-brand-dark/60
                        "
                    >
                        --
                    </p>
                </div>


                <div class="mt-4">

                    <div
                        class="
                            mb-1.5
                            flex
                            items-center
                            justify-between
                        "
                    >
                        <span
                            class="
                                text-[10px]
                                text-brand-dark/45
                            "
                        >
                            Progress jalur
                        </span>

                        <span
                            id="progressText"
                            class="
                                text-[10px]
                                font-bold
                            "
                        >
                            0%
                        </span>
                    </div>

                    <div
                        class="
                            h-2
                            overflow-hidden
                            rounded-full
                            bg-brand-dark/10
                        "
                    >
                        <div
                            id="progressBar"
                            class="
                                h-full
                                w-0
                                rounded-full
                                bg-brand-orange
                                transition-all
                                duration-500
                            "
                        ></div>
                    </div>

                    <p
                        id="progressHint"
                        class="
                            mt-1.5
                            hidden
                            text-[10px]
                            text-red-500
                        "
                    >
                    </p>
                </div>
            </div>


            <div
                class="
                    grid
                    grid-cols-3
                    border-y
                    border-brand-dark/10
                "
            >
                <div
                    class="
                        border-r
                        border-brand-dark/10
                        px-2
                        py-3
                        text-center
                    "
                >
                    <p
                        class="
                            text-[9px]
                            text-brand-dark/40
                        "
                    >
                        Elevasi
                    </p>

                    <p
                        class="
                            mt-0.5
                            text-xs
                            font-bold
                        "
                    >
                        <span id="altitudeValue">--</span> m
                    </p>
                </div>

                <div
                    class="
                        border-r
                        border-brand-dark/10
                        px-2
                        py-3
                        text-center
                    "
                >
                    <p
                        class="
                            text-[9px]
                            text-brand-dark/40
                        "
                    >
                        Akurasi
                    </p>

                    <p
                        class="
                            mt-0.5
                            text-xs
                            font-bold
                        "
                    >
                        <span id="accuracyValue">--</span> m
                    </p>
                </div>

                <div
                    class="
                        px-2
                        py-3
                        text-center
                    "
                >
                    <p
                        class="
                            text-[9px]
                            text-brand-dark/40
                        "
                    >
                        Kecepatan
                    </p>

                    <p
                        class="
                            mt-0.5
                            text-xs
                            font-bold
                        "
                    >
                        <span id="speedValue">0</span> km/j
                    </p>
                </div>
            </div>


            {{-- FINISH BUTTON --}}

            <div
                id="finishContainer"
                class="
                    hidden
                    border-b
                    border-brand-dark/10
                    p-3
                "
            >
                <button
                    type="button"
                    onclick="completeHike()"
                    class="
                        flex
                        h-11
                        w-full
                        items-center
                        justify-center
                        gap-2
                        rounded-xl
                        bg-emerald-600
                        text-xs
                        font-bold
                        text-white
                        transition
                        hover:bg-emerald-700
                    "
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m5 12 4 4L19 6"
                        />
                    </svg>

                    Selesaikan Pendakian
                </button>
            </div>


            <div
                class="
                    grid
                    grid-cols-[1fr_auto]
                    gap-2
                    p-3
                "
            >
                <button
                    type="button"
                    onclick="enableAutoFollow()"
                    class="
                        flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-xl
                        bg-brand-dark
                        text-xs
                        font-bold
                        text-white
                    "
                >
                    Ikuti Lokasi Saya
                </button>

                <button
                    type="button"
                    id="sosButton"
                    onclick="sendSOS()"
                    disabled
                    class="
                        h-11
                        rounded-xl
                        bg-red-600
                        px-5
                        text-xs
                        font-bold
                        text-white
                        disabled:opacity-40
                    "
                >
                    SOS
                </button>
            </div>

        </div>
    </div>


<script>
    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */

    const MAX_GPS_ACCURACY_M = 50;

    const MIN_ROUTE_TOLERANCE_M = 30;

    const FINISH_RADIUS_M = 30;

    const REQUIRED_FINISH_READINGS = 3;


    /*
    |--------------------------------------------------------------------------
    | SERVER
    |--------------------------------------------------------------------------
    */

    const trailId =
        @json($trail->id);

    const trailName =
        @json($trail->name);

    const routeCoordinates =
        @json($routeCoordinates);

    const checkpoints =
        @json($checkpoints);

    const startPoint =
        @json($startPoint);

    const finishPoint =
        @json($finishPoint);

    const estimatedTimeHours =
        Number(
            @json(
                $trail->estimated_time_hours
            )
        );

    const locationEndpoint =
        @json(
            route(
                'pendaki.live-track.location'
            )
        );

    const completeEndpoint =
        @json(
            route(
                'pendaki.live-track.complete'
            )
        );

    const sosEndpoint =
        @json(
            route(
                'pendaki.sos'
            )
        );

    const historyUrl =
        @json(
            route(
                'pendaki.riwayat'
            )
        );

    const csrfToken =
        document
            .querySelector(
                'meta[name="csrf-token"]'
            )
            .content;


    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    let map = null;

    let trailPolyline = null;

    let userMarker = null;

    let accuracyCircle = null;

    let watchId = null;

    let autoFollow = true;

    let lastPosition = null;

    let lastStoredAt = 0;

    let batteryLevel = null;

    let routeTotalKm = 0;

    let routeCumulativeKm = [];

    let checkpointNavigationData = [];

    /*
     * INI FIX UTAMA.
     *
     * Progress hanya diperbarui
     * ketika posisi benar-benar valid
     * dan berada dekat jalur.
     */
    let lastTrustedProgress = 0;

    /*
     * Finish harus valid beberapa
     * kali berturut-turut.
     */
    let finishValidReadings = 0;


    /*
    |--------------------------------------------------------------------------
    | TOAST
    |--------------------------------------------------------------------------
    */

    const Toast =
        Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });


    /*
    |--------------------------------------------------------------------------
    | START
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        async function () {
            initializeMap();

            prepareRouteGeometry();

            prepareCheckpoints();

            await initializeBattery();

            startTracking();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | MAP
    |--------------------------------------------------------------------------
    */

    function initializeMap() {
        map =
            L.map(
                'map',
                {
                    zoomControl: false,
                    attributionControl: true,
                }
            );

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }
        )
        .addTo(map);

        if (
            Array.isArray(
                routeCoordinates
            )
            &&
            routeCoordinates.length >= 2
        ) {
            L.polyline(
                routeCoordinates,
                {
                    color: '#ffffff',
                    weight: 9,
                    opacity: .9,
                    lineCap: 'round',
                    lineJoin: 'round',
                }
            )
            .addTo(map);

            trailPolyline =
                L.polyline(
                    routeCoordinates,
                    {
                        color: '#1a382b',
                        weight: 5,
                        opacity: 1,
                        lineCap: 'round',
                        lineJoin: 'round',
                    }
                )
                .addTo(map);

            map.fitBounds(
                trailPolyline
                    .getBounds(),
                {
                    padding: [
                        70,
                        70
                    ]
                }
            );
        } else {
            map.setView(
                [
                    -8.409518,
                    115.188919
                ],
                10
            );
        }

        addStartMarker();

        addCheckpointMarkers();

        addFinishMarker();

        map.on(
            'dragstart',
            function () {
                autoFollow = false;
            }
        );
    }


    function addStartMarker() {
        if (!startPoint) {
            return;
        }

        const icon =
            L.divIcon({
                className:
                    'route-marker',

                html:
                    '<div class="route-dot start"></div>',

                iconSize:
                    [19, 19],

                iconAnchor:
                    [9, 9],
            });

        L.marker(
            startPoint,
            {
                icon
            }
        )
        .addTo(map)
        .bindTooltip(
            'Start'
        );
    }


    function addCheckpointMarkers() {
        checkpoints.forEach(
            function (
                checkpoint,
                index
            ) {
                const icon =
                    L.divIcon({
                        className:
                            'route-marker',

                        html:
                            `
                            <div class="checkpoint-marker">
                                ${index + 1}
                            </div>
                            `,

                        iconSize:
                            [24, 24],

                        iconAnchor:
                            [12, 12],
                    });

                L.marker(
                    [
                        Number(
                            checkpoint.latitude
                        ),

                        Number(
                            checkpoint.longitude
                        )
                    ],
                    {
                        icon
                    }
                )
                .addTo(map)
                .bindTooltip(
                    checkpoint.name
                );
            }
        );
    }


    function addFinishMarker() {
        if (!finishPoint) {
            return;
        }

        const icon =
            L.divIcon({
                className:
                    'route-marker',

                html:
                    '<div class="route-dot"></div>',

                iconSize:
                    [19, 19],

                iconAnchor:
                    [9, 9],
            });

        L.marker(
            finishPoint,
            {
                icon
            }
        )
        .addTo(map)
        .bindTooltip(
            'Puncak'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GPS
    |--------------------------------------------------------------------------
    */

    function startTracking() {
        if (
            !navigator.geolocation
        ) {
            setGpsError(
                'GPS tidak didukung'
            );

            return;
        }

        setGpsSearching();

        watchId =
            navigator
                .geolocation
                .watchPosition(
                    handlePosition,
                    handleGpsError,
                    {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 3000,
                    }
                );
    }


    function handlePosition(
        position
    ) {
        const coords =
            position.coords;

        const previous =
            lastPosition;

        const current = {
            lat:
                Number(
                    coords.latitude
                ),

            lng:
                Number(
                    coords.longitude
                ),

            accuracy:
                Number(
                    coords.accuracy
                    || 999
                ),

            altitude:
                coords.altitude,

            speed:
                coords.speed,

            heading:
                coords.heading,

            timestamp:
                position.timestamp,
        };

        /*
        |--------------------------------------------------------------------------
        | HEADING
        |--------------------------------------------------------------------------
        */

        if (
            current.heading === null
            ||
            !Number.isFinite(
                current.heading
            )
        ) {
            if (previous) {
                current.heading =
                    calculateBearing(
                        previous.lat,
                        previous.lng,
                        current.lat,
                        current.lng
                    );
            } else {
                current.heading = 0;
            }
        }

        lastPosition =
            current;

        setGpsConnected(
            current.accuracy
        );

        updateUserMarker(
            current
        );

        updateGpsStats(
            current
        );

        updateNavigation(
            current
        );

        if (
            autoFollow
        ) {
            map.panTo(
                [
                    current.lat,
                    current.lng
                ],
                {
                    animate: true
                }
            );

            if (
                map.getZoom() < 16
            ) {
                map.setZoom(
                    16
                );
            }
        }

        document
            .getElementById(
                'sosButton'
            )
            .disabled =
                false;

        maybeStoreLocation(
            current
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NAVIGATION
    |--------------------------------------------------------------------------
    */

    function updateNavigation(
        current
    ) {
        if (
            routeCoordinates.length < 2
        ) {
            return;
        }

        const routePosition =
            getRoutePosition(
                current.lat,
                current.lng
            );

        const gpsReliable =
            current.accuracy
            <=
            MAX_GPS_ACCURACY_M;

        const routeTolerance =
            Math.max(
                MIN_ROUTE_TOLERANCE_M,
                current.accuracy
                    * 1.5
            );

        const isOnRoute =
            gpsReliable
            &&
            routePosition.distanceM
            <=
            routeTolerance;

        /*
        |--------------------------------------------------------------------------
        | FIX BUG PROGRESS
        |--------------------------------------------------------------------------
        |
        | Progress hanya berubah jika:
        |
        | - GPS akurat
        | - User dekat dengan jalur
        |
        */

        if (
            isOnRoute
        ) {
            const calculatedProgress =
                Math.min(
                    100,
                    Math.max(
                        0,
                        routePosition.progress
                            *
                            100
                    )
                );

            /*
             * Jangan mundurkan progress karena jitter.
             */
            lastTrustedProgress =
                Math.max(
                    lastTrustedProgress,
                    calculatedProgress
                );

            updateProgressUi(
                lastTrustedProgress,
                false
            );
        } else {
            /*
             * Jangan mengambil progress palsu.
             *
             * Pertahankan progress valid terakhir.
             */
            updateProgressUi(
                lastTrustedProgress,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GPS BURUK
        |--------------------------------------------------------------------------
        */

        if (
            !gpsReliable
        ) {
            showWarning(
                'Akurasi GPS rendah',
                `Akurasi saat ini ±${Math.round(current.accuracy)} meter. Progress ditahan sampai GPS lebih akurat.`,
                'amber'
            );

            hideFinishButton();

            finishValidReadings = 0;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | OFF ROUTE
        |--------------------------------------------------------------------------
        */

        if (
            !isOnRoute
        ) {
            showWarning(
                'Anda berada di luar jalur',
                `Perkiraan jarak dari jalur ${Math.round(routePosition.distanceM)} meter. Progress tidak akan bertambah sampai Anda kembali ke jalur.`,
                'red'
            );

            /*
            |--------------------------------------------------------------------------
            | NAVIGASI KEMBALI KE JALUR
            |--------------------------------------------------------------------------
            */

            const returnBearing =
                calculateBearing(
                    current.lat,
                    current.lng,
                    routePosition.closestLat,
                    routePosition.closestLng
                );

            const compass =
                bearingToCompass(
                    returnBearing
                );

            document
                .getElementById(
                    'directionText'
                )
                .innerText =
                    `Kembali ke jalur ke ${compass}`;

            document
                .getElementById(
                    'nextPointText'
                )
                .innerText =
                    'Menuju titik jalur terdekat';

            document
                .getElementById(
                    'nextDistance'
                )
                .innerText =
                    formatDistance(
                        routePosition.distanceM
                        /
                        1000
                    );

            document
                .getElementById(
                    'targetLabel'
                )
                .innerText =
                    'Status navigasi';

            document
                .getElementById(
                    'nextCheckpointName'
                )
                .innerText =
                    'Kembali ke jalur';

            document
                .getElementById(
                    'nextCheckpointMeta'
                )
                .innerText =
                    `${Math.round(routePosition.distanceM)} m dari jalur`;

            document
                .getElementById(
                    'directionIcon'
                )
                .style
                .transform =
                    `rotate(${returnBearing}deg)`;

            finishValidReadings = 0;

            hideFinishButton();

            return;
        }

        hideWarning();

        /*
        |--------------------------------------------------------------------------
        | NEXT CHECKPOINT
        |--------------------------------------------------------------------------
        */

        const next =
            findNextCheckpoint(
                routePosition.alongKm
            );

        let target;

        if (next) {
            target = next;
        } else {
            target = {
                name:
                    'Puncak',

                latitude:
                    Number(
                        finishPoint[0]
                    ),

                longitude:
                    Number(
                        finishPoint[1]
                    ),

                routeKm:
                    routeTotalKm,
            };
        }

        const distanceKm =
            haversineKm(
                current.lat,
                current.lng,
                Number(
                    target.latitude
                ),
                Number(
                    target.longitude
                )
            );

        const remainingRouteKm =
            Math.max(
                0,
                target.routeKm
                    -
                    routePosition.alongKm
            );

        const bearing =
            calculateBearing(
                current.lat,
                current.lng,
                Number(
                    target.latitude
                ),
                Number(
                    target.longitude
                )
            );

        const compass =
            bearingToCompass(
                bearing
            );

        const eta =
            estimateMinutes(
                remainingRouteKm
            );

        document
            .getElementById(
                'directionText'
            )
            .innerText =
                `Ikuti jalur ke ${compass}`;

        document
            .getElementById(
                'nextPointText'
            )
            .innerText =
                `Menuju ${target.name}`;

        document
            .getElementById(
                'nextDistance'
            )
            .innerText =
                formatDistance(
                    distanceKm
                );

        document
            .getElementById(
                'targetLabel'
            )
            .innerText =
                'Pos berikutnya';

        document
            .getElementById(
                'nextCheckpointName'
            )
            .innerText =
                target.name;

        document
            .getElementById(
                'nextCheckpointMeta'
            )
            .innerText =
                `${formatDistance(distanceKm)} · ± ${eta} menit`;

        document
            .getElementById(
                'directionIcon'
            )
            .style
            .transform =
                `rotate(${bearing}deg)`;

        /*
        |--------------------------------------------------------------------------
        | FINISH VALIDATION
        |--------------------------------------------------------------------------
        */

        updateFinishEligibility(
            current,
            routePosition
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FINISH DETECTION
    |--------------------------------------------------------------------------
    */

    function updateFinishEligibility(
        current,
        routePosition
    ) {
        if (
            !finishPoint
        ) {
            return;
        }

        const distanceToFinishM =
            haversineKm(
                current.lat,
                current.lng,
                Number(
                    finishPoint[0]
                ),
                Number(
                    finishPoint[1]
                )
            )
            *
            1000;

        const gpsReliable =
            current.accuracy
            <=
            MAX_GPS_ACCURACY_M;

        const routeTolerance =
            Math.max(
                MIN_ROUTE_TOLERANCE_M,
                current.accuracy
                    * 1.5
            );

        const valid =
            gpsReliable
            &&
            routePosition.distanceM
                <=
                routeTolerance
            &&
            distanceToFinishM
                <=
                FINISH_RADIUS_M;

        if (valid) {
            finishValidReadings++;
        } else {
            finishValidReadings = 0;

            hideFinishButton();

            return;
        }

        /*
         * Harus valid 3 pembacaan berturut-turut.
         */
        if (
            finishValidReadings
            >=
            REQUIRED_FINISH_READINGS
        ) {
            showFinishButton();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE HIKE
    |--------------------------------------------------------------------------
    */

    async function completeHike() {
        if (
            !lastPosition
        ) {
            return;
        }

        const result =
            await Swal.fire({
                icon:
                    'question',

                title:
                    'Selesaikan pendakian?',

                html:
                    `
                    <div style="font-size:13px;color:#6b7280;line-height:1.6">
                        Sistem mendeteksi Anda telah berada dekat titik akhir jalur.
                        <br><br>
                        Setelah diselesaikan, perjalanan ini akan masuk ke
                        <strong>Riwayat Pendakian</strong>.
                    </div>
                    `,

                showCancelButton:
                    true,

                confirmButtonText:
                    'Ya, Selesaikan',

                cancelButtonText:
                    'Belum',

                confirmButtonColor:
                    '#059669',

                cancelButtonColor:
                    '#6b7280',
            });

        if (
            !result.isConfirmed
        ) {
            return;
        }

        Swal.fire({
            title:
                'Memvalidasi lokasi',

            text:
                'Mohon tunggu...',

            allowOutsideClick:
                false,

            showConfirmButton:
                false,

            didOpen: () => {
                Swal.showLoading();
            },
        });

        try {
            const response =
                await fetch(
                    completeEndpoint,
                    {
                        method:
                            'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken,
                        },

                        body:
                            JSON.stringify({
                                trail_id:
                                    trailId,

                                latitude:
                                    lastPosition.lat,

                                longitude:
                                    lastPosition.lng,

                                accuracy:
                                    lastPosition.accuracy,
                            }),
                    }
                );

            const data =
                await response.json();

            if (
                !response.ok
            ) {
                throw new Error(
                    data.message
                    ||
                    'Pendakian belum dapat diselesaikan.'
                );
            }

            if (
                watchId !== null
            ) {
                navigator
                    .geolocation
                    .clearWatch(
                        watchId
                    );
            }

            await Swal.fire({
                icon:
                    'success',

                title:
                    'Pendakian selesai',

                text:
                    'Perjalanan berhasil disimpan ke riwayat pendakian.',

                confirmButtonText:
                    'Lihat Riwayat',

                confirmButtonColor:
                    '#1a382b',
            });

            window.location.href =
                historyUrl;

        } catch (
            error
        ) {
            Swal.fire({
                icon:
                    'warning',

                title:
                    'Belum dapat diselesaikan',

                text:
                    error.message,

                confirmButtonColor:
                    '#1a382b',
            });
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PROGRESS UI
    |--------------------------------------------------------------------------
    */

    function updateProgressUi(
        progress,
        frozen
    ) {
        const value =
            Math.max(
                0,
                Math.min(
                    100,
                    progress
                )
            );

        document
            .getElementById(
                'progressBar'
            )
            .style
            .width =
                `${value}%`;

        document
            .getElementById(
                'progressText'
            )
            .innerText =
                `${Math.round(value)}%`;

        const hint =
            document
                .getElementById(
                    'progressHint'
                );

        if (frozen) {
            hint
                .classList
                .remove(
                    'hidden'
                );

            hint.innerText =
                'Progress ditahan karena posisi belum berada pada jalur.';
        } else {
            hint
                .classList
                .add(
                    'hidden'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ROUTE GEOMETRY
    |--------------------------------------------------------------------------
    */

    function prepareRouteGeometry() {
        routeCumulativeKm =
            [0];

        routeTotalKm =
            0;

        for (
            let i = 1;
            i < routeCoordinates.length;
            i++
        ) {
            routeTotalKm +=
                haversineKm(
                    routeCoordinates[
                        i - 1
                    ][0],

                    routeCoordinates[
                        i - 1
                    ][1],

                    routeCoordinates[
                        i
                    ][0],

                    routeCoordinates[
                        i
                    ][1]
                );

            routeCumulativeKm
                .push(
                    routeTotalKm
                );
        }
    }


    function prepareCheckpoints() {
        checkpointNavigationData =
            checkpoints
                .map(
                    function (
                        checkpoint
                    ) {
                        const position =
                            getRoutePosition(
                                Number(
                                    checkpoint.latitude
                                ),

                                Number(
                                    checkpoint.longitude
                                )
                            );

                        return {
                            ...checkpoint,

                            routeKm:
                                position.alongKm,
                        };
                    }
                )
                .sort(
                    (
                        a,
                        b
                    ) =>
                        a.routeKm
                        -
                        b.routeKm
                );
    }


    function findNextCheckpoint(
        currentRouteKm
    ) {
        const bufferKm =
            0.020;

        return (
            checkpointNavigationData
                .find(
                    checkpoint =>
                        checkpoint.routeKm
                        >
                        currentRouteKm
                        +
                        bufferKm
                )
            ??
            null
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GET ROUTE POSITION
    |--------------------------------------------------------------------------
    */

    function getRoutePosition(
        lat,
        lng
    ) {
        if (
            routeCoordinates.length < 2
        ) {
            return {
                distanceM:
                    Infinity,

                alongKm:
                    0,

                progress:
                    0,

                closestLat:
                    lat,

                closestLng:
                    lng,
            };
        }

        let bestDistanceKm =
            Infinity;

        let bestAlongKm =
            0;

        let bestClosestLat =
            routeCoordinates[0][0];

        let bestClosestLng =
            routeCoordinates[0][1];

        for (
            let i = 0;
            i
            <
            routeCoordinates.length - 1;
            i++
        ) {
            const a =
                routeCoordinates[i];

            const b =
                routeCoordinates[
                    i + 1
                ];

            const projection =
                projectPointToSegment(
                    lat,
                    lng,
                    a[0],
                    a[1],
                    b[0],
                    b[1]
                );

            if (
                projection.distanceKm
                <
                bestDistanceKm
            ) {
                bestDistanceKm =
                    projection.distanceKm;

                const segmentKm =
                    routeCumulativeKm[
                        i + 1
                    ]
                    -
                    routeCumulativeKm[i];

                bestAlongKm =
                    routeCumulativeKm[i]
                    +
                    (
                        segmentKm
                        *
                        projection.t
                    );

                bestClosestLat =
                    a[0]
                    +
                    (
                        b[0] - a[0]
                    )
                    *
                    projection.t;

                bestClosestLng =
                    a[1]
                    +
                    (
                        b[1] - a[1]
                    )
                    *
                    projection.t;
            }
        }

        return {
            distanceM:
                bestDistanceKm
                *
                1000,

            alongKm:
                bestAlongKm,

            progress:
                routeTotalKm > 0
                    ?
                    bestAlongKm
                    /
                    routeTotalKm
                    :
                    0,

            closestLat:
                bestClosestLat,

            closestLng:
                bestClosestLng,
        };
    }


    function projectPointToSegment(
        pLat,
        pLng,
        aLat,
        aLng,
        bLat,
        bLng
    ) {
        const R =
            6371;

        const refLat =
            degreesToRadians(
                pLat
            );

        const ax =
            degreesToRadians(
                aLng - pLng
            )
            *
            Math.cos(
                refLat
            )
            *
            R;

        const ay =
            degreesToRadians(
                aLat - pLat
            )
            *
            R;

        const bx =
            degreesToRadians(
                bLng - pLng
            )
            *
            Math.cos(
                refLat
            )
            *
            R;

        const by =
            degreesToRadians(
                bLat - pLat
            )
            *
            R;

        const vx =
            bx - ax;

        const vy =
            by - ay;

        const lengthSquared =
            vx * vx
            +
            vy * vy;

        let t = 0;

        if (
            lengthSquared > 0
        ) {
            t =
                -(
                    ax * vx
                    +
                    ay * vy
                )
                /
                lengthSquared;

            t =
                Math.max(
                    0,
                    Math.min(
                        1,
                        t
                    )
                );
        }

        const closestX =
            ax
            +
            vx
            *
            t;

        const closestY =
            ay
            +
            vy
            *
            t;

        return {
            t,

            distanceKm:
                Math.sqrt(
                    closestX
                    *
                    closestX
                    +
                    closestY
                    *
                    closestY
                ),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | USER MARKER
    |--------------------------------------------------------------------------
    */

    function updateUserMarker(
        current
    ) {
        const latLng = [
            current.lat,
            current.lng
        ];

        if (
            !userMarker
        ) {
            const icon =
                L.divIcon({
                    className:
                        'navigation-marker',

                    html:
                        `
                        <div class="navigation-arrow-wrapper">
                            <div class="navigation-pulse"></div>

                            <div
                                id="navigationArrow"
                                class="navigation-arrow"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path
                                        d="M12 2 5.5 20l6.5-3 6.5 3L12 2Z"
                                    />
                                </svg>
                            </div>
                        </div>
                        `,

                    iconSize:
                        [46, 46],

                    iconAnchor:
                        [23, 23],
                });

            userMarker =
                L.marker(
                    latLng,
                    {
                        icon,
                        zIndexOffset:
                            1000,
                    }
                )
                .addTo(
                    map
                );

            accuracyCircle =
                L.circle(
                    latLng,
                    {
                        radius:
                            current.accuracy,

                        color:
                            '#f06535',

                        fillColor:
                            '#f06535',

                        fillOpacity:
                            .08,

                        weight:
                            1,
                    }
                )
                .addTo(
                    map
                );
        } else {
            userMarker
                .setLatLng(
                    latLng
                );

            accuracyCircle
                .setLatLng(
                    latLng
                )
                .setRadius(
                    current.accuracy
                );
        }

        requestAnimationFrame(
            function () {
                const arrow =
                    document
                        .getElementById(
                            'navigationArrow'
                        );

                if (arrow) {
                    arrow
                        .style
                        .transform =
                            `rotate(${current.heading || 0}deg)`;
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | WARNING
    |--------------------------------------------------------------------------
    */

    function showWarning(
        title,
        message,
        type
    ) {
        const container =
            document
                .getElementById(
                    'routeWarning'
                );

        container
            .classList
            .remove(
                'hidden',
                'border-red-200',
                'bg-red-50/95',
                'text-red-700',
                'border-amber-200',
                'bg-amber-50/95',
                'text-amber-700'
            );

        if (
            type === 'red'
        ) {
            container
                .classList
                .add(
                    'border-red-200',
                    'bg-red-50/95',
                    'text-red-700'
                );
        } else {
            container
                .classList
                .add(
                    'border-amber-200',
                    'bg-amber-50/95',
                    'text-amber-700'
                );
        }

        document
            .getElementById(
                'routeWarningTitle'
            )
            .innerText =
                title;

        document
            .getElementById(
                'routeWarningText'
            )
            .innerText =
                message;
    }


    function hideWarning() {
        document
            .getElementById(
                'routeWarning'
            )
            .classList
            .add(
                'hidden'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FINISH BUTTON
    |--------------------------------------------------------------------------
    */

    function showFinishButton() {
        document
            .getElementById(
                'finishContainer'
            )
            .classList
            .remove(
                'hidden'
            );
    }


    function hideFinishButton() {
        document
            .getElementById(
                'finishContainer'
            )
            .classList
            .add(
                'hidden'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GPS UI
    |--------------------------------------------------------------------------
    */

    function updateGpsStats(
        current
    ) {
        document
            .getElementById(
                'accuracyValue'
            )
            .innerText =
                Math.round(
                    current.accuracy
                );

        document
            .getElementById(
                'altitudeValue'
            )
            .innerText =
                current.altitude !== null
                &&
                Number.isFinite(
                    current.altitude
                )
                    ?
                    Math.round(
                        current.altitude
                    )
                    :
                    '--';

        const speed =
            current.speed !== null
            &&
            Number.isFinite(
                current.speed
            )
                ?
                (
                    current.speed
                    *
                    3.6
                )
                .toFixed(1)
                :
                '0';

        document
            .getElementById(
                'speedValue'
            )
            .innerText =
                speed;
    }


    function setGpsSearching() {
        document
            .getElementById(
                'gpsStatus'
            )
            .innerText =
                'Mencari GPS...';
    }


    function setGpsConnected(
        accuracy
    ) {
        document
            .getElementById(
                'gpsStatus'
            )
            .innerText =
                `GPS aktif · ±${Math.round(accuracy)} m`;

        document
            .getElementById(
                'gpsIndicator'
            )
            .className =
                'h-2 w-2 rounded-full bg-emerald-500';
    }


    function setGpsError(
        message
    ) {
        document
            .getElementById(
                'gpsStatus'
            )
            .innerText =
                message;

        document
            .getElementById(
                'gpsIndicator'
            )
            .className =
                'h-2 w-2 rounded-full bg-red-500';
    }


    function handleGpsError(
        error
    ) {
        let message =
            'GPS bermasalah';

        if (
            error.code
            ===
            error.PERMISSION_DENIED
        ) {
            message =
                'Izin lokasi ditolak';
        }

        if (
            error.code
            ===
            error.POSITION_UNAVAILABLE
        ) {
            message =
                'Lokasi tidak tersedia';
        }

        if (
            error.code
            ===
            error.TIMEOUT
        ) {
            message =
                'GPS timeout';
        }

        setGpsError(
            message
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FOLLOW
    |--------------------------------------------------------------------------
    */

    function enableAutoFollow() {
        autoFollow = true;

        if (
            lastPosition
        ) {
            map.flyTo(
                [
                    lastPosition.lat,
                    lastPosition.lng
                ],
                17,
                {
                    duration: .7
                }
            );

            Toast.fire({
                icon:
                    'success',

                title:
                    'Mengikuti lokasi Anda'
            });
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SAVE LOCATION
    |--------------------------------------------------------------------------
    */

    async function maybeStoreLocation(
        current
    ) {
        const now =
            Date.now();

        if (
            now - lastStoredAt
            <
            15000
        ) {
            return;
        }

        lastStoredAt =
            now;

        const routePosition =
            getRoutePosition(
                current.lat,
                current.lng
            );

        const routeTolerance =
            Math.max(
                MIN_ROUTE_TOLERANCE_M,
                current.accuracy
                    *
                    1.5
            );

        let status =
            'tracking';

        if (
            current.accuracy
            >
            MAX_GPS_ACCURACY_M
        ) {
            status =
                'gps_low_accuracy';
        } else if (
            routePosition.distanceM
            >
            routeTolerance
        ) {
            status =
                'off_route';
        }

        try {
            await fetch(
                locationEndpoint,
                {
                    method:
                        'POST',

                    headers: {
                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken,
                    },

                    body:
                        JSON.stringify({
                            trail_id:
                                trailId,

                            latitude:
                                current.lat,

                            longitude:
                                current.lng,

                            altitude_m:
                                current.altitude,

                            battery_level:
                                batteryLevel,

                            status:
                                status,
                        }),
                }
            );
        } catch (
            error
        ) {
            console.warn(
                error
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SOS
    |--------------------------------------------------------------------------
    */

    async function sendSOS() {
        if (
            !lastPosition
        ) {
            Swal.fire({
                icon:
                    'warning',

                title:
                    'Lokasi belum tersedia',

                text:
                    'Tunggu sampai GPS menemukan lokasi Anda.',
            });

            return;
        }

        const confirm =
            await Swal.fire({
                icon:
                    'warning',

                title:
                    'Kirim sinyal SOS?',

                text:
                    'Lokasi terakhir Anda akan dikirim ke petugas.',

                showCancelButton:
                    true,

                confirmButtonText:
                    'Kirim SOS',

                cancelButtonText:
                    'Batal',

                confirmButtonColor:
                    '#dc2626',
            });

        if (
            !confirm.isConfirmed
        ) {
            return;
        }

        try {
            Swal.fire({
                title:
                    'Mengirim SOS',

                allowOutsideClick:
                    false,

                showConfirmButton:
                    false,

                didOpen: () =>
                    Swal.showLoading(),
            });

            const response =
                await fetch(
                    sosEndpoint,
                    {
                        method:
                            'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken,
                        },

                        body:
                            JSON.stringify({
                                trail_id:
                                    trailId,

                                latitude:
                                    lastPosition.lat,

                                longitude:
                                    lastPosition.lng,

                                altitude_m:
                                    lastPosition.altitude,

                                battery_level:
                                    batteryLevel,
                            }),
                    }
                );

            const data =
                await response.json();

            if (
                !response.ok
            ) {
                throw new Error(
                    data.message
                );
            }

            Swal.fire({
                icon:
                    'success',

                title:
                    'SOS berhasil dikirim',

                text:
                    data.message,

                confirmButtonColor:
                    '#1a382b',
            });

        } catch (
            error
        ) {
            Swal.fire({
                icon:
                    'error',

                title:
                    'SOS gagal dikirim',

                text:
                    error.message,
            });
        }
    }


    /*
    |--------------------------------------------------------------------------
    | BATTERY
    |--------------------------------------------------------------------------
    */

    async function initializeBattery() {
        if (
            !navigator.getBattery
        ) {
            return;
        }

        try {
            const battery =
                await navigator
                    .getBattery();

            const update =
                function () {
                    batteryLevel =
                        Math.round(
                            battery.level
                            *
                            100
                        );
                };

            update();

            battery
                .addEventListener(
                    'levelchange',
                    update
                );
        } catch (
            error
        ) {
            console.warn(
                error
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ETA
    |--------------------------------------------------------------------------
    */

    function estimateMinutes(
        remainingKm
    ) {
        if (
            estimatedTimeHours > 0
            &&
            routeTotalKm > 0
        ) {
            return Math.max(
                1,
                Math.round(
                    estimatedTimeHours
                    *
                    60
                    *
                    (
                        remainingKm
                        /
                        routeTotalKm
                    )
                )
            );
        }

        return Math.max(
            1,
            Math.round(
                (
                    remainingKm
                    /
                    3
                )
                *
                60
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GEO UTILS
    |--------------------------------------------------------------------------
    */

    function haversineKm(
        lat1,
        lon1,
        lat2,
        lon2
    ) {
        const R = 6371;

        const dLat =
            degreesToRadians(
                lat2 - lat1
            );

        const dLon =
            degreesToRadians(
                lon2 - lon1
            );

        const a =
            Math.sin(
                dLat / 2
            )
            **
            2
            +
            Math.cos(
                degreesToRadians(
                    lat1
                )
            )
            *
            Math.cos(
                degreesToRadians(
                    lat2
                )
            )
            *
            Math.sin(
                dLon / 2
            )
            **
            2;

        return (
            R
            *
            2
            *
            Math.atan2(
                Math.sqrt(a),
                Math.sqrt(
                    1 - a
                )
            )
        );
    }


    function calculateBearing(
        lat1,
        lon1,
        lat2,
        lon2
    ) {
        const φ1 =
            degreesToRadians(
                lat1
            );

        const φ2 =
            degreesToRadians(
                lat2
            );

        const Δλ =
            degreesToRadians(
                lon2 - lon1
            );

        const y =
            Math.sin(
                Δλ
            )
            *
            Math.cos(
                φ2
            );

        const x =
            Math.cos(
                φ1
            )
            *
            Math.sin(
                φ2
            )
            -
            Math.sin(
                φ1
            )
            *
            Math.cos(
                φ2
            )
            *
            Math.cos(
                Δλ
            );

        return (
            radiansToDegrees(
                Math.atan2(
                    y,
                    x
                )
            )
            +
            360
        )
        %
        360;
    }


    function bearingToCompass(
        bearing
    ) {
        const directions = [
            'Utara',
            'Timur Laut',
            'Timur',
            'Tenggara',
            'Selatan',
            'Barat Daya',
            'Barat',
            'Barat Laut'
        ];

        return directions[
            Math.round(
                bearing
                /
                45
            )
            %
            8
        ];
    }


    function formatDistance(
        km
    ) {
        if (
            km < 1
        ) {
            return (
                Math.round(
                    km
                    *
                    1000
                )
                +
                ' m'
            );
        }

        return (
            km
                .toFixed(1)
                .replace(
                    '.',
                    ','
                )
            +
            ' km'
        );
    }


    function degreesToRadians(
        degrees
    ) {
        return (
            degrees
            *
            Math.PI
            /
            180
        );
    }


    function radiansToDegrees(
        radians
    ) {
        return (
            radians
            *
            180
            /
            Math.PI
        );
    }
</script>

@endif

</body>

</html>