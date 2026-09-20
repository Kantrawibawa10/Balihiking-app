<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="theme-color"
        content="#1a382b"
    >

    <meta
        name="mobile-web-app-capable"
        content="yes"
    >

    <meta
        name="apple-mobile-web-app-capable"
        content="yes"
    >

    <meta
        name="apple-mobile-web-app-status-bar-style"
        content="black-translucent"
    >

    <meta
        name="apple-mobile-web-app-title"
        content="BaliHiking"
    >

    <meta
        name="application-name"
        content="BaliHiking"
    >

    <title>
        Live Tracking - BaliHiking
    </title>


    {{-- ========================================================= --}}
    {{-- PWA --}}
    {{-- ========================================================= --}}

    <link
        rel="manifest"
        href="{{ asset('manifest.webmanifest') }}"
    >

    <link
        rel="icon"
        type="image/png"
        sizes="192x192"
        href="{{ asset('icons/icon-192.png') }}"
    >

    <link
        rel="apple-touch-icon"
        href="{{ asset('icons/icon-192.png') }}"
    >


    {{-- ========================================================= --}}
    {{-- LOCAL ASSETS --}}
    {{-- ========================================================= --}}

    <script
        src="{{ asset('vendor/tailwindcss.js') }}"
    ></script>

    <link
        rel="stylesheet"
        href="{{ asset('vendor/leaflet/leaflet.css') }}"
    >

    <script
        src="{{ asset('vendor/leaflet/leaflet.js') }}"
    ></script>

    <link
        rel="stylesheet"
        href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}"
    >

    <script
        src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"
    ></script>


    {{-- ========================================================= --}}
    {{-- TAILWIND CONFIG --}}
    {{-- ========================================================= --}}

    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        brand: {

                            dark:
                                '#1a382b',

                            orange:
                                '#f06535',

                            cream:
                                '#fbfbfa',

                        }

                    }

                }

            }

        };

    </script>


    {{-- ========================================================= --}}
    {{-- STYLE --}}
    {{-- ========================================================= --}}

    <style>

        * {

            box-sizing:
                border-box;

        }


        html,
        body {

            width:
                100%;

            height:
                100%;

            margin:
                0;

            padding:
                0;

            background:
                #fbfbfa;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

        }


        body {

            overflow:
                hidden;

        }


        button {

            font:
                inherit;

        }



        /*
        |--------------------------------------------------------------------------
        | MAP
        |--------------------------------------------------------------------------
        |
        | Map memenuhi seluruh sisa layar.
        | Bottom sheet hanya overlay kecil di bagian bawah.
        |--------------------------------------------------------------------------
        */

        #map {

            position:
                fixed;

            top:
                64px;

            right:
                0;

            bottom:
                0;

            left:
                0;

            z-index:
                1;

            width:
                100%;

            height:
                calc(100dvh - 64px);

            background:
                #e9eee9;

        }



        /*
        |--------------------------------------------------------------------------
        | TOP NAVIGATION CARD
        |--------------------------------------------------------------------------
        */

        #navigationCard {

            position:
                fixed;

            top:
                76px;

            left:
                50%;

            z-index:
                1100;

            width:
                calc(100% - 28px);

            max-width:
                500px;

            transform:
                translateX(-50%);

            pointer-events:
                auto;

        }



        /*
        |--------------------------------------------------------------------------
        | NETWORK STATUS
        |--------------------------------------------------------------------------
        */

        #networkStatus {

            position:
                fixed;

            top:
                72px;

            left:
                50%;

            z-index:
                2200;

            display:
                none;

            align-items:
                center;

            gap:
                6px;

            padding:
                7px
                12px;

            transform:
                translateX(-50%);

            border-radius:
                999px;

            background:
                #f06535;

            color:
                #ffffff;

            box-shadow:
                0 8px 25px
                rgba(
                    0,
                    0,
                    0,
                    .16
                );

            font-size:
                10px;

            font-weight:
                800;

            white-space:
                nowrap;

        }


        #networkStatus.show {

            display:
                flex;

        }



        /*
        |--------------------------------------------------------------------------
        | BOTTOM SHEET
        |--------------------------------------------------------------------------
        */

        #trackingSheet {

            position:
                fixed;

            right:
                0;

            bottom:
                0;

            left:
                0;

            z-index:
                1400;

            display:
                flex;

            justify-content:
                center;

            padding:
                0
                12px
                calc(
                    12px
                    +
                    env(safe-area-inset-bottom)
                );

            pointer-events:
                none;

        }


        #trackingSheetCard {

            position:
                relative;

            width:
                100%;

            max-width:
                520px;

            max-height:
                min(
                    55dvh,
                    570px
                );

            overflow:
                hidden;

            border:
                1px solid
                rgba(
                    26,
                    56,
                    43,
                    .10
                );

            border-radius:
                22px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .98
                );

            box-shadow:
                0 18px 55px
                rgba(
                    0,
                    0,
                    0,
                    .20
                );

            backdrop-filter:
                blur(18px);

            -webkit-backdrop-filter:
                blur(18px);

            transition:
                max-height
                .28s
                ease,
                transform
                .28s
                ease;

            pointer-events:
                auto;

        }



        /*
        |--------------------------------------------------------------------------
        | BOTTOM SHEET COLLAPSED
        |--------------------------------------------------------------------------
        */

        #trackingSheetCard.sheet-collapsed {

            max-height:
                78px;

        }


        #trackingSheetCard.sheet-collapsed
        #trackingSheetContent {

            display:
                none;

        }


        #trackingSheetCard.sheet-collapsed
        #sheetToggleIcon {

            transform:
                rotate(
                    180deg
                );

        }



        /*
        |--------------------------------------------------------------------------
        | SHEET HEADER
        |--------------------------------------------------------------------------
        */

        #trackingSheetHeader {

            position:
                relative;

            z-index:
                20;

            display:
                flex;

            min-height:
                78px;

            align-items:
                center;

            justify-content:
                space-between;

            gap:
                10px;

            padding:
                15px
                14px
                12px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .98
                );

        }


        .sheet-handle {

            position:
                absolute;

            top:
                7px;

            left:
                50%;

            width:
                38px;

            height:
                4px;

            transform:
                translateX(-50%);

            border-radius:
                999px;

            background:
                rgba(
                    26,
                    56,
                    43,
                    .15
                );

        }


        #sheetHeaderActions {

            display:
                flex;

            flex-shrink:
                0;

            align-items:
                center;

            gap:
                7px;

        }



        /*
        |--------------------------------------------------------------------------
        | MINI FINISH BUTTON
        |--------------------------------------------------------------------------
        */

        #miniFinishButton {

            display:
                flex;

            height:
                36px;

            align-items:
                center;

            justify-content:
                center;

            gap:
                5px;

            padding:
                0
                11px;

            border:
                0;

            border-radius:
                11px;

            background:
                #059669;

            color:
                #ffffff;

            font-size:
                9px;

            font-weight:
                900;

            cursor:
                pointer;

            transition:
                .15s
                ease;

        }


        #miniFinishButton:active {

            transform:
                scale(
                    .96
                );

        }


        #miniFinishButton:disabled {

            opacity:
                .45;

            cursor:
                not-allowed;

        }



        /*
        |--------------------------------------------------------------------------
        | TOGGLE BUTTON
        |--------------------------------------------------------------------------
        */

        #sheetToggleButton {

            display:
                flex;

            width:
                36px;

            height:
                36px;

            flex:
                0 0
                36px;

            align-items:
                center;

            justify-content:
                center;

            border:
                0;

            border-radius:
                11px;

            background:
                rgba(
                    26,
                    56,
                    43,
                    .06
                );

            color:
                #1a382b;

            cursor:
                pointer;

        }


        #sheetToggleIcon {

            width:
                18px;

            height:
                18px;

            transition:
                transform
                .25s
                ease;

        }



        /*
        |--------------------------------------------------------------------------
        | SHEET CONTENT
        |--------------------------------------------------------------------------
        */

        #trackingSheetContent {

            max-height:
                calc(
                    min(
                        55dvh,
                        570px
                    )
                    -
                    78px
                );

            overflow-x:
                hidden;

            overflow-y:
                auto;

            overscroll-behavior:
                contain;

            scrollbar-width:
                thin;

        }



        /*
        |--------------------------------------------------------------------------
        | ROUTE WARNING
        |--------------------------------------------------------------------------
        */

        #routeWarning {

            display:
                none;

        }


        #routeWarning.show {

            display:
                block;

        }



        /*
        |--------------------------------------------------------------------------
        | STICKY FOOTER
        |--------------------------------------------------------------------------
        */

        #actionFooter {

            position:
                sticky;

            bottom:
                0;

            z-index:
                15;

            border-top:
                1px solid
                rgba(
                    26,
                    56,
                    43,
                    .10
                );

            background:
                rgba(
                    255,
                    255,
                    255,
                    .99
                );

            padding:
                12px;

            backdrop-filter:
                blur(16px);

            -webkit-backdrop-filter:
                blur(16px);

        }



        /*
        |--------------------------------------------------------------------------
        | MAP USER MARKER
        |--------------------------------------------------------------------------
        */

        .navigation-marker {

            border:
                none !important;

            background:
                transparent !important;

        }


        .navigation-marker-wrapper {

            position:
                relative;

            display:
                flex;

            width:
                48px;

            height:
                48px;

            align-items:
                center;

            justify-content:
                center;

        }


        .navigation-marker-pulse {

            position:
                absolute;

            width:
                48px;

            height:
                48px;

            border-radius:
                999px;

            background:
                rgba(
                    240,
                    101,
                    53,
                    .18
                );

            animation:
                gpsPulse
                2s
                infinite;

        }


        .navigation-marker-arrow {

            position:
                relative;

            z-index:
                2;

            display:
                flex;

            width:
                34px;

            height:
                34px;

            align-items:
                center;

            justify-content:
                center;

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

        }


        @keyframes gpsPulse {

            0% {

                transform:
                    scale(
                        .65
                    );

                opacity:
                    1;

            }


            100% {

                transform:
                    scale(
                        1.75
                    );

                opacity:
                    0;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | ROUTE MARKERS
        |--------------------------------------------------------------------------
        */

        .route-marker {

            border:
                none !important;

            background:
                transparent !important;

        }


        .route-dot {

            width:
                19px;

            height:
                19px;

            border:
                3px solid
                white;

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


        .route-dot.finish {

            background:
                #059669;

        }


        .checkpoint-dot {

            display:
                flex;

            width:
                25px;

            height:
                25px;

            align-items:
                center;

            justify-content:
                center;

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
                900;

            box-shadow:
                0 2px 8px
                rgba(
                    0,
                    0,
                    0,
                    .25
                );

        }



        /*
        |--------------------------------------------------------------------------
        | LEAFLET
        |--------------------------------------------------------------------------
        */

        .leaflet-control-zoom {

            display:
                none !important;

        }


        .leaflet-control-attribution {

            font-size:
                8px !important;

        }



        /*
        |--------------------------------------------------------------------------
        | SWEET ALERT
        |--------------------------------------------------------------------------
        */

        .swal2-popup {

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif !important;

            border-radius:
                18px !important;

        }



        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (
            max-width:
                640px
        ) {

            #trackingSheet {

                padding-right:
                    8px;

                padding-left:
                    8px;

            }


            #trackingSheetCard {

                max-height:
                    52dvh;

                border-radius:
                    20px;

            }


            #trackingSheetContent {

                max-height:
                    calc(
                        52dvh
                        -
                        78px
                    );

            }


            #navigationCard {

                width:
                    calc(
                        100%
                        -
                        18px
                    );

            }


            .swal2-popup {

                width:
                    calc(
                        100%
                        -
                        28px
                    ) !important;

            }

        }


        @media (
            max-height:
                700px
        ) {

            #trackingSheetCard {

                max-height:
                    46dvh;

            }


            #trackingSheetContent {

                max-height:
                    calc(
                        46dvh
                        -
                        78px
                    );

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

    {{-- ========================================================= --}}
    {{-- BELUM PILIH JALUR --}}
    {{-- ========================================================= --}}

    <main
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
                rounded-3xl
                border
                border-brand-dark/10
                bg-white
                p-7
                text-center
                shadow-sm
            "
        >

            <img
                src="{{ asset('icons/icon-192.png') }}"
                alt="BaliHiking"
                class="
                    mx-auto
                    h-16
                    w-16
                    rounded-2xl
                    object-cover
                "
            >


            <h1
                class="
                    mt-5
                    text-lg
                    font-black
                "
            >
                Pilih Jalur Pendakian
            </h1>


            <p
                class="
                    mt-2
                    text-sm
                    leading-relaxed
                    text-brand-dark/50
                "
            >
                Live Tracking BaliHiking hanya dapat
                digunakan setelah Anda memilih jalur
                pendakian.
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
                Kembali ke Beranda
            </a>

        </div>

    </main>


@else

    {{-- ========================================================= --}}
    {{-- NETWORK STATUS --}}
    {{-- ========================================================= --}}

    <div
        id="networkStatus"
    >

        <span
            class="
                h-2
                w-2
                rounded-full
                bg-white
            "
        ></span>


        <span
            id="networkStatusText"
        >
            Offline
        </span>

    </div>



    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <header
        class="
            fixed
            left-0
            right-0
            top-0
            z-[1500]
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
                    rounded-xl
                    text-brand-dark
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
                        font-black
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
                    rounded-xl
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
                        r="4"
                    />

                    <path
                        d="M12 2v3M12 19v3M2 12h3M19 12h3"
                    />

                </svg>

            </button>

        </div>

    </header>



    {{-- ========================================================= --}}
    {{-- MAP --}}
    {{-- ========================================================= --}}

    <div id="map"></div>



    {{-- ========================================================= --}}
    {{-- TOP NAVIGATION --}}
    {{-- ========================================================= --}}

    <div
        id="navigationCard"
    >

        <div
            class="
                flex
                items-center
                gap-4
                rounded-2xl
                border
                border-brand-dark/10
                bg-white/95
                p-3.5
                shadow-lg
                backdrop-blur-md
            "
        >

            <div
                class="
                    flex
                    h-12
                    w-12
                    shrink-0
                    items-center
                    justify-center
                    rounded-xl
                    bg-brand-dark
                    text-white
                "
            >

                <svg
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m7 17 10-10M9 7h8v8"
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
                    id="navigationInstruction"
                    class="
                        truncate
                        text-sm
                        font-black
                    "
                >
                    Menunggu GPS
                </p>


                <p
                    id="navigationSubtext"
                    class="
                        mt-0.5
                        truncate
                        text-[10px]
                        text-brand-dark/40
                    "
                >
                    BaliHiking sedang menentukan posisi
                </p>

            </div>


            <div
                class="
                    shrink-0
                    text-right
                "
            >

                <p
                    id="navigationDistance"
                    class="
                        text-lg
                        font-black
                        text-brand-orange
                    "
                >
                    -
                </p>


                <p
                    class="
                        text-[8px]
                        text-brand-dark/35
                    "
                >
                    jarak
                </p>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- BOTTOM SHEET --}}
    {{-- ========================================================= --}}

    <div
        id="trackingSheet"
    >

        <section
            id="trackingSheetCard"
            class="sheet-collapsed"
        >

            {{-- ================================================= --}}
            {{-- ALWAYS VISIBLE HEADER --}}
            {{-- ================================================= --}}

            <div
                id="trackingSheetHeader"
            >

                <div
                    class="sheet-handle"
                ></div>


                <div
                    class="
                        min-w-0
                        flex-1
                    "
                >

                    <p
                        class="
                            text-[8px]
                            font-bold
                            uppercase
                            tracking-wider
                            text-brand-dark/35
                        "
                    >
                        Status Pendakian
                    </p>


                    <div
                        class="
                            mt-1
                            flex
                            min-w-0
                            items-center
                            gap-2
                        "
                    >

                        <span
                            id="hikingStatusDot"
                            class="
                                h-2
                                w-2
                                shrink-0
                                rounded-full
                                bg-emerald-500
                            "
                        ></span>


                        <p
                            id="hikingStatusText"
                            class="
                                truncate
                                text-xs
                                font-black
                                text-brand-dark
                            "
                        >
                            Pendakian Berlangsung
                        </p>


                        <span
                            id="hikingStatusBadge"
                            class="
                                shrink-0
                                rounded-full
                                bg-emerald-100
                                px-2
                                py-1
                                text-[8px]
                                font-black
                                text-emerald-700
                            "
                        >
                            AKTIF
                        </span>


                        <span
                            id="routeMiniBadge"
                            class="
                                hidden
                                shrink-0
                                rounded-full
                                bg-red-100
                                px-2
                                py-1
                                text-[8px]
                                font-black
                                text-red-600
                            "
                        >
                            LUAR JALUR
                        </span>


                        <span
                            id="syncMiniBadge"
                            class="
                                hidden
                                shrink-0
                                rounded-full
                                bg-amber-100
                                px-2
                                py-1
                                text-[8px]
                                font-black
                                text-amber-700
                            "
                        >
                            SYNC
                        </span>

                    </div>

                </div>


                <div
                    id="sheetHeaderActions"
                >

                    {{-- COMPLETE ALWAYS AVAILABLE --}}

                    <button
                        type="button"
                        id="miniFinishButton"
                        onclick="completeHike()"
                    >

                        <svg
                            class="
                                h-3.5
                                w-3.5
                            "
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2.5"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m5 12 4 4L19 6"
                            />

                        </svg>


                        <span>
                            Selesai
                        </span>

                    </button>


                    {{-- EXPAND / COLLAPSE --}}

                    <button
                        type="button"
                        id="sheetToggleButton"
                        onclick="toggleTrackingSheet()"
                        aria-label="Buka detail pendakian"
                        aria-expanded="false"
                    >

                        <svg
                            id="sheetToggleIcon"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2.2"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m6 15 6-6 6 6"
                            />

                        </svg>

                    </button>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- EXPANDABLE CONTENT --}}
            {{-- ================================================= --}}

            <div
                id="trackingSheetContent"
            >

                {{-- ============================================= --}}
                {{-- OFF ROUTE WARNING --}}
                {{-- ============================================= --}}

                <div
                    id="routeWarning"
                    class="
                        border-b
                        border-red-100
                        bg-red-50
                        px-4
                        py-3
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
                                h-4
                                w-4
                                shrink-0
                                text-red-600
                            "
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />

                            <path
                                d="M12 8v5M12 16h.01"
                            />

                        </svg>


                        <div>

                            <p
                                class="
                                    text-[10px]
                                    font-black
                                    text-red-700
                                "
                            >
                                Anda berada di luar jalur
                            </p>


                            <p
                                id="routeWarningText"
                                class="
                                    mt-1
                                    text-[9px]
                                    leading-relaxed
                                    text-red-600
                                "
                            >
                                Kembali ke jalur terdekat.
                            </p>

                        </div>

                    </div>

                </div>



                {{-- ============================================= --}}
                {{-- NAVIGATION STATUS --}}
                {{-- ============================================= --}}

                <div
                    class="
                        border-b
                        border-brand-dark/10
                        p-4
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
                                class="
                                    text-[9px]
                                    uppercase
                                    text-brand-dark/35
                                "
                            >
                                Status Navigasi
                            </p>


                            <p
                                id="routeStatusText"
                                class="
                                    mt-1
                                    text-sm
                                    font-black
                                "
                            >
                                Menunggu GPS...
                            </p>

                        </div>


                        <p
                            id="distanceFromRouteText"
                            class="
                                shrink-0
                                text-[10px]
                                font-bold
                                text-brand-dark/45
                            "
                        >
                            -
                        </p>

                    </div>


                    <div
                        class="mt-4"
                    >

                        <div
                            class="
                                flex
                                items-center
                                justify-between
                            "
                        >

                            <span
                                class="
                                    text-[9px]
                                    text-brand-dark/40
                                "
                            >
                                Progress jalur
                            </span>


                            <span
                                id="progressText"
                                class="
                                    text-[9px]
                                    font-black
                                "
                            >
                                0%
                            </span>

                        </div>


                        <div
                            class="
                                mt-2
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
                                    rounded-full
                                    bg-brand-orange
                                    transition-all
                                    duration-300
                                "
                                style="width: 0%"
                            ></div>

                        </div>


                        <p
                            id="progressNotice"
                            class="
                                mt-2
                                text-[9px]
                                text-brand-dark/40
                            "
                        >
                            Progress diperbarui berdasarkan posisi Anda pada jalur.
                        </p>

                    </div>

                </div>



                {{-- ============================================= --}}
                {{-- GPS STATS --}}
                {{-- ============================================= --}}

                <div
                    class="
                        grid
                        grid-cols-3
                        divide-x
                        divide-brand-dark/10
                        border-b
                        border-brand-dark/10
                    "
                >

                    <div
                        class="
                            px-3
                            py-3
                            text-center
                        "
                    >

                        <p
                            class="
                                text-[8px]
                                text-brand-dark/35
                            "
                        >
                            Elevasi
                        </p>


                        <p
                            id="elevationText"
                            class="
                                mt-1
                                text-xs
                                font-black
                            "
                        >
                            -
                        </p>

                    </div>


                    <div
                        class="
                            px-3
                            py-3
                            text-center
                        "
                    >

                        <p
                            class="
                                text-[8px]
                                text-brand-dark/35
                            "
                        >
                            Akurasi
                        </p>


                        <p
                            id="accuracyText"
                            class="
                                mt-1
                                text-xs
                                font-black
                            "
                        >
                            -
                        </p>

                    </div>


                    <div
                        class="
                            px-3
                            py-3
                            text-center
                        "
                    >

                        <p
                            class="
                                text-[8px]
                                text-brand-dark/35
                            "
                        >
                            Kecepatan
                        </p>


                        <p
                            id="speedText"
                            class="
                                mt-1
                                text-xs
                                font-black
                            "
                        >
                            -
                        </p>

                    </div>

                </div>



                {{-- ============================================= --}}
                {{-- LAST LOCATION --}}
                {{-- ============================================= --}}

                <div
                    class="
                        border-b
                        border-brand-dark/10
                        px-4
                        py-3
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

                        <div
                            class="min-w-0"
                        >

                            <p
                                class="
                                    text-[9px]
                                    text-brand-dark/35
                                "
                            >
                                Lokasi terakhir
                            </p>


                            <p
                                id="lastPositionText"
                                class="
                                    mt-1
                                    truncate
                                    text-[10px]
                                    font-semibold
                                    text-brand-dark/60
                                "
                            >
                                Belum tersedia
                            </p>

                        </div>


                        <span
                            id="lastPositionTime"
                            class="
                                shrink-0
                                text-[8px]
                                text-brand-dark/35
                            "
                        >
                            -
                        </span>

                    </div>

                </div>



                {{-- ============================================= --}}
                {{-- OFFLINE SYNC --}}
                {{-- ============================================= --}}

                <div
                    id="queueContainer"
                    class="
                        hidden
                        border-b
                        border-amber-100
                        bg-amber-50
                        px-4
                        py-2.5
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

                        <p
                            id="queueText"
                            class="
                                text-[9px]
                                font-bold
                                text-amber-700
                            "
                        >
                            Data menunggu sinkronisasi
                        </p>


                        <button
                            type="button"
                            onclick="syncOfflineQueue()"
                            class="
                                shrink-0
                                text-[9px]
                                font-black
                                text-brand-dark
                            "
                        >
                            Sinkronkan
                        </button>

                    </div>

                </div>



                {{-- ============================================= --}}
                {{-- COMPLETE INFO --}}
                {{-- ============================================= --}}

                <div
                    id="finishInfo"
                    class="
                        border-b
                        border-brand-dark/10
                        bg-emerald-50
                        px-4
                        py-3
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
                                h-4
                                w-4
                                shrink-0
                                text-emerald-600
                            "
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


                        <p
                            class="
                                text-[9px]
                                leading-relaxed
                                text-emerald-800/80
                            "
                        >
                            Pendakian dapat diselesaikan kapan pun.
                            BaliHiking akan mengambil GPS terbaru dan
                            menyimpan posisi terakhir sebagai lokasi
                            akhir perjalanan.
                        </p>

                    </div>

                </div>



                {{-- ============================================= --}}
                {{-- ACTION FOOTER --}}
                {{-- ============================================= --}}

                <div
                    id="actionFooter"
                >

                    <div
                        id="activeActions"
                    >

                        {{-- COMPLETE --}}

                        <button
                            type="button"
                            id="finishHikeButton"
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
                                font-black
                                text-white
                                transition
                                hover:bg-emerald-700
                                active:scale-[0.99]
                                disabled:opacity-50
                            "
                        >

                            <svg
                                class="
                                    h-4
                                    w-4
                                "
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


                            <span
                                id="finishHikeButtonText"
                            >
                                Selesaikan Pendakian
                            </span>

                        </button>


                        {{-- FOLLOW + SOS --}}

                        <div
                            class="
                                mt-2
                                grid
                                grid-cols-[1fr_auto]
                                gap-2
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
                                    rounded-xl
                                    bg-brand-dark
                                    px-4
                                    text-xs
                                    font-black
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
                                    flex
                                    h-11
                                    min-w-[70px]
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-red-600
                                    px-4
                                    text-xs
                                    font-black
                                    text-white
                                    disabled:opacity-40
                                "
                            >
                                SOS
                            </button>

                        </div>

                    </div>



                    {{-- ========================================= --}}
                    {{-- COMPLETED ACTIONS --}}
                    {{-- ========================================= --}}

                    <div
                        id="completedActions"
                        class="hidden"
                    >

                        <div
                            class="
                                rounded-xl
                                bg-slate-100
                                p-3
                                text-center
                            "
                        >

                            <p
                                class="
                                    text-xs
                                    font-black
                                "
                            >
                                Pendakian Telah Selesai
                            </p>


                            <p
                                id="completedLocationText"
                                class="
                                    mt-1
                                    text-[9px]
                                    leading-relaxed
                                    text-brand-dark/45
                                "
                            >
                                Posisi terakhir telah disimpan.
                            </p>

                        </div>


                        <a
                            id="startNewHikeButton"
                            href="{{
                                route(
                                    'pendaki.live-track',
                                    [
                                        'trail_id' => $trail->id,
                                        'start' => 1,
                                    ]
                                )
                            }}"
                            class="
                                mt-2
                                flex
                                h-11
                                w-full
                                items-center
                                justify-center
                                rounded-xl
                                bg-brand-orange
                                text-xs
                                font-black
                                text-white
                            "
                        >
                            Mulai Pendakian Baru
                        </a>


                        <a
                            href="{{ route('pendaki.riwayat') }}"
                            class="
                                mt-2
                                flex
                                h-10
                                w-full
                                items-center
                                justify-center
                                rounded-xl
                                border
                                border-brand-dark/10
                                bg-white
                                text-[10px]
                                font-bold
                                text-brand-dark
                            "
                        >
                            Lihat Riwayat Pendakian
                        </a>

                    </div>

                </div>

            </div>

        </section>

    </div>



    {{-- ========================================================= --}}
    {{-- APPLICATION --}}
    {{-- ========================================================= --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | CONFIG
        |--------------------------------------------------------------------------
        */

        const MAX_GOOD_ACCURACY_M =
            50;


        const MIN_ROUTE_TOLERANCE_M =
            30;


        const SERVER_STORE_INTERVAL_MS =
            15000;


        const LOCAL_STORE_INTERVAL_MS =
            5000;



        /*
        |--------------------------------------------------------------------------
        | SERVER DATA
        |--------------------------------------------------------------------------
        */

        const trailId =
            Number(
                @json($trail->id)
            );


        const trailName =
            @json(
                $trail->name
            );


        const userRouteId =
            Number(
                @json(
                    $userRoute->id
                    ??
                    null
                )
            );


        const serverSessionStatus =
            @json(
                $sessionStatus
                ??
                'active'
            );


        const startNewRequested =
            @json(
                request()->boolean(
                    'start'
                )
            );


        const routeCoordinates =
            @json(
                $routeCoordinates
            );


        const checkpoints =
            @json(
                $checkpoints
            );


        const startPoint =
            @json(
                $startPoint
            );


        const finishPoint =
            @json(
                $finishPoint
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
        | LOCAL KEYS
        |--------------------------------------------------------------------------
        */

        const SESSION_STATE_KEY =
            `balihiking_hike_state_${userRouteId || trailId}`;


        const SHEET_STATE_KEY =
            `balihiking_bottom_sheet_${trailId}`;



        /*
        |--------------------------------------------------------------------------
        | INDEXED DB
        |--------------------------------------------------------------------------
        */

        const OFFLINE_DB_NAME =
            'balihiking-live-tracking';


        const OFFLINE_DB_VERSION =
            1;


        const QUEUE_STORE =
            'request_queue';


        const TRACK_STORE =
            'track_points';



        /*
        |--------------------------------------------------------------------------
        | STATE
        |--------------------------------------------------------------------------
        */

        let offlineDb =
            null;


        let map =
            null;


        let trailPolyline =
            null;


        let travelledPolyline =
            null;


        let userMarker =
            null;


        let accuracyCircle =
            null;


        let watchId =
            null;


        let lastPosition =
            null;


        let autoFollow =
            true;


        let lastServerStoredAt =
            0;


        let lastLocalStoredAt =
            0;


        let batteryLevel =
            null;


        let routeTotalKm =
            0;


        let routeCumulativeKm =
            [0];


        let lastTrustedProgress =
            0;


        let sessionEnded =
            serverSessionStatus ===
            'completed';


        let sessionPendingSync =
            false;


        let isSyncing =
            false;



        /*
        |--------------------------------------------------------------------------
        | START APPLICATION
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'DOMContentLoaded',
            async function () {

                /*
                |--------------------------------------------------------------------------
                | Jika user memulai perjalanan baru.
                |--------------------------------------------------------------------------
                */

                if (
                    startNewRequested
                ) {

                    try {

                        localStorage.removeItem(
                            SESSION_STATE_KEY
                        );

                    } catch (
                        error
                    ) {}

                }


                buildRouteMetrics();


                initializeMap();


                initializeBottomSheet();


                try {

                    await openDatabase();


                    restoreSessionState();


                    await restoreOfflineTrack();


                    await updateQueueStatus();


                } catch (
                    error
                ) {

                    console.warn(
                        '[BaliHiking] IndexedDB:',
                        error
                    );

                }


                await initializeBattery();


                updateNetworkUi();


                /*
                |--------------------------------------------------------------------------
                | Server sudah selesai.
                |--------------------------------------------------------------------------
                */

                if (
                    serverSessionStatus ===
                    'completed'
                ) {

                    markHikingCompleted(
                        false
                    );


                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Local completion pending.
                |--------------------------------------------------------------------------
                */

                if (
                    sessionEnded
                ) {

                    markHikingCompleted(
                        sessionPendingSync
                    );


                    if (
                        navigator.onLine
                    ) {

                        await syncOfflineQueue();

                    }


                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Sinkron queue terlebih dahulu.
                |--------------------------------------------------------------------------
                */

                if (
                    navigator.onLine
                ) {

                    await syncOfflineQueue();

                }


                if (
                    !sessionEnded
                ) {

                    startGpsTracking();

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | SERVICE WORKER
        |--------------------------------------------------------------------------
        */

        if (
            'serviceWorker'
            in navigator
        ) {

            window.addEventListener(
                'load',
                function () {

                    navigator
                        .serviceWorker
                        .register(
                            '/sw.js'
                        )
                        .catch(
                            function (
                                error
                            ) {

                                console.warn(
                                    '[BaliHiking] Service Worker:',
                                    error
                                );

                            }
                        );

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | NETWORK EVENTS
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'online',
            async function () {

                updateNetworkUi();


                await syncOfflineQueue();

            }
        );


        window.addEventListener(
            'offline',
            function () {

                updateNetworkUi();

            }
        );



        /*
        |--------------------------------------------------------------------------
        | NETWORK UI
        |--------------------------------------------------------------------------
        */

        function updateNetworkUi() {

            const status =
                document.getElementById(
                    'networkStatus'
                );


            const text =
                document.getElementById(
                    'networkStatusText'
                );


            if (
                navigator.onLine
            ) {

                status
                    .classList
                    .remove(
                        'show'
                    );


            } else {


                status
                    .classList
                    .add(
                        'show'
                    );


                text.innerText =
                    'Offline · GPS tetap berjalan';

            }

        }



        /*
        |--------------------------------------------------------------------------
        | BOTTOM SHEET
        |--------------------------------------------------------------------------
        */

        function initializeBottomSheet() {

            let collapsed =
                true;


            try {

                const stored =
                    localStorage.getItem(
                        SHEET_STATE_KEY
                    );


                /*
                |--------------------------------------------------------------------------
                | Jika belum pernah diset:
                | default COLLAPSED agar map terlihat luas.
                |--------------------------------------------------------------------------
                */

                if (
                    stored !==
                    null
                ) {

                    collapsed =
                        stored ===
                        'collapsed';

                }


            } catch (
                error
            ) {}


            setTrackingSheetCollapsed(
                collapsed,
                false
            );

        }


        function toggleTrackingSheet() {

            const sheet =
                document.getElementById(
                    'trackingSheetCard'
                );


            const collapsed =
                sheet
                    .classList
                    .contains(
                        'sheet-collapsed'
                    );


            setTrackingSheetCollapsed(
                !collapsed
            );

        }


        function setTrackingSheetCollapsed(
            collapsed,
            save =
                true
        ) {

            const sheet =
                document.getElementById(
                    'trackingSheetCard'
                );


            const toggle =
                document.getElementById(
                    'sheetToggleButton'
                );


            if (
                collapsed
            ) {

                sheet
                    .classList
                    .add(
                        'sheet-collapsed'
                    );


                toggle.setAttribute(
                    'aria-expanded',
                    'false'
                );


            } else {


                sheet
                    .classList
                    .remove(
                        'sheet-collapsed'
                    );


                toggle.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }


            if (
                save
            ) {

                try {

                    localStorage.setItem(
                        SHEET_STATE_KEY,
                        collapsed
                            ?
                            'collapsed'
                            :
                            'expanded'
                    );


                } catch (
                    error
                ) {}

            }


            setTimeout(
                function () {

                    if (
                        map
                    ) {

                        map.invalidateSize();

                    }

                },
                300
            );

        }



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
                        zoomControl:
                            false
                    }
                );


            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                {
                    maxZoom:
                        19,

                    attribution:
                        '&copy; OpenStreetMap'
                }
            )
            .addTo(
                map
            );


            /*
            |--------------------------------------------------------------------------
            | ROUTE
            |--------------------------------------------------------------------------
            */

            if (
                Array.isArray(
                    routeCoordinates
                )
                &&
                routeCoordinates.length >=
                2
            ) {

                /*
                |--------------------------------------------------------------------------
                | Outline
                |--------------------------------------------------------------------------
                */

                L.polyline(
                    routeCoordinates,
                    {
                        color:
                            '#ffffff',

                        weight:
                            9,

                        opacity:
                            .85
                    }
                )
                .addTo(
                    map
                );


                trailPolyline =
                    L.polyline(
                        routeCoordinates,
                        {
                            color:
                                '#f06535',

                            weight:
                                5,

                            opacity:
                                .95
                        }
                    )
                    .addTo(
                        map
                    );


                /*
                |--------------------------------------------------------------------------
                | Travelled
                |--------------------------------------------------------------------------
                */

                travelledPolyline =
                    L.polyline(
                        [],
                        {
                            color:
                                '#1a382b',

                            weight:
                                4,

                            opacity:
                                .90
                        }
                    )
                    .addTo(
                        map
                    );


                map.fitBounds(
                    trailPolyline.getBounds(),
                    {
                        padding:
                            [
                                55,
                                55
                            ]
                    }
                );


            } else {


                map.setView(
                    [
                        -8.4095,
                        115.1889
                    ],
                    13
                );

            }


            addStartFinishMarkers();


            addCheckpointMarkers();


            /*
            |--------------------------------------------------------------------------
            | Saat user menggeser map:
            | bottom sheet otomatis collapse.
            |--------------------------------------------------------------------------
            */

            map.on(
                'dragstart',
                function () {

                    autoFollow =
                        false;


                    setTrackingSheetCollapsed(
                        true
                    );

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | START / FINISH MARKERS
        |--------------------------------------------------------------------------
        */

        function addStartFinishMarkers() {

            if (
                startPoint
                &&
                startPoint.length >=
                2
            ) {

                const startIcon =
                    L.divIcon({

                        className:
                            'route-marker',

                        html:
                            '<div class="route-dot start"></div>',

                        iconSize:
                            [
                                19,
                                19
                            ],

                        iconAnchor:
                            [
                                9,
                                9
                            ]

                    });


                L.marker(
                    startPoint,
                    {
                        icon:
                            startIcon
                    }
                )
                .addTo(
                    map
                )
                .bindTooltip(
                    'Titik Mulai'
                );

            }


            if (
                finishPoint
                &&
                finishPoint.length >=
                2
            ) {

                const finishIcon =
                    L.divIcon({

                        className:
                            'route-marker',

                        html:
                            '<div class="route-dot finish"></div>',

                        iconSize:
                            [
                                19,
                                19
                            ],

                        iconAnchor:
                            [
                                9,
                                9
                            ]

                    });


                L.marker(
                    finishPoint,
                    {
                        icon:
                            finishIcon
                    }
                )
                .addTo(
                    map
                )
                .bindTooltip(
                    'Titik Akhir'
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | CHECKPOINT MARKERS
        |--------------------------------------------------------------------------
        */

        function addCheckpointMarkers() {

            checkpoints.forEach(
                function (
                    checkpoint,
                    index
                ) {

                    const latitude =
                        Number(
                            checkpoint.latitude
                        );


                    const longitude =
                        Number(
                            checkpoint.longitude
                        );


                    if (
                        !Number.isFinite(
                            latitude
                        )
                        ||
                        !Number.isFinite(
                            longitude
                        )
                    ) {

                        return;

                    }


                    const icon =
                        L.divIcon({

                            className:
                                'route-marker',

                            html:
                                `
                                <div class="checkpoint-dot">
                                    ${index + 1}
                                </div>
                                `,

                            iconSize:
                                [
                                    25,
                                    25
                                ],

                            iconAnchor:
                                [
                                    12,
                                    12
                                ]

                        });


                    L.marker(
                        [
                            latitude,
                            longitude
                        ],
                        {
                            icon:
                                icon
                        }
                    )
                    .addTo(
                        map
                    )
                    .bindPopup(
                        `
                        <strong>
                            ${escapeHtml(
                                checkpoint.name
                                ||
                                `Pos ${index + 1}`
                            )}
                        </strong>
                        <br>
                        <small>
                            ${
                                escapeHtml(
                                    checkpoint.type
                                    ||
                                    'Checkpoint'
                                )
                            }
                        </small>
                        `
                    );

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | ROUTE METRICS
        |--------------------------------------------------------------------------
        */

        function buildRouteMetrics() {

            routeTotalKm =
                0;


            routeCumulativeKm =
                [
                    0
                ];


            if (
                routeCoordinates.length <
                2
            ) {

                return;

            }


            for (
                let i = 1;
                i < routeCoordinates.length;
                i++
            ) {

                routeTotalKm +=
                    haversineKm(
                        Number(
                            routeCoordinates[
                                i - 1
                            ][0]
                        ),

                        Number(
                            routeCoordinates[
                                i - 1
                            ][1]
                        ),

                        Number(
                            routeCoordinates[
                                i
                            ][0]
                        ),

                        Number(
                            routeCoordinates[
                                i
                            ][1]
                        )
                    );


                routeCumulativeKm.push(
                    routeTotalKm
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | INDEXED DB
        |--------------------------------------------------------------------------
        */

        function openDatabase() {

            return new Promise(
                function (
                    resolve,
                    reject
                ) {

                    if (
                        offlineDb
                    ) {

                        resolve(
                            offlineDb
                        );


                        return;

                    }


                    const request =
                        indexedDB.open(
                            OFFLINE_DB_NAME,
                            OFFLINE_DB_VERSION
                        );


                    request.onupgradeneeded =
                        function (
                            event
                        ) {

                            const database =
                                event.target.result;


                            if (
                                !database
                                    .objectStoreNames
                                    .contains(
                                        QUEUE_STORE
                                    )
                            ) {

                                database
                                    .createObjectStore(
                                        QUEUE_STORE,
                                        {
                                            keyPath:
                                                'id',

                                            autoIncrement:
                                                true
                                        }
                                    );

                            }


                            if (
                                !database
                                    .objectStoreNames
                                    .contains(
                                        TRACK_STORE
                                    )
                            ) {

                                database
                                    .createObjectStore(
                                        TRACK_STORE,
                                        {
                                            keyPath:
                                                'id',

                                            autoIncrement:
                                                true
                                        }
                                    );

                            }

                        };


                    request.onsuccess =
                        function (
                            event
                        ) {

                            offlineDb =
                                event.target.result;


                            resolve(
                                offlineDb
                            );

                        };


                    request.onerror =
                        function () {

                            reject(
                                request.error
                            );

                        };

                }
            );

        }


        function dbAdd(
            storeName,
            value
        ) {

            return new Promise(
                function (
                    resolve,
                    reject
                ) {

                    const transaction =
                        offlineDb.transaction(
                            storeName,
                            'readwrite'
                        );


                    const request =
                        transaction
                            .objectStore(
                                storeName
                            )
                            .add(
                                value
                            );


                    request.onsuccess =
                        function () {

                            resolve(
                                request.result
                            );

                        };


                    request.onerror =
                        function () {

                            reject(
                                request.error
                            );

                        };

                }
            );

        }


        function dbGetAll(
            storeName
        ) {

            return new Promise(
                function (
                    resolve,
                    reject
                ) {

                    const transaction =
                        offlineDb.transaction(
                            storeName,
                            'readonly'
                        );


                    const request =
                        transaction
                            .objectStore(
                                storeName
                            )
                            .getAll();


                    request.onsuccess =
                        function () {

                            resolve(
                                request.result
                                ||
                                []
                            );

                        };


                    request.onerror =
                        function () {

                            reject(
                                request.error
                            );

                        };

                }
            );

        }


        function dbDelete(
            storeName,
            id
        ) {

            return new Promise(
                function (
                    resolve,
                    reject
                ) {

                    const transaction =
                        offlineDb.transaction(
                            storeName,
                            'readwrite'
                        );


                    const request =
                        transaction
                            .objectStore(
                                storeName
                            )
                            .delete(
                                id
                            );


                    request.onsuccess =
                        function () {

                            resolve();

                        };


                    request.onerror =
                        function () {

                            reject(
                                request.error
                            );

                        };

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | LOCAL SESSION STATE
        |--------------------------------------------------------------------------
        */

        function saveSessionState(
            completed,
            pendingSync
        ) {

            try {

                localStorage.setItem(
                    SESSION_STATE_KEY,
                    JSON.stringify({

                        completed:
                            Boolean(
                                completed
                            ),

                        pending_sync:
                            Boolean(
                                pendingSync
                            ),

                        user_route_id:
                            userRouteId,

                        updated_at:
                            Date.now()

                    })
                );


            } catch (
                error
            ) {}

        }


        function restoreSessionState() {

            if (
                startNewRequested
            ) {

                return;

            }


            try {

                const raw =
                    localStorage.getItem(
                        SESSION_STATE_KEY
                    );


                if (
                    !raw
                ) {

                    return;

                }


                const state =
                    JSON.parse(
                        raw
                    );


                if (
                    serverSessionStatus ===
                    'completed'
                ) {

                    sessionEnded =
                        true;

                    sessionPendingSync =
                        false;


                    return;

                }


                if (
                    state.completed
                    &&
                    state.pending_sync
                ) {

                    sessionEnded =
                        true;

                    sessionPendingSync =
                        true;

                }


            } catch (
                error
            ) {}

        }



        /*
        |--------------------------------------------------------------------------
        | RESTORE TRACK
        |--------------------------------------------------------------------------
        */

        async function restoreOfflineTrack() {

            const points =
                await dbGetAll(
                    TRACK_STORE
                );


            const currentPoints =
                points
                    .filter(
                        function (
                            point
                        ) {

                            if (
                                Number(
                                    point.trail_id
                                )
                                !==
                                trailId
                            ) {

                                return false;

                            }


                            if (
                                point.user_route_id
                                &&
                                userRouteId
                            ) {

                                return Number(
                                    point.user_route_id
                                )
                                ===
                                userRouteId;

                            }


                            return true;

                        }
                    )
                    .sort(
                        function (
                            a,
                            b
                        ) {

                            return Number(
                                a.recorded_at
                            )
                            -
                            Number(
                                b.recorded_at
                            );

                        }
                    );


            if (
                currentPoints.length ===
                0
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Travel path.
            |--------------------------------------------------------------------------
            */

            if (
                travelledPolyline
            ) {

                travelledPolyline
                    .setLatLngs(
                        currentPoints.map(
                            function (
                                point
                            ) {

                                return [
                                    Number(
                                        point.latitude
                                    ),

                                    Number(
                                        point.longitude
                                    )
                                ];

                            }
                        )
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | Last point.
            |--------------------------------------------------------------------------
            */

            const last =
                currentPoints[
                    currentPoints.length - 1
                ];


            lastPosition = {

                lat:
                    Number(
                        last.latitude
                    ),

                lng:
                    Number(
                        last.longitude
                    ),

                accuracy:
                    Number(
                        last.accuracy
                        ||
                        999
                    ),

                altitude:
                    last.altitude
                    ??
                    null,

                speed:
                    last.speed
                    ??
                    null,

                heading:
                    last.heading
                    ??
                    null,

                timestamp:
                    Number(
                        last.recorded_at
                    )
                    ||
                    Date.now()

            };


            lastTrustedProgress =
                Number(
                    last.progress
                )
                ||
                0;


            updateLastPositionUi(
                lastPosition
            );


            updateProgressUi(
                lastTrustedProgress,
                false
            );

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
                    await navigator.getBattery();


                const updateBattery =
                    function () {

                        batteryLevel =
                            Math.round(
                                battery.level
                                *
                                100
                            );

                    };


                updateBattery();


                battery.addEventListener(
                    'levelchange',
                    updateBattery
                );


            } catch (
                error
            ) {}

        }



        /*
        |--------------------------------------------------------------------------
        | GPS TRACKING
        |--------------------------------------------------------------------------
        */

        function startGpsTracking() {

            if (
                sessionEnded
            ) {

                return;

            }


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
                        handleGpsPosition,
                        handleGpsError,
                        {
                            enableHighAccuracy:
                                true,

                            timeout:
                                15000,

                            maximumAge:
                                3000
                        }
                    );

        }


        function stopGpsTracking() {

            if (
                watchId !==
                null
            ) {

                navigator
                    .geolocation
                    .clearWatch(
                        watchId
                    );


                watchId =
                    null;

            }


            autoFollow =
                false;

        }



        /*
        |--------------------------------------------------------------------------
        | GPS POSITION
        |--------------------------------------------------------------------------
        */

        async function handleGpsPosition(
            position
        ) {

            if (
                sessionEnded
            ) {

                return;

            }


            const current = {

                lat:
                    Number(
                        position.coords.latitude
                    ),

                lng:
                    Number(
                        position.coords.longitude
                    ),

                accuracy:
                    Number(
                        position.coords.accuracy
                        ||
                        999
                    ),

                altitude:
                    position.coords.altitude,

                speed:
                    position.coords.speed,

                heading:
                    position.coords.heading,

                timestamp:
                    Number(
                        position.timestamp
                    )
                    ||
                    Date.now()

            };


            lastPosition =
                current;


            updateGpsUi(
                current
            );


            updateLastPositionUi(
                current
            );


            updateUserMarker(
                current
            );


            updateNavigation(
                current
            );


            document
                .getElementById(
                    'sosButton'
                )
                .disabled =
                    false;


            if (
                autoFollow
            ) {

                map.panTo(
                    [
                        current.lat,
                        current.lng
                    ],
                    {
                        animate:
                            true
                    }
                );


                if (
                    map.getZoom()
                    <
                    16
                ) {

                    map.setZoom(
                        16
                    );

                }

            }


            await maybeStoreLocalPosition(
                current
            );


            await maybeStoreServerLocation(
                current
            );

        }



        /*
        |--------------------------------------------------------------------------
        | GPS UI
        |--------------------------------------------------------------------------
        */

        function setGpsSearching() {

            document
                .getElementById(
                    'gpsStatus'
                )
                .innerText =
                    'Mencari GPS...';


            document
                .getElementById(
                    'gpsIndicator'
                )
                .className =
                    'h-2 w-2 rounded-full bg-amber-400';

        }


        function updateGpsUi(
            current
        ) {

            const good =
                current.accuracy
                <=
                MAX_GOOD_ACCURACY_M;


            document
                .getElementById(
                    'gpsIndicator'
                )
                .className =
                    good
                        ?
                        'h-2 w-2 rounded-full bg-emerald-500'
                        :
                        'h-2 w-2 rounded-full bg-amber-400';


            document
                .getElementById(
                    'gpsStatus'
                )
                .innerText =
                    navigator.onLine
                        ?
                        `GPS aktif · ±${Math.round(current.accuracy)} m`
                        :
                        `GPS offline aktif · ±${Math.round(current.accuracy)} m`;


            document
                .getElementById(
                    'accuracyText'
                )
                .innerText =
                    `${Math.round(current.accuracy)} m`;


            document
                .getElementById(
                    'elevationText'
                )
                .innerText =
                    current.altitude !==
                    null
                    &&
                    Number.isFinite(
                        Number(
                            current.altitude
                        )
                    )

                        ?

                        `${Math.round(current.altitude)} m`

                        :

                        '-';


            const speedKmh =
                current.speed !==
                null
                &&
                Number.isFinite(
                    Number(
                        current.speed
                    )
                )

                    ?

                    Number(
                        current.speed
                    )
                    *
                    3.6

                    :

                    0;


            document
                .getElementById(
                    'speedText'
                )
                .innerText =
                    `${speedKmh.toFixed(1)} km/j`;

        }


        function setGpsError(
            message
        ) {

            document
                .getElementById(
                    'gpsIndicator'
                )
                .className =
                    'h-2 w-2 rounded-full bg-red-500';


            document
                .getElementById(
                    'gpsStatus'
                )
                .innerText =
                    message;

        }


        function handleGpsError(
            error
        ) {

            let message =
                'GPS tidak tersedia';


            if (
                error.code ===
                1
            ) {

                message =
                    'Izin lokasi ditolak';

            }


            if (
                error.code ===
                2
            ) {

                message =
                    'Lokasi tidak tersedia';

            }


            if (
                error.code ===
                3
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
        | LAST POSITION UI
        |--------------------------------------------------------------------------
        */

        function updateLastPositionUi(
            position
        ) {

            document
                .getElementById(
                    'lastPositionText'
                )
                .innerText =
                    `${
                        position.lat.toFixed(6)
                    }, ${
                        position.lng.toFixed(6)
                    } · ±${
                        Math.round(
                            position.accuracy
                        )
                    } m`;


            document
                .getElementById(
                    'lastPositionTime'
                )
                .innerText =
                    new Date(
                        position.timestamp
                    )
                    .toLocaleTimeString(
                        'id-ID',
                        {
                            hour:
                                '2-digit',

                            minute:
                                '2-digit',

                            second:
                                '2-digit'
                        }
                    );

        }



        /*
        |--------------------------------------------------------------------------
        | USER MARKER
        |--------------------------------------------------------------------------
        */

        function updateUserMarker(
            current
        ) {

            if (
                !userMarker
            ) {

                const icon =
                    L.divIcon({

                        className:
                            'navigation-marker',

                        html:
                            `
                            <div
                                class="navigation-marker-wrapper"
                            >

                                <div
                                    class="navigation-marker-pulse"
                                ></div>


                                <div
                                    class="navigation-marker-arrow"
                                >

                                    <svg
                                        width="17"
                                        height="17"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m12 3 7 16-7-4-7 4 7-16Z"
                                        />

                                    </svg>

                                </div>

                            </div>
                            `,

                        iconSize:
                            [
                                48,
                                48
                            ],

                        iconAnchor:
                            [
                                24,
                                24
                            ]

                    });


                userMarker =
                    L.marker(
                        [
                            current.lat,
                            current.lng
                        ],
                        {
                            icon:
                                icon,

                            zIndexOffset:
                                1000
                        }
                    )
                    .addTo(
                        map
                    );


                accuracyCircle =
                    L.circle(
                        [
                            current.lat,
                            current.lng
                        ],
                        {
                            radius:
                                current.accuracy,

                            color:
                                '#f06535',

                            weight:
                                1,

                            opacity:
                                .25,

                            fillColor:
                                '#f06535',

                            fillOpacity:
                                .05
                        }
                    )
                    .addTo(
                        map
                    );


            } else {


                userMarker
                    .setLatLng(
                        [
                            current.lat,
                            current.lng
                        ]
                    );


                accuracyCircle
                    .setLatLng(
                        [
                            current.lat,
                            current.lng
                        ]
                    )
                    .setRadius(
                        current.accuracy
                    );

            }

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
                routeCoordinates.length
                <
                2
            ) {

                return;

            }


            const routePosition =
                getRoutePosition(
                    current.lat,
                    current.lng
                );


            const tolerance =
                Math.max(
                    MIN_ROUTE_TOLERANCE_M,

                    current.accuracy
                    *
                    1.5
                );


            const isOnRoute =
                routePosition.distanceM
                <=
                tolerance;


            const warning =
                document.getElementById(
                    'routeWarning'
                );


            const miniBadge =
                document.getElementById(
                    'routeMiniBadge'
                );



            /*
            |--------------------------------------------------------------------------
            | OFF ROUTE
            |--------------------------------------------------------------------------
            */

            if (
                !isOnRoute
            ) {

                warning
                    .classList
                    .add(
                        'show'
                    );


                miniBadge
                    .classList
                    .remove(
                        'hidden'
                    );


                document
                    .getElementById(
                        'routeWarningText'
                    )
                    .innerText =
                        `Perkiraan jarak dari jalur ${Math.round(routePosition.distanceM)} meter. Progress ditahan sampai Anda kembali ke jalur.`;


                document
                    .getElementById(
                        'routeStatusText'
                    )
                    .innerText =
                        'Kembali ke jalur';


                document
                    .getElementById(
                        'distanceFromRouteText'
                    )
                    .innerText =
                        `${Math.round(routePosition.distanceM)} m dari jalur`;


                document
                    .getElementById(
                        'navigationInstruction'
                    )
                    .innerText =
                        'Kembali ke jalur';


                document
                    .getElementById(
                        'navigationSubtext'
                    )
                    .innerText =
                        'Menuju titik jalur terdekat';


                document
                    .getElementById(
                        'navigationDistance'
                    )
                    .innerText =
                        formatDistanceMeters(
                            routePosition.distanceM
                        );


                updateProgressUi(
                    lastTrustedProgress,
                    true
                );


            } else {


                warning
                    .classList
                    .remove(
                        'show'
                    );


                miniBadge
                    .classList
                    .add(
                        'hidden'
                    );


                /*
                |--------------------------------------------------------------------------
                | Trusted progress.
                |--------------------------------------------------------------------------
                */

                lastTrustedProgress =
                    Math.max(
                        lastTrustedProgress,
                        routePosition.progress
                    );


                updateProgressUi(
                    lastTrustedProgress,
                    false
                );


                document
                    .getElementById(
                        'routeStatusText'
                    )
                    .innerText =
                        'Anda berada di jalur';


                document
                    .getElementById(
                        'distanceFromRouteText'
                    )
                    .innerText =
                        `${Math.round(routePosition.distanceM)} m`;


                /*
                |--------------------------------------------------------------------------
                | Navigation target.
                |--------------------------------------------------------------------------
                */

                const targetIndex =
                    Math.min(
                        routeCoordinates.length - 1,

                        routePosition.segmentIndex + 3
                    );


                const target =
                    routeCoordinates[
                        targetIndex
                    ];


                const distanceMeters =
                    haversineKm(
                        current.lat,
                        current.lng,

                        Number(
                            target[0]
                        ),

                        Number(
                            target[1]
                        )
                    )
                    *
                    1000;


                document
                    .getElementById(
                        'navigationInstruction'
                    )
                    .innerText =
                        'Ikuti jalur';


                document
                    .getElementById(
                        'navigationSubtext'
                    )
                    .innerText =
                        'Tetap berada pada jalur pendakian';


                document
                    .getElementById(
                        'navigationDistance'
                    )
                    .innerText =
                        formatDistanceMeters(
                            distanceMeters
                        );

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
                        Number(
                            progress
                        )
                        ||
                        0
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


            const notice =
                document.getElementById(
                    'progressNotice'
                );


            if (
                frozen
            ) {

                notice.innerText =
                    'Progress ditahan karena posisi belum berada pada jalur.';


                notice.className =
                    'mt-2 text-[9px] text-red-500';


            } else {


                notice.innerText =
                    'Progress diperbarui berdasarkan posisi Anda pada jalur.';


                notice.className =
                    'mt-2 text-[9px] text-brand-dark/40';

            }

        }



        /*
        |--------------------------------------------------------------------------
        | LOCAL TRACK STORAGE
        |--------------------------------------------------------------------------
        */

        async function maybeStoreLocalPosition(
            current
        ) {

            if (
                !offlineDb
            ) {

                return;

            }


            const now =
                Date.now();


            if (
                now
                -
                lastLocalStoredAt
                <
                LOCAL_STORE_INTERVAL_MS
            ) {

                return;

            }


            lastLocalStoredAt =
                now;


            try {

                await dbAdd(
                    TRACK_STORE,
                    {
                        trail_id:
                            trailId,

                        user_route_id:
                            userRouteId,

                        latitude:
                            current.lat,

                        longitude:
                            current.lng,

                        altitude:
                            current.altitude,

                        accuracy:
                            current.accuracy,

                        speed:
                            current.speed,

                        heading:
                            current.heading,

                        progress:
                            lastTrustedProgress,

                        recorded_at:
                            now
                    }
                );


                if (
                    travelledPolyline
                ) {

                    travelledPolyline
                        .addLatLng(
                            [
                                current.lat,
                                current.lng
                            ]
                        );

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking] Local position:',
                    error
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | SERVER LOCATION
        |--------------------------------------------------------------------------
        */

        async function maybeStoreServerLocation(
            current
        ) {

            if (
                sessionEnded
            ) {

                return;

            }


            const now =
                Date.now();


            if (
                now
                -
                lastServerStoredAt
                <
                SERVER_STORE_INTERVAL_MS
            ) {

                return;

            }


            lastServerStoredAt =
                now;


            const routePosition =
                getRoutePosition(
                    current.lat,
                    current.lng
                );


            const tolerance =
                Math.max(
                    MIN_ROUTE_TOLERANCE_M,

                    current.accuracy
                    *
                    1.5
                );


            let status =
                'tracking';


            if (
                routePosition.distanceM
                >
                tolerance
            ) {

                status =
                    'off_route';

            }


            await sendOrQueue(
                'location',
                locationEndpoint,
                {
                    trail_id:
                        trailId,

                    user_route_id:
                        userRouteId,

                    latitude:
                        current.lat,

                    longitude:
                        current.lng,

                    altitude_m:
                        current.altitude,

                    battery_level:
                        batteryLevel,

                    status:
                        status
                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | POST JSON
        |--------------------------------------------------------------------------
        */

        function postJson(
            endpoint,
            payload
        ) {

            return fetch(
                endpoint,
                {
                    method:
                        'POST',

                    credentials:
                        'same-origin',

                    headers: {

                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken

                    },

                    body:
                        JSON.stringify(
                            payload
                        )
                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | SEND OR QUEUE
        |--------------------------------------------------------------------------
        */

        async function sendOrQueue(
            kind,
            endpoint,
            payload
        ) {

            if (
                !navigator.onLine
            ) {

                await queueRequest(
                    kind,
                    endpoint,
                    payload
                );


                return {
                    queued:
                        true,

                    response:
                        null
                };

            }


            try {

                const response =
                    await postJson(
                        endpoint,
                        payload
                    );


                if (
                    response.ok
                ) {

                    return {
                        queued:
                            false,

                        response:
                            response
                    };

                }


                if (
                    response.status
                    >=
                    500
                    ||
                    response.status
                    ===
                    429
                ) {

                    await queueRequest(
                        kind,
                        endpoint,
                        payload
                    );


                    return {
                        queued:
                            true,

                        response:
                            response
                    };

                }


                return {
                    queued:
                        false,

                    response:
                        response
                };


            } catch (
                error
            ) {

                await queueRequest(
                    kind,
                    endpoint,
                    payload
                );


                return {
                    queued:
                        true,

                    response:
                        null
                };

            }

        }



        /*
        |--------------------------------------------------------------------------
        | QUEUE REQUEST
        |--------------------------------------------------------------------------
        */

        async function queueRequest(
            kind,
            endpoint,
            payload
        ) {

            if (
                !offlineDb
            ) {

                await openDatabase();

            }


            await dbAdd(
                QUEUE_STORE,
                {
                    kind:
                        kind,

                    endpoint:
                        endpoint,

                    trail_id:
                        trailId,

                    user_route_id:
                        userRouteId,

                    payload:
                        payload,

                    created_at:
                        Date.now()
                }
            );


            await updateQueueStatus();

        }



        /*
        |--------------------------------------------------------------------------
        | QUEUE STATUS
        |--------------------------------------------------------------------------
        */

        async function updateQueueStatus() {

            if (
                !offlineDb
            ) {

                return;

            }


            const queue =
                await dbGetAll(
                    QUEUE_STORE
                );


            const currentQueue =
                queue.filter(
                    function (
                        item
                    ) {

                        if (
                            item.user_route_id
                        ) {

                            return Number(
                                item.user_route_id
                            )
                            ===
                            userRouteId;

                        }


                        return Number(
                            item.trail_id
                        )
                        ===
                        trailId;

                    }
                );


            const container =
                document.getElementById(
                    'queueContainer'
                );


            const miniBadge =
                document.getElementById(
                    'syncMiniBadge'
                );


            if (
                currentQueue.length ===
                0
            ) {

                container
                    .classList
                    .add(
                        'hidden'
                    );


                miniBadge
                    .classList
                    .add(
                        'hidden'
                    );


                return;

            }


            container
                .classList
                .remove(
                    'hidden'
                );


            miniBadge
                .classList
                .remove(
                    'hidden'
                );


            miniBadge.innerText =
                `${currentQueue.length} SYNC`;


            document
                .getElementById(
                    'queueText'
                )
                .innerText =
                    `${currentQueue.length} data menunggu sinkronisasi`;

        }



        /*
        |--------------------------------------------------------------------------
        | SYNC QUEUE
        |--------------------------------------------------------------------------
        */

        async function syncOfflineQueue() {

            if (
                !navigator.onLine
                ||
                !offlineDb
                ||
                isSyncing
            ) {

                return;

            }


            isSyncing =
                true;


            try {

                const queue =
                    (
                        await dbGetAll(
                            QUEUE_STORE
                        )
                    )
                    .sort(
                        function (
                            a,
                            b
                        ) {

                            return Number(
                                a.created_at
                            )
                            -
                            Number(
                                b.created_at
                            );

                        }
                    );


                for (
                    const item
                    of queue
                ) {

                    try {

                        const response =
                            await postJson(
                                item.endpoint,
                                item.payload
                            );


                        if (
                            response.ok
                        ) {

                            await dbDelete(
                                QUEUE_STORE,
                                item.id
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | Completion berhasil disinkron.
                            |--------------------------------------------------------------------------
                            */

                            if (
                                item.kind ===
                                'complete'
                                &&
                                (
                                    Number(
                                        item.user_route_id
                                    )
                                    ===
                                    userRouteId
                                    ||
                                    Number(
                                        item.trail_id
                                    )
                                    ===
                                    trailId
                                )
                            ) {

                                sessionEnded =
                                    true;


                                sessionPendingSync =
                                    false;


                                saveSessionState(
                                    true,
                                    false
                                );


                                markHikingCompleted(
                                    false
                                );

                            }


                            continue;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Server/session error.
                        |--------------------------------------------------------------------------
                        */

                        if (
                            response.status
                            ===
                            401
                            ||
                            response.status
                            ===
                            419
                            ||
                            response.status
                            >=
                            500
                            ||
                            response.status
                            ===
                            429
                        ) {

                            break;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Location biasa yang invalid boleh dilewati.
                        | Complete dan SOS jangan dibuang.
                        |--------------------------------------------------------------------------
                        */

                        if (
                            item.kind ===
                            'location'
                        ) {

                            await dbDelete(
                                QUEUE_STORE,
                                item.id
                            );


                            continue;

                        }


                        break;


                    } catch (
                        error
                    ) {

                        break;

                    }

                }


                await updateQueueStatus();


            } finally {


                isSyncing =
                    false;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | FRESH GPS FOR COMPLETION
        |--------------------------------------------------------------------------
        */

        function getFreshGpsPosition() {

            return new Promise(
                function (
                    resolve
                ) {

                    if (
                        !navigator.geolocation
                    ) {

                        resolve(
                            null
                        );


                        return;

                    }


                    navigator
                        .geolocation
                        .getCurrentPosition(
                            function (
                                position
                            ) {

                                resolve({

                                    lat:
                                        Number(
                                            position.coords.latitude
                                        ),

                                    lng:
                                        Number(
                                            position.coords.longitude
                                        ),

                                    accuracy:
                                        Number(
                                            position.coords.accuracy
                                            ||
                                            999
                                        ),

                                    altitude:
                                        position.coords.altitude,

                                    speed:
                                        position.coords.speed,

                                    heading:
                                        position.coords.heading,

                                    timestamp:
                                        Number(
                                            position.timestamp
                                        )
                                        ||
                                        Date.now()

                                });

                            },

                            function () {

                                resolve(
                                    null
                                );

                            },

                            {
                                enableHighAccuracy:
                                    true,

                                timeout:
                                    10000,

                                maximumAge:
                                    0
                            }
                        );

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | LAST INDEXED DB POSITION
        |--------------------------------------------------------------------------
        */

        async function getLastStoredPosition() {

            if (
                !offlineDb
            ) {

                return null;

            }


            const points =
                await dbGetAll(
                    TRACK_STORE
                );


            const filtered =
                points
                    .filter(
                        function (
                            point
                        ) {

                            if (
                                Number(
                                    point.trail_id
                                )
                                !==
                                trailId
                            ) {

                                return false;

                            }


                            if (
                                point.user_route_id
                                &&
                                userRouteId
                            ) {

                                return Number(
                                    point.user_route_id
                                )
                                ===
                                userRouteId;

                            }


                            return true;

                        }
                    )
                    .sort(
                        function (
                            a,
                            b
                        ) {

                            return Number(
                                b.recorded_at
                            )
                            -
                            Number(
                                a.recorded_at
                            );

                        }
                    );


            if (
                filtered.length ===
                0
            ) {

                return null;

            }


            const last =
                filtered[0];


            return {

                lat:
                    Number(
                        last.latitude
                    ),

                lng:
                    Number(
                        last.longitude
                    ),

                accuracy:
                    Number(
                        last.accuracy
                        ||
                        999
                    ),

                altitude:
                    last.altitude
                    ??
                    null,

                speed:
                    last.speed
                    ??
                    null,

                heading:
                    last.heading
                    ??
                    null,

                timestamp:
                    Number(
                        last.recorded_at
                    )
                    ||
                    Date.now()

            };

        }



        /*
        |--------------------------------------------------------------------------
        | RESOLVE FINAL POSITION
        |--------------------------------------------------------------------------
        |
        | Priority:
        |
        | 1. Fresh GPS
        | 2. Last watchPosition
        | 3. Last IndexedDB position
        |--------------------------------------------------------------------------
        */

        async function getCompletionPosition() {

            const fresh =
                await getFreshGpsPosition();


            if (
                fresh
            ) {

                lastPosition =
                    fresh;


                updateLastPositionUi(
                    fresh
                );


                return fresh;

            }


            if (
                lastPosition
            ) {

                return lastPosition;

            }


            const stored =
                await getLastStoredPosition();


            if (
                stored
            ) {

                lastPosition =
                    stored;


                updateLastPositionUi(
                    stored
                );


                return stored;

            }


            return null;

        }



        /*
        |--------------------------------------------------------------------------
        | COMPLETE HIKE
        |--------------------------------------------------------------------------
        */

        async function completeHike() {

            if (
                sessionEnded
            ) {

                return;

            }


            const confirmation =
                await Swal.fire({

                    icon:
                        'question',

                    title:
                        'Selesaikan pendakian?',

                    html:
                        `
                        <div
                            style="
                                font-size:13px;
                                color:#6b7280;
                                line-height:1.65;
                            "
                        >

                            BaliHiking akan mengambil
                            <strong>lokasi terakhir Anda</strong>
                            dan menghentikan Live Tracking.

                            <br><br>

                            Anda dapat menyelesaikan perjalanan
                            kapan pun, meskipun belum sampai
                            titik akhir jalur.

                            ${
                                !navigator.onLine

                                    ?

                                    `
                                    <br><br>

                                    <strong>
                                        Saat ini perangkat offline.
                                        Status selesai akan disimpan
                                        di perangkat dan dikirim ke
                                        server ketika internet kembali.
                                    </strong>
                                    `

                                    :

                                    ''
                            }

                        </div>
                        `,

                    showCancelButton:
                        true,

                    confirmButtonText:
                        'Ya, Selesaikan',

                    cancelButtonText:
                        'Batal',

                    confirmButtonColor:
                        '#059669',

                    cancelButtonColor:
                        '#6b7280',

                    reverseButtons:
                        true

                });


            if (
                !confirmation.isConfirmed
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Disable both buttons.
            |--------------------------------------------------------------------------
            */

            const finishButton =
                document.getElementById(
                    'finishHikeButton'
                );


            const miniButton =
                document.getElementById(
                    'miniFinishButton'
                );


            const finishText =
                document.getElementById(
                    'finishHikeButtonText'
                );


            finishButton.disabled =
                true;


            miniButton.disabled =
                true;


            finishText.innerText =
                'Mengambil lokasi terakhir...';



            /*
            |--------------------------------------------------------------------------
            | Get final GPS.
            |--------------------------------------------------------------------------
            */

            const finalPosition =
                await getCompletionPosition();


            if (
                !finalPosition
            ) {

                finishButton.disabled =
                    false;


                miniButton.disabled =
                    false;


                finishText.innerText =
                    'Selesaikan Pendakian';


                await Swal.fire({

                    icon:
                        'warning',

                    title:
                        'Lokasi belum tersedia',

                    text:
                        'BaliHiking belum memiliki lokasi terakhir. Aktifkan GPS dan tunggu sampai lokasi terbaca.',

                    confirmButtonColor:
                        '#1a382b'

                });


                return;

            }



            /*
            |--------------------------------------------------------------------------
            | Save final local point.
            |--------------------------------------------------------------------------
            */

            try {

                if (
                    offlineDb
                ) {

                    await dbAdd(
                        TRACK_STORE,
                        {
                            trail_id:
                                trailId,

                            user_route_id:
                                userRouteId,

                            latitude:
                                finalPosition.lat,

                            longitude:
                                finalPosition.lng,

                            altitude:
                                finalPosition.altitude,

                            accuracy:
                                finalPosition.accuracy,

                            speed:
                                finalPosition.speed,

                            heading:
                                finalPosition.heading,

                            progress:
                                lastTrustedProgress,

                            status:
                                'completed',

                            recorded_at:
                                Date.now()
                        }
                    );

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking] Final local point:',
                    error
                );

            }



            /*
            |--------------------------------------------------------------------------
            | Payload.
            |--------------------------------------------------------------------------
            */

            const payload = {

                trail_id:
                    trailId,

                user_route_id:
                    userRouteId,

                latitude:
                    finalPosition.lat,

                longitude:
                    finalPosition.lng,

                accuracy:
                    finalPosition.accuracy,

                altitude_m:
                    finalPosition.altitude,

                battery_level:
                    batteryLevel

            };


            finishText.innerText =
                'Menyelesaikan...';



            try {

                const result =
                    await sendOrQueue(
                        'complete',
                        completeEndpoint,
                        payload
                    );


                /*
                |--------------------------------------------------------------------------
                | OFFLINE / QUEUED.
                |--------------------------------------------------------------------------
                */

                if (
                    result.queued
                ) {

                    sessionEnded =
                        true;


                    sessionPendingSync =
                        true;


                    saveSessionState(
                        true,
                        true
                    );


                    stopGpsTracking();


                    markHikingCompleted(
                        true,
                        finalPosition
                    );


                    await Swal.fire({

                        icon:
                            'success',

                        title:
                            'Pendakian selesai',

                        html:
                            `
                            Status selesai dan lokasi terakhir
                            telah disimpan di perangkat.

                            <br><br>

                            BaliHiking akan menyinkronkan data
                            ketika internet kembali.
                            `,

                        confirmButtonText:
                            'OK',

                        confirmButtonColor:
                            '#1a382b'

                    });


                    return;

                }



                /*
                |--------------------------------------------------------------------------
                | ONLINE RESPONSE.
                |--------------------------------------------------------------------------
                */

                const response =
                    result.response;


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


                sessionEnded =
                    true;


                sessionPendingSync =
                    false;


                saveSessionState(
                    true,
                    false
                );


                stopGpsTracking();


                markHikingCompleted(
                    false,
                    finalPosition
                );


                await Swal.fire({

                    icon:
                        'success',

                    title:
                        'Pendakian selesai',

                    html:
                        `
                        Lokasi terakhir dan data perjalanan
                        berhasil disimpan.

                        <br><br>

                        <strong>
                            ${
                                finalPosition.lat.toFixed(6)
                            },
                            ${
                                finalPosition.lng.toFixed(6)
                            }
                        </strong>
                        `,

                    confirmButtonText:
                        'Lihat Riwayat',

                    confirmButtonColor:
                        '#1a382b'

                });


                window.location.href =
                    historyUrl;


            } catch (
                error
            ) {

                finishButton.disabled =
                    false;


                miniButton.disabled =
                    false;


                finishText.innerText =
                    'Selesaikan Pendakian';


                await Swal.fire({

                    icon:
                        'error',

                    title:
                        'Gagal menyelesaikan pendakian',

                    text:
                        error.message,

                    confirmButtonColor:
                        '#1a382b'

                });

            }

        }



        /*
        |--------------------------------------------------------------------------
        | COMPLETED UI
        |--------------------------------------------------------------------------
        */

        function markHikingCompleted(
            pendingSync,
            finalPosition =
                null
        ) {

            sessionEnded =
                true;


            stopGpsTracking();


            /*
            |--------------------------------------------------------------------------
            | Status.
            |--------------------------------------------------------------------------
            */

            document
                .getElementById(
                    'hikingStatusDot'
                )
                .className =
                    pendingSync

                        ?

                        'h-2 w-2 shrink-0 rounded-full bg-amber-500'

                        :

                        'h-2 w-2 shrink-0 rounded-full bg-slate-400';


            document
                .getElementById(
                    'hikingStatusText'
                )
                .innerText =
                    pendingSync

                        ?

                        'Selesai · Menunggu Sinkronisasi'

                        :

                        'Pendakian Selesai';


            const badge =
                document.getElementById(
                    'hikingStatusBadge'
                );


            badge.className =
                pendingSync

                    ?

                    'shrink-0 rounded-full bg-amber-100 px-2 py-1 text-[8px] font-black text-amber-700'

                    :

                    'shrink-0 rounded-full bg-slate-200 px-2 py-1 text-[8px] font-black text-slate-600';


            badge.innerText =
                pendingSync
                    ?
                    'MENUNGGU SYNC'
                    :
                    'SELESAI';



            /*
            |--------------------------------------------------------------------------
            | Disable mini finish.
            |--------------------------------------------------------------------------
            */

            const miniFinish =
                document.getElementById(
                    'miniFinishButton'
                );


            miniFinish.disabled =
                true;


            miniFinish.classList.add(
                'hidden'
            );



            /*
            |--------------------------------------------------------------------------
            | Actions.
            |--------------------------------------------------------------------------
            */

            document
                .getElementById(
                    'activeActions'
                )
                .classList
                .add(
                    'hidden'
                );


            document
                .getElementById(
                    'completedActions'
                )
                .classList
                .remove(
                    'hidden'
                );


            document
                .getElementById(
                    'finishInfo'
                )
                .classList
                .add(
                    'hidden'
                );


            document
                .getElementById(
                    'routeWarning'
                )
                .classList
                .remove(
                    'show'
                );


            document
                .getElementById(
                    'routeMiniBadge'
                )
                .classList
                .add(
                    'hidden'
                );



            /*
            |--------------------------------------------------------------------------
            | Navigation.
            |--------------------------------------------------------------------------
            */

            document
                .getElementById(
                    'navigationInstruction'
                )
                .innerText =
                    'Pendakian selesai';


            document
                .getElementById(
                    'navigationSubtext'
                )
                .innerText =
                    pendingSync
                        ?
                        'Menunggu sinkronisasi ke server'
                        :
                        'Perjalanan berhasil disimpan';


            document
                .getElementById(
                    'navigationDistance'
                )
                .innerText =
                    '✓';


            document
                .getElementById(
                    'routeStatusText'
                )
                .innerText =
                    'Pendakian selesai';



            /*
            |--------------------------------------------------------------------------
            | Final position.
            |--------------------------------------------------------------------------
            */

            if (
                finalPosition
            ) {

                document
                    .getElementById(
                        'completedLocationText'
                    )
                    .innerText =
                        `Lokasi terakhir: ${
                            finalPosition.lat.toFixed(6)
                        }, ${
                            finalPosition.lng.toFixed(6)
                        } · ±${
                            Math.round(
                                finalPosition.accuracy
                            )
                        } m`;

            }



            /*
            |--------------------------------------------------------------------------
            | Expand setelah selesai.
            |--------------------------------------------------------------------------
            */

            setTrackingSheetCollapsed(
                false
            );

        }



        /*
        |--------------------------------------------------------------------------
        | SOS
        |--------------------------------------------------------------------------
        */

        async function sendSOS() {

            if (
                sessionEnded
            ) {

                return;

            }


            let position =
                lastPosition;


            if (
                !position
            ) {

                position =
                    await getLastStoredPosition();

            }


            if (
                !position
            ) {

                await Swal.fire({

                    icon:
                        'warning',

                    title:
                        'Lokasi belum tersedia',

                    text:
                        'Tunggu sampai GPS mendapatkan lokasi Anda.',

                    confirmButtonColor:
                        '#1a382b'

                });


                return;

            }


            const confirmation =
                await Swal.fire({

                    icon:
                        'warning',

                    title:
                        'Kirim SOS?',

                    text:
                        'Gunakan SOS hanya dalam kondisi darurat.',

                    showCancelButton:
                        true,

                    confirmButtonText:
                        'Kirim SOS',

                    cancelButtonText:
                        'Batal',

                    confirmButtonColor:
                        '#dc2626',

                    cancelButtonColor:
                        '#6b7280',

                    reverseButtons:
                        true

                });


            if (
                !confirmation.isConfirmed
            ) {

                return;

            }


            const result =
                await sendOrQueue(
                    'sos',
                    sosEndpoint,
                    {
                        trail_id:
                            trailId,

                        user_route_id:
                            userRouteId,

                        latitude:
                            position.lat,

                        longitude:
                            position.lng,

                        altitude_m:
                            position.altitude,

                        battery_level:
                            batteryLevel
                    }
                );


            if (
                result.queued
            ) {

                await Swal.fire({

                    icon:
                        'warning',

                    title:
                        'SOS tersimpan di perangkat',

                    html:
                        `
                        Internet belum tersedia.

                        <br><br>

                        SOS akan dikirim otomatis ketika
                        perangkat kembali mendapatkan internet.
                        `,

                    confirmButtonColor:
                        '#dc2626'

                });


                return;

            }


            const response =
                result.response;


            const data =
                await response.json();


            if (
                !response.ok
            ) {

                await Swal.fire({

                    icon:
                        'error',

                    title:
                        'SOS gagal dikirim',

                    text:
                        data.message
                        ||
                        'Terjadi kesalahan.',

                    confirmButtonColor:
                        '#dc2626'

                });


                return;

            }


            await Swal.fire({

                icon:
                    'success',

                title:
                    'SOS terkirim',

                text:
                    data.message
                    ||
                    'Sinyal SOS berhasil dikirim.',

                confirmButtonColor:
                    '#1a382b'

            });

        }



        /*
        |--------------------------------------------------------------------------
        | AUTO FOLLOW
        |--------------------------------------------------------------------------
        */

        function enableAutoFollow() {

            if (
                sessionEnded
            ) {

                return;

            }


            autoFollow =
                true;


            /*
            |--------------------------------------------------------------------------
            | Collapse sheet supaya map langsung terlihat luas.
            |--------------------------------------------------------------------------
            */

            setTrackingSheetCollapsed(
                true
            );


            if (
                lastPosition
            ) {

                map.setView(
                    [
                        lastPosition.lat,
                        lastPosition.lng
                    ],

                    Math.max(
                        map.getZoom(),
                        16
                    ),

                    {
                        animate:
                            true
                    }
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | ROUTE POSITION
        |--------------------------------------------------------------------------
        */

        function getRoutePosition(
            latitude,
            longitude
        ) {

            if (
                routeCoordinates.length <
                2
            ) {

                return {

                    distanceM:
                        Infinity,

                    alongKm:
                        0,

                    progress:
                        0,

                    segmentIndex:
                        0,

                    closestLat:
                        latitude,

                    closestLng:
                        longitude

                };

            }


            let minimumDistance =
                Infinity;


            let bestAlongKm =
                0;


            let bestSegment =
                0;


            let bestLatitude =
                Number(
                    routeCoordinates[0][0]
                );


            let bestLongitude =
                Number(
                    routeCoordinates[0][1]
                );


            for (
                let i = 0;
                i < routeCoordinates.length - 1;
                i++
            ) {

                const start =
                    routeCoordinates[
                        i
                    ];


                const end =
                    routeCoordinates[
                        i + 1
                    ];


                const projection =
                    projectPointToSegment(
                        latitude,
                        longitude,

                        Number(
                            start[0]
                        ),

                        Number(
                            start[1]
                        ),

                        Number(
                            end[0]
                        ),

                        Number(
                            end[1]
                        )
                    );


                if (
                    projection.distanceM
                    <
                    minimumDistance
                ) {

                    minimumDistance =
                        projection.distanceM;


                    bestSegment =
                        i;


                    bestLatitude =
                        projection.lat;


                    bestLongitude =
                        projection.lng;


                    const segmentDistance =
                        haversineKm(
                            Number(
                                start[0]
                            ),

                            Number(
                                start[1]
                            ),

                            Number(
                                end[0]
                            ),

                            Number(
                                end[1]
                            )
                        );


                    bestAlongKm =
                        routeCumulativeKm[
                            i
                        ]
                        +
                        (
                            segmentDistance
                            *
                            projection.t
                        );

                }

            }


            const progress =
                routeTotalKm > 0

                    ?

                    (
                        bestAlongKm
                        /
                        routeTotalKm
                    )
                    *
                    100

                    :

                    0;


            return {

                distanceM:
                    minimumDistance,

                alongKm:
                    bestAlongKm,

                progress:
                    progress,

                segmentIndex:
                    bestSegment,

                closestLat:
                    bestLatitude,

                closestLng:
                    bestLongitude

            };

        }



        /*
        |--------------------------------------------------------------------------
        | POINT TO SEGMENT
        |--------------------------------------------------------------------------
        */

        function projectPointToSegment(
            latitude,
            longitude,
            lat1,
            lng1,
            lat2,
            lng2
        ) {

            const metersPerDegreeLat =
                111320;


            const metersPerDegreeLng =
                111320
                *
                Math.cos(
                    degreesToRadians(
                        latitude
                    )
                );


            const ax =
                (
                    lng1
                    -
                    longitude
                )
                *
                metersPerDegreeLng;


            const ay =
                (
                    lat1
                    -
                    latitude
                )
                *
                metersPerDegreeLat;


            const bx =
                (
                    lng2
                    -
                    longitude
                )
                *
                metersPerDegreeLng;


            const by =
                (
                    lat2
                    -
                    latitude
                )
                *
                metersPerDegreeLat;


            const abx =
                bx
                -
                ax;


            const aby =
                by
                -
                ay;


            const lengthSquared =
                (
                    abx
                    *
                    abx
                )
                +
                (
                    aby
                    *
                    aby
                );


            let t =
                0;


            if (
                lengthSquared
                >
                0
            ) {

                t =
                    -(
                        (
                            ax
                            *
                            abx
                        )
                        +
                        (
                            ay
                            *
                            aby
                        )
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
                (
                    t
                    *
                    abx
                );


            const closestY =
                ay
                +
                (
                    t
                    *
                    aby
                );


            return {

                distanceM:
                    Math.sqrt(
                        (
                            closestX
                            *
                            closestX
                        )
                        +
                        (
                            closestY
                            *
                            closestY
                        )
                    ),

                t:
                    t,

                lat:
                    lat1
                    +
                    (
                        (
                            lat2
                            -
                            lat1
                        )
                        *
                        t
                    ),

                lng:
                    lng1
                    +
                    (
                        (
                            lng2
                            -
                            lng1
                        )
                        *
                        t
                    )

            };

        }



        /*
        |--------------------------------------------------------------------------
        | HAVERSINE
        |--------------------------------------------------------------------------
        */

        function haversineKm(
            lat1,
            lng1,
            lat2,
            lng2
        ) {

            const earthRadius =
                6371;


            const deltaLat =
                degreesToRadians(
                    lat2
                    -
                    lat1
                );


            const deltaLng =
                degreesToRadians(
                    lng2
                    -
                    lng1
                );


            const firstLatitude =
                degreesToRadians(
                    lat1
                );


            const secondLatitude =
                degreesToRadians(
                    lat2
                );


            const a =
                Math.sin(
                    deltaLat / 2
                )
                *
                Math.sin(
                    deltaLat / 2
                )
                +
                Math.cos(
                    firstLatitude
                )
                *
                Math.cos(
                    secondLatitude
                )
                *
                Math.sin(
                    deltaLng / 2
                )
                *
                Math.sin(
                    deltaLng / 2
                );


            return earthRadius
                *
                2
                *
                Math.atan2(
                    Math.sqrt(
                        a
                    ),

                    Math.sqrt(
                        1 - a
                    )
                );

        }


        function degreesToRadians(
            value
        ) {

            return value
                *
                Math.PI
                /
                180;

        }



        /*
        |--------------------------------------------------------------------------
        | FORMAT DISTANCE
        |--------------------------------------------------------------------------
        */

        function formatDistanceMeters(
            meters
        ) {

            if (
                !Number.isFinite(
                    meters
                )
            ) {

                return '-';

            }


            if (
                meters
                >=
                1000
            ) {

                return (
                    meters
                    /
                    1000
                )
                .toFixed(
                    1
                )
                .replace(
                    '.',
                    ','
                )
                +
                ' km';

            }


            return `${Math.round(meters)} m`;

        }



        /*
        |--------------------------------------------------------------------------
        | ESCAPE HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(
            value
        ) {

            return String(
                value
                ??
                ''
            )
            .replace(
                /&/g,
                '&amp;'
            )
            .replace(
                /</g,
                '&lt;'
            )
            .replace(
                />/g,
                '&gt;'
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            );

        }

    </script>


@endif


</body>

</html>