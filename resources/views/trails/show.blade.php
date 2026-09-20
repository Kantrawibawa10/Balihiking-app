<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover"
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

    <meta
        name="description"
        content="Peta jalur {{ $trail->name }} - BaliHiking"
    >


    <title>
        Peta Jalur - {{ $trail->name }} - BaliHiking
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
        rel="icon"
        type="image/png"
        sizes="512x512"
        href="{{ asset('icons/icon-512.png') }}"
    >

    <link
        rel="apple-touch-icon"
        href="{{ asset('icons/icon-192.png') }}"
    >



    {{-- ========================================================= --}}
    {{-- LEAFLET LOCAL --}}
    {{-- ========================================================= --}}

    <link
        rel="stylesheet"
        href="{{ asset('vendor/leaflet/leaflet.css') }}"
    >

    <script
        src="{{ asset('vendor/leaflet/leaflet.js') }}"
    ></script>



    {{-- ========================================================= --}}
    {{-- FULL LOCAL STYLE --}}
    {{-- Tidak bergantung Bootstrap / Tailwind / Google Font --}}
    {{-- ========================================================= --}}

    <style>

        :root {

            --bh-dark:
                #1a382b;

            --bh-dark-soft:
                #244b3a;

            --bh-orange:
                #f06535;

            --bh-cream:
                #fbfbfa;

            --bh-white:
                #ffffff;

            --bh-muted:
                #68736d;

            --bh-border:
                rgba(
                    26,
                    56,
                    43,
                    .10
                );

            --bh-success:
                #059669;

            --bh-danger:
                #dc2626;

            --bh-warning:
                #d97706;

        }


        * {

            box-sizing:
                border-box;

        }


        html {

            margin:
                0;

            background:
                var(--bh-cream);

            -webkit-text-size-adjust:
                100%;

        }


        body {

            margin:
                0;

            min-height:
                100vh;

            padding-bottom:
                calc(
                    100px +
                    env(safe-area-inset-bottom)
                );

            color:
                var(--bh-dark);

            background:
                var(--bh-cream);

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Helvetica,
                Arial,
                sans-serif;

            -webkit-font-smoothing:
                antialiased;

        }


        a {

            color:
                inherit;

            text-decoration:
                none;

        }


        button {

            font:
                inherit;

        }



        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .page-header {

            position:
                sticky;

            top:
                0;

            z-index:
                1200;

            height:
                64px;

            border-bottom:
                1px solid
                var(--bh-border);

            background:
                rgba(
                    251,
                    251,
                    250,
                    .96
                );

            backdrop-filter:
                blur(16px);

            -webkit-backdrop-filter:
                blur(16px);

        }


        .page-header-inner {

            display:
                flex;

            width:
                100%;

            max-width:
                780px;

            height:
                64px;

            margin:
                0 auto;

            padding:
                0 16px;

            align-items:
                center;

            gap:
                12px;

        }


        .back-button {

            display:
                flex;

            width:
                40px;

            height:
                40px;

            flex:
                0 0 40px;

            align-items:
                center;

            justify-content:
                center;

            border-radius:
                12px;

            color:
                var(--bh-dark);

            transition:
                background .2s ease;

        }


        .back-button:hover {

            background:
                rgba(
                    26,
                    56,
                    43,
                    .06
                );

        }


        .back-button svg {

            width:
                21px;

            height:
                21px;

        }


        .header-title {

            min-width:
                0;

            flex:
                1;

        }


        .header-eyebrow {

            margin:
                0 0 2px;

            color:
                rgba(
                    26,
                    56,
                    43,
                    .43
                );

            font-size:
                9px;

            font-weight:
                700;

            letter-spacing:
                .06em;

            text-transform:
                uppercase;

        }


        .header-title h1 {

            overflow:
                hidden;

            margin:
                0;

            font-size:
                14px;

            font-weight:
                800;

            line-height:
                1.3;

            text-overflow:
                ellipsis;

            white-space:
                nowrap;

        }


        .app-icon {

            width:
                34px;

            height:
                34px;

            flex:
                0 0 34px;

            overflow:
                hidden;

            border-radius:
                9px;

            background:
                var(--bh-dark);

        }


        .app-icon img {

            display:
                block;

            width:
                100%;

            height:
                100%;

            object-fit:
                cover;

        }



        /*
        |--------------------------------------------------------------------------
        | MAIN
        |--------------------------------------------------------------------------
        */

        .page-content {

            width:
                100%;

            max-width:
                780px;

            margin:
                0 auto;

            padding:
                18px 16px 20px;

        }



        /*
        |--------------------------------------------------------------------------
        | TRAIL SUMMARY
        |--------------------------------------------------------------------------
        */

        .trail-card {

            overflow:
                hidden;

            margin-bottom:
                14px;

            border:
                1px solid
                var(--bh-border);

            border-radius:
                20px;

            background:
                var(--bh-white);

            box-shadow:
                0 6px 24px
                rgba(
                    26,
                    56,
                    43,
                    .05
                );

        }


        .trail-card-header {

            padding:
                18px;

        }


        .trail-top {

            display:
                flex;

            align-items:
                flex-start;

            justify-content:
                space-between;

            gap:
                12px;

        }


        .trail-name {

            min-width:
                0;

            flex:
                1;

        }


        .trail-label {

            margin:
                0 0 5px;

            color:
                var(--bh-orange);

            font-size:
                9px;

            font-weight:
                900;

            letter-spacing:
                .08em;

            text-transform:
                uppercase;

        }


        .trail-name h2 {

            margin:
                0;

            color:
                var(--bh-dark);

            font-size:
                20px;

            font-weight:
                900;

            line-height:
                1.25;

        }


        .trail-mountain {

            margin:
                5px 0 0;

            color:
                var(--bh-muted);

            font-size:
                11px;

            font-weight:
                600;

        }



        /*
        |--------------------------------------------------------------------------
        | STATUS BADGE
        |--------------------------------------------------------------------------
        */

        .status-badge {

            display:
                inline-flex;

            flex:
                0 0 auto;

            align-items:
                center;

            gap:
                5px;

            padding:
                6px 9px;

            border-radius:
                999px;

            font-size:
                9px;

            font-weight:
                900;

            text-transform:
                uppercase;

        }


        .status-badge.open {

            color:
                #047857;

            background:
                #d1fae5;

        }


        .status-badge.closed {

            color:
                #b91c1c;

            background:
                #fee2e2;

        }


        .status-dot {

            width:
                6px;

            height:
                6px;

            border-radius:
                999px;

            background:
                currentColor;

        }



        /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */

        .trail-stats {

            display:
                grid;

            grid-template-columns:
                repeat(
                    3,
                    minmax(
                        0,
                        1fr
                    )
                );

            border-top:
                1px solid
                var(--bh-border);

            background:
                rgba(
                    251,
                    251,
                    250,
                    .7
                );

        }


        .trail-stat {

            min-width:
                0;

            padding:
                12px 8px;

            text-align:
                center;

        }


        .trail-stat:not(:last-child) {

            border-right:
                1px solid
                var(--bh-border);

        }


        .trail-stat-label {

            margin:
                0;

            color:
                rgba(
                    26,
                    56,
                    43,
                    .42
                );

            font-size:
                9px;

            font-weight:
                600;

        }


        .trail-stat-value {

            overflow:
                hidden;

            margin:
                4px 0 0;

            color:
                var(--bh-dark);

            font-size:
                12px;

            font-weight:
                800;

            text-overflow:
                ellipsis;

            white-space:
                nowrap;

        }



        /*
        |--------------------------------------------------------------------------
        | OFFLINE INFO
        |--------------------------------------------------------------------------
        */

        #offlineInformation {

            display:
                none;

            margin-bottom:
                14px;

            padding:
                12px 14px;

            border:
                1px solid
                rgba(
                    240,
                    101,
                    53,
                    .20
                );

            border-radius:
                15px;

            background:
                #fff6f1;

        }


        #offlineInformation.show {

            display:
                block;

        }


        .offline-title {

            display:
                flex;

            align-items:
                center;

            gap:
                7px;

            margin:
                0;

            color:
                var(--bh-orange);

            font-size:
                11px;

            font-weight:
                900;

        }


        .offline-title-dot {

            width:
                7px;

            height:
                7px;

            border-radius:
                999px;

            background:
                var(--bh-orange);

        }


        .offline-description {

            margin:
                5px 0 0;

            color:
                #9a5439;

            font-size:
                10px;

            font-weight:
                500;

            line-height:
                1.55;

        }


        .cache-time {

            margin:
                6px 0 0;

            color:
                rgba(
                    154,
                    84,
                    57,
                    .7
                );

            font-size:
                9px;

            font-weight:
                600;

        }



        /*
        |--------------------------------------------------------------------------
        | MAP
        |--------------------------------------------------------------------------
        */

        .map-card {

            position:
                relative;

            overflow:
                hidden;

            border:
                1px solid
                var(--bh-border);

            border-radius:
                20px;

            background:
                #e8ede9;

            box-shadow:
                0 8px 28px
                rgba(
                    26,
                    56,
                    43,
                    .08
                );

        }


        #map {

            width:
                100%;

            height:
                min(
                    62vh,
                    560px
                );

            min-height:
                430px;

            background:
                #e8ede9;

        }



        /*
        |--------------------------------------------------------------------------
        | MAP OFFLINE OVERLAY
        |--------------------------------------------------------------------------
        */

        #mapOfflineNotice {

            position:
                absolute;

            top:
                12px;

            left:
                50%;

            z-index:
                850;

            display:
                none;

            width:
                calc(
                    100%
                    -
                    30px
                );

            max-width:
                390px;

            padding:
                8px 10px;

            transform:
                translateX(
                    -50%
                );

            border:
                1px solid
                rgba(
                    240,
                    101,
                    53,
                    .18
                );

            border-radius:
                10px;

            color:
                #9a5439;

            background:
                rgba(
                    255,
                    247,
                    242,
                    .95
                );

            box-shadow:
                0 5px 16px
                rgba(
                    0,
                    0,
                    0,
                    .08
                );

            font-size:
                9px;

            font-weight:
                700;

            line-height:
                1.45;

            text-align:
                center;

            pointer-events:
                none;

        }


        #mapOfflineNotice.show {

            display:
                block;

        }



        /*
        |--------------------------------------------------------------------------
        | MAP BUTTON
        |--------------------------------------------------------------------------
        */

        .map-reset-button {

            position:
                absolute;

            right:
                12px;

            bottom:
                12px;

            z-index:
                850;

            display:
                flex;

            width:
                42px;

            height:
                42px;

            align-items:
                center;

            justify-content:
                center;

            border:
                0;

            border-radius:
                12px;

            color:
                var(--bh-dark);

            background:
                rgba(
                    255,
                    255,
                    255,
                    .95
                );

            box-shadow:
                0 6px 20px
                rgba(
                    0,
                    0,
                    0,
                    .15
                );

            cursor:
                pointer;

        }


        .map-reset-button svg {

            width:
                19px;

            height:
                19px;

        }



        /*
        |--------------------------------------------------------------------------
        | MAP LEGEND
        |--------------------------------------------------------------------------
        */

        .map-legend {

            display:
                flex;

            flex-wrap:
                wrap;

            gap:
                12px;

            padding:
                11px 14px;

            border-top:
                1px solid
                var(--bh-border);

            background:
                white;

        }


        .legend-item {

            display:
                flex;

            align-items:
                center;

            gap:
                6px;

            color:
                var(--bh-muted);

            font-size:
                9px;

            font-weight:
                700;

        }


        .legend-line {

            width:
                20px;

            height:
                4px;

            border-radius:
                999px;

            background:
                var(--bh-orange);

        }


        .legend-dot {

            width:
                9px;

            height:
                9px;

            border:
                2px solid
                white;

            border-radius:
                999px;

            background:
                var(--bh-dark);

            box-shadow:
                0 0 0 1px
                rgba(
                    26,
                    56,
                    43,
                    .25
                );

        }


        .legend-dot.start {

            background:
                var(--bh-orange);

        }


        .legend-dot.finish {

            background:
                var(--bh-success);

        }



        /*
        |--------------------------------------------------------------------------
        | CHECKPOINT LIST
        |--------------------------------------------------------------------------
        */

        .checkpoint-section {

            margin-top:
                16px;

            overflow:
                hidden;

            border:
                1px solid
                var(--bh-border);

            border-radius:
                20px;

            background:
                white;

        }


        .section-header {

            padding:
                16px 18px;

            border-bottom:
                1px solid
                var(--bh-border);

        }


        .section-header h3 {

            margin:
                0;

            color:
                var(--bh-dark);

            font-size:
                14px;

            font-weight:
                900;

        }


        .section-header p {

            margin:
                4px 0 0;

            color:
                var(--bh-muted);

            font-size:
                10px;

        }


        .checkpoint-list {

            padding:
                5px 18px;

        }


        .checkpoint-item {

            position:
                relative;

            display:
                flex;

            align-items:
                flex-start;

            gap:
                12px;

            padding:
                13px 0;

        }


        .checkpoint-item:not(:last-child) {

            border-bottom:
                1px solid
                rgba(
                    26,
                    56,
                    43,
                    .07
                );

        }


        .checkpoint-number {

            display:
                flex;

            width:
                28px;

            height:
                28px;

            flex:
                0 0 28px;

            align-items:
                center;

            justify-content:
                center;

            border-radius:
                999px;

            color:
                white;

            background:
                var(--bh-dark);

            font-size:
                9px;

            font-weight:
                900;

        }


        .checkpoint-content {

            min-width:
                0;

            flex:
                1;

        }


        .checkpoint-name {

            margin:
                1px 0 0;

            color:
                var(--bh-dark);

            font-size:
                11px;

            font-weight:
                800;

        }


        .checkpoint-meta {

            margin:
                3px 0 0;

            color:
                var(--bh-muted);

            font-size:
                9px;

            font-weight:
                500;

        }



        /*
        |--------------------------------------------------------------------------
        | ACTION
        |--------------------------------------------------------------------------
        */

        .trail-actions {

            display:
                grid;

            grid-template-columns:
                1fr;

            gap:
                9px;

            margin-top:
                16px;

        }


        .action-button {

            display:
                flex;

            min-height:
                46px;

            align-items:
                center;

            justify-content:
                center;

            gap:
                8px;

            padding:
                10px 14px;

            border-radius:
                13px;

            font-size:
                11px;

            font-weight:
                800;

            transition:
                transform .15s ease,
                opacity .15s ease;

        }


        .action-button:active {

            transform:
                scale(.985);

        }


        .action-button.primary {

            color:
                white;

            background:
                var(--bh-orange);

        }


        .action-button.secondary {

            border:
                1px solid
                var(--bh-border);

            color:
                var(--bh-dark);

            background:
                white;

        }


        .action-button svg {

            width:
                17px;

            height:
                17px;

        }



        /*
        |--------------------------------------------------------------------------
        | CUSTOM LEAFLET MARKERS
        |--------------------------------------------------------------------------
        |
        | Tidak memakai marker-icon.png bawaan Leaflet.
        |--------------------------------------------------------------------------
        */

        .bh-marker-wrapper {

            background:
                transparent !important;

            border:
                none !important;

        }


        .bh-checkpoint-marker {

            display:
                flex;

            width:
                28px;

            height:
                28px;

            align-items:
                center;

            justify-content:
                center;

            border:
                3px solid
                white;

            border-radius:
                999px;

            color:
                white;

            background:
                var(--bh-dark);

            box-shadow:
                0 3px 12px
                rgba(
                    0,
                    0,
                    0,
                    .3
                );

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            font-size:
                9px;

            font-weight:
                900;

        }


        .bh-point-marker {

            width:
                22px;

            height:
                22px;

            border:
                4px solid
                white;

            border-radius:
                999px;

            box-shadow:
                0 3px 12px
                rgba(
                    0,
                    0,
                    0,
                    .3
                );

        }


        .bh-point-marker.start {

            background:
                var(--bh-orange);

        }


        .bh-point-marker.finish {

            background:
                var(--bh-success);

        }



        /*
        |--------------------------------------------------------------------------
        | LEAFLET POPUP
        |--------------------------------------------------------------------------
        */

        .leaflet-popup-content-wrapper {

            border-radius:
                13px !important;

            box-shadow:
                0 8px 25px
                rgba(
                    0,
                    0,
                    0,
                    .17
                ) !important;

        }


        .leaflet-popup-content {

            margin:
                13px 15px !important;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif !important;

        }


        .bh-popup-title {

            margin:
                0;

            color:
                var(--bh-dark);

            font-size:
                12px;

            font-weight:
                900;

        }


        .bh-popup-meta {

            margin:
                4px 0 0;

            color:
                var(--bh-muted);

            font-size:
                9px;

            line-height:
                1.55;

        }



        /*
        |--------------------------------------------------------------------------
        | NETWORK STATUS
        |--------------------------------------------------------------------------
        */

        #networkStatus {

            position:
                fixed;

            left:
                50%;

            bottom:
                calc(
                    92px +
                    env(safe-area-inset-bottom)
                );

            z-index:
                99999;

            display:
                none;

            align-items:
                center;

            gap:
                7px;

            max-width:
                calc(
                    100vw
                    -
                    28px
                );

            padding:
                9px 14px;

            border-radius:
                999px;

            transform:
                translateX(
                    -50%
                );

            color:
                white;

            background:
                var(--bh-orange);

            box-shadow:
                0 10px 28px
                rgba(
                    0,
                    0,
                    0,
                    .17
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


        #networkStatus.online {

            background:
                var(--bh-dark);

        }


        #networkStatus.offline {

            background:
                var(--bh-orange);

        }


        #networkStatusDot {

            width:
                7px;

            height:
                7px;

            flex:
                0 0 7px;

            border-radius:
                999px;

            background:
                white;

        }



        /*
        |--------------------------------------------------------------------------
        | TOAST
        |--------------------------------------------------------------------------
        */

        #pageToast {

            position:
                fixed;

            top:
                76px;

            left:
                50%;

            z-index:
                99999;

            width:
                calc(
                    100%
                    -
                    30px
                );

            max-width:
                410px;

            padding:
                11px 14px;

            border-radius:
                13px;

            transform:
                translate(
                    -50%,
                    -20px
                );

            opacity:
                0;

            visibility:
                hidden;

            color:
                white;

            background:
                var(--bh-dark);

            box-shadow:
                0 12px 30px
                rgba(
                    0,
                    0,
                    0,
                    .18
                );

            font-size:
                10px;

            font-weight:
                700;

            line-height:
                1.5;

            transition:
                all .25s ease;

        }


        #pageToast.warning {

            background:
                var(--bh-orange);

        }


        #pageToast.show {

            transform:
                translate(
                    -50%,
                    0
                );

            opacity:
                1;

            visibility:
                visible;

        }



        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (
            min-width:
                640px
        ) {

            .page-content {

                padding-top:
                    24px;

            }


            #map {

                min-height:
                    500px;

            }


            .trail-actions {

                grid-template-columns:
                    repeat(
                        2,
                        minmax(
                            0,
                            1fr
                        )
                    );

            }

        }

    </style>

</head>


<body>


    {{-- ========================================================= --}}
    {{-- TOAST --}}
    {{-- ========================================================= --}}

    <div
        id="pageToast"
        role="status"
        aria-live="polite"
    >
        BaliHiking
    </div>



    {{-- ========================================================= --}}
    {{-- NETWORK STATUS --}}
    {{-- ========================================================= --}}

    <div
        id="networkStatus"
    >

        <span
            id="networkStatusDot"
        ></span>


        <span
            id="networkStatusText"
        >
            Mode Offline
        </span>

    </div>



    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <header
        class="page-header"
    >

        <div
            class="page-header-inner"
        >

            <a
                href="{{ route('pendaki.dashboard') }}"
                data-offline-link
                class="back-button"
                aria-label="Kembali"
            >

                <svg
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2.3"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m15 18-6-6 6-6"
                    />

                </svg>

            </a>


            <div
                class="header-title"
            >

                <p
                    class="header-eyebrow"
                >
                    Peta Jalur
                </p>


                <h1>
                    {{ $trail->name }}
                </h1>

            </div>


            <div
                class="app-icon"
            >

                <img
                    src="{{ asset('icons/icon-192.png') }}"
                    alt="BaliHiking"
                >

            </div>

        </div>

    </header>



    {{-- ========================================================= --}}
    {{-- MAIN --}}
    {{-- ========================================================= --}}

    <main
        class="page-content"
    >


        {{-- ===================================================== --}}
        {{-- OFFLINE INFORMATION --}}
        {{-- ===================================================== --}}

        <section
            id="offlineInformation"
        >

            <p
                class="offline-title"
            >

                <span
                    class="offline-title-dot"
                ></span>

                Peta Jalur Offline

            </p>


            <p
                class="offline-description"
            >
                Data jalur dan checkpoint tetap tersedia.
                Peta dasar hanya dapat ditampilkan untuk area
                yang sebelumnya sudah pernah dimuat ketika online.
            </p>


            <p
                id="cacheTimeContainer"
                class="cache-time"
                style="display:none;"
            >
                Data terakhir tersimpan:
                <span id="cacheTime">-</span>
            </p>

        </section>



        {{-- ===================================================== --}}
        {{-- TRAIL INFORMATION --}}
        {{-- ===================================================== --}}

        <section
            class="trail-card"
        >

            <div
                class="trail-card-header"
            >

                <div
                    class="trail-top"
                >

                    <div
                        class="trail-name"
                    >

                        <p
                            class="trail-label"
                        >
                            Jalur Pendakian
                        </p>


                        <h2>
                            {{ $trail->name }}
                        </h2>


                        <p
                            class="trail-mountain"
                        >
                            {{
                                $trail->mountain->name
                                ??
                                'Gunung Bali'
                            }}
                        </p>

                    </div>



                    <span
                        class="
                            status-badge
                            {{
                                $trail->status === 'open'
                                    ? 'open'
                                    : 'closed'
                            }}
                        "
                    >

                        <span
                            class="status-dot"
                        ></span>


                        {{
                            $trail->status === 'open'
                                ? 'Dibuka'
                                : 'Ditutup'
                        }}

                    </span>

                </div>

            </div>



            <div
                class="trail-stats"
            >


                <div
                    class="trail-stat"
                >

                    <p
                        class="trail-stat-label"
                    >
                        Jarak
                    </p>


                    <p
                        class="trail-stat-value"
                    >

                        @if($trail->distance_km)

                            {{
                                number_format(
                                    $trail->distance_km,
                                    1,
                                    ',',
                                    '.'
                                )
                            }}
                            km

                        @else

                            -

                        @endif

                    </p>

                </div>



                <div
                    class="trail-stat"
                >

                    <p
                        class="trail-stat-label"
                    >
                        Estimasi
                    </p>


                    <p
                        class="trail-stat-value"
                    >

                        @if($trail->estimated_time_hours)

                            {{
                                $trail->estimated_time_hours
                            }}
                            jam

                        @else

                            -

                        @endif

                    </p>

                </div>



                <div
                    class="trail-stat"
                >

                    <p
                        class="trail-stat-label"
                    >
                        Checkpoint
                    </p>


                    <p
                        class="trail-stat-value"
                    >
                        {{ $trail->checkpoints->count() }}
                    </p>

                </div>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- MAP --}}
        {{-- ===================================================== --}}

        <section
            class="map-card"
        >

            <div
                id="map"
            ></div>


            <div
                id="mapOfflineNotice"
            >
                Peta dasar tidak tersedia untuk sebagian area.
                Jalur dan checkpoint tetap dapat digunakan.
            </div>


            <button
                type="button"
                id="resetMapButton"
                class="map-reset-button"
                aria-label="Tampilkan seluruh jalur"
            >

                <svg
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 2v3m0 14v3M2 12h3m14 0h3"
                    />

                    <circle
                        cx="12"
                        cy="12"
                        r="4"
                    />

                </svg>

            </button>



            <div
                class="map-legend"
            >

                <div
                    class="legend-item"
                >

                    <span
                        class="legend-line"
                    ></span>

                    Jalur

                </div>


                <div
                    class="legend-item"
                >

                    <span
                        class="legend-dot start"
                    ></span>

                    Mulai

                </div>


                <div
                    class="legend-item"
                >

                    <span
                        class="legend-dot"
                    ></span>

                    Checkpoint

                </div>


                <div
                    class="legend-item"
                >

                    <span
                        class="legend-dot finish"
                    ></span>

                    Akhir

                </div>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- CHECKPOINT LIST --}}
        {{-- ===================================================== --}}

        @if(
            $trail
                ->checkpoints
                ->isNotEmpty()
        )

            <section
                class="checkpoint-section"
            >

                <div
                    class="section-header"
                >

                    <h3>
                        Checkpoint Jalur
                    </h3>


                    <p>
                        Titik penting sepanjang perjalanan.
                    </p>

                </div>



                <div
                    class="checkpoint-list"
                >

                    @foreach(
                        $trail->checkpoints
                        as $checkpoint
                    )

                        <div
                            class="checkpoint-item"
                        >

                            <div
                                class="checkpoint-number"
                            >
                                {{ $loop->iteration }}
                            </div>


                            <div
                                class="checkpoint-content"
                            >

                                <p
                                    class="checkpoint-name"
                                >
                                    {{ $checkpoint->name }}
                                </p>


                                <p
                                    class="checkpoint-meta"
                                >

                                    {{
                                        $checkpoint->type
                                        ??
                                        'Checkpoint'
                                    }}


                                    @if(
                                        $checkpoint->elevation_m
                                    )

                                        ·

                                        {{
                                            number_format(
                                                $checkpoint->elevation_m,
                                                0,
                                                ',',
                                                '.'
                                            )
                                        }}

                                        mdpl

                                    @endif

                                </p>

                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        @endif



        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div
            class="trail-actions"
        >

            <a
                href="{{ route('pendaki.dashboard') }}"
                data-offline-link
                class="action-button secondary"
            >

                <svg
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

                Kembali ke Beranda

            </a>



            @if(
                Route::has(
                    'pendaki.live-track'
                )
            )

                <a
                    href="{{
                        route(
                            'pendaki.live-track',
                            [
                                'trail_id' =>
                                    $trail->id
                            ]
                        )
                    }}"
                    data-offline-link
                    class="action-button primary"
                >

                    <svg
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

                    Mulai Live Tracking

                </a>

            @endif

        </div>

    </main>



    {{-- ========================================================= --}}
    {{-- BOTTOM NAVIGATION --}}
    {{-- ========================================================= --}}

    @include(
        'pendaki.components.bottom-nav',
        [
            'active' => 'dashboard'
        ]
    )



    {{-- ========================================================= --}}
    {{-- MAP + OFFLINE ENGINE --}}
    {{-- ========================================================= --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | BaliHiking Trail Map
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | SERVER DATA
        |--------------------------------------------------------------------------
        */

        const rawGeojsonData =
            @json(
                $trail->map_geojson
            );


        const checkpoints =
            @json(
                $trail
                    ->checkpoints
                    ->map(
                        function (
                            $checkpoint
                        ) {

                            return [

                                'id' =>
                                    $checkpoint->id,

                                'name' =>
                                    $checkpoint->name,

                                'type' =>
                                    $checkpoint->type,

                                'latitude' =>
                                    $checkpoint->latitude,

                                'longitude' =>
                                    $checkpoint->longitude,

                                'elevation_m' =>
                                    $checkpoint->elevation_m,

                            ];

                        }
                    )
                    ->values()
            );


        const trailId =
            @json(
                $trail->id
            );


        const trailName =
            @json(
                $trail->name
            );


        const pageCacheName =
            'balihiking-trail-page-v3';


        const pageCacheTimeKey =
            `balihiking_trail_${trailId}_cache_time`;



        /*
        |--------------------------------------------------------------------------
        | STATE
        |--------------------------------------------------------------------------
        */

        let map =
            null;


        let trailLayer =
            null;


        let tileLayer =
            null;


        let tileErrorCount =
            0;


        let toastTimer =
            null;



        /*
        |--------------------------------------------------------------------------
        | NORMALIZE GEOJSON
        |--------------------------------------------------------------------------
        */

        function normalizeGeoJson(
            value
        ) {

            if (
                !value
            ) {

                return null;

            }


            if (
                typeof value ===
                    'string'
            ) {

                try {

                    return JSON.parse(
                        value
                    );


                } catch (
                    error
                ) {

                    console.error(
                        '[BaliHiking Map] GeoJSON tidak valid:',
                        error
                    );


                    return null;

                }

            }


            if (
                typeof value ===
                    'object'
            ) {

                return value;

            }


            return null;

        }


        const geojsonData =
            normalizeGeoJson(
                rawGeojsonData
            );



        /*
        |--------------------------------------------------------------------------
        | INIT
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'DOMContentLoaded',
            async function () {

                initializeMap();

                initializeResetButton();

                initializeOfflineLinks();

                updateNetworkUI();

                showCacheTimestamp();


                if (
                    navigator.onLine
                ) {

                    await cacheCurrentPage();

                    await cacheLocalAssets();

                }

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

                        zoomControl:
                            true,

                        attributionControl:
                            true

                    }
                )
                .setView(
                    [
                        -8.3405,
                        115.5082
                    ],
                    12
                );


            /*
            |--------------------------------------------------------------------------
            | OPEN STREET MAP
            |--------------------------------------------------------------------------
            |
            | Service Worker BaliHiking akan menyimpan tile yang benar-benar
            | sudah pernah ditampilkan.
            |
            */

            tileLayer =
                L.tileLayer(
                    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    {

                        maxZoom:
                            19,

                        crossOrigin:
                            true,

                        attribution:
                            '&copy; OpenStreetMap'

                    }
                );


            tileLayer.on(
                'tileerror',
                function () {

                    tileErrorCount++;


                    if (
                        !navigator.onLine
                        &&
                        tileErrorCount >=
                            2
                    ) {

                        document
                            .getElementById(
                                'mapOfflineNotice'
                            )
                            .classList
                            .add(
                                'show'
                            );

                    }

                }
            );


            tileLayer.on(
                'load',
                function () {

                    if (
                        navigator.onLine
                    ) {

                        document
                            .getElementById(
                                'mapOfflineNotice'
                            )
                            .classList
                            .remove(
                                'show'
                            );

                    }

                }
            );


            tileLayer.addTo(
                map
            );


            /*
            |--------------------------------------------------------------------------
            | GEOJSON
            |--------------------------------------------------------------------------
            */

            if (
                geojsonData
            ) {

                try {

                    /*
                    |--------------------------------------------------------------------------
                    | White route outline
                    |--------------------------------------------------------------------------
                    */

                    L.geoJSON(
                        geojsonData,
                        {

                            style: {

                                color:
                                    '#ffffff',

                                weight:
                                    9,

                                opacity:
                                    .95,

                                lineCap:
                                    'round',

                                lineJoin:
                                    'round'

                            }

                        }
                    )
                    .addTo(
                        map
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Main BaliHiking route
                    |--------------------------------------------------------------------------
                    */

                    trailLayer =
                        L.geoJSON(
                            geojsonData,
                            {

                                style: {

                                    color:
                                        '#f06535',

                                    weight:
                                        5,

                                    opacity:
                                        1,

                                    lineCap:
                                        'round',

                                    lineJoin:
                                        'round'

                                }

                            }
                        )
                        .addTo(
                            map
                        );


                    const bounds =
                        trailLayer
                            .getBounds();


                    if (
                        bounds.isValid()
                    ) {

                        map.fitBounds(
                            bounds,
                            {

                                padding:
                                    [
                                        35,
                                        35
                                    ],

                                maxZoom:
                                    17

                            }
                        );


                        addStartFinishMarkers(
                            bounds
                        );

                    }


                } catch (
                    error
                ) {

                    console.error(
                        '[BaliHiking Map] Render GeoJSON gagal:',
                        error
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | CHECKPOINT MARKERS
            |--------------------------------------------------------------------------
            */

            addCheckpointMarkers();


            /*
            |--------------------------------------------------------------------------
            | Tidak ada rute dan checkpoint
            |--------------------------------------------------------------------------
            */

            if (
                !geojsonData
                &&
                checkpoints.length >
                    0
            ) {

                const bounds =
                    L.latLngBounds(
                        checkpoints
                            .filter(
                                validCheckpoint
                            )
                            .map(
                                function (
                                    cp
                                ) {

                                    return [

                                        Number(
                                            cp.latitude
                                        ),

                                        Number(
                                            cp.longitude
                                        )

                                    ];

                                }
                            )
                    );


                if (
                    bounds.isValid()
                ) {

                    map.fitBounds(
                        bounds,
                        {

                            padding:
                                [
                                    35,
                                    35
                                ],

                            maxZoom:
                                16

                        }
                    );

                }

            }


            setTimeout(
                function () {

                    map.invalidateSize();

                },
                250
            );

        }



        /*
        |--------------------------------------------------------------------------
        | START / FINISH
        |--------------------------------------------------------------------------
        */

        function addStartFinishMarkers(
            bounds
        ) {

            if (
                !bounds
                ||
                !bounds.isValid()
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Bounds southwest/northeast bukan selalu awal/akhir jalur.
            | Ambil koordinat pertama & terakhir langsung dari GeoJSON.
            |--------------------------------------------------------------------------
            */

            const routeCoordinates =
                extractLineCoordinates(
                    geojsonData
                );


            if (
                routeCoordinates.length <
                    2
            ) {

                return;

            }


            const start =
                routeCoordinates[
                    0
                ];


            const finish =
                routeCoordinates[
                    routeCoordinates.length -
                    1
                ];


            const startIcon =
                L.divIcon({

                    className:
                        'bh-marker-wrapper',

                    html:
                        '<div class="bh-point-marker start"></div>',

                    iconSize:
                        [
                            22,
                            22
                        ],

                    iconAnchor:
                        [
                            11,
                            11
                        ]

                });


            const finishIcon =
                L.divIcon({

                    className:
                        'bh-marker-wrapper',

                    html:
                        '<div class="bh-point-marker finish"></div>',

                    iconSize:
                        [
                            22,
                            22
                        ],

                    iconAnchor:
                        [
                            11,
                            11
                        ]

                });


            L.marker(
                [
                    start[1],
                    start[0]
                ],
                {
                    icon:
                        startIcon
                }
            )
            .addTo(
                map
            )
            .bindPopup(
                `
                <p class="bh-popup-title">
                    Titik Mulai
                </p>

                <p class="bh-popup-meta">
                    Awal jalur ${escapeHtml(trailName)}
                </p>
                `
            );


            L.marker(
                [
                    finish[1],
                    finish[0]
                ],
                {
                    icon:
                        finishIcon
                }
            )
            .addTo(
                map
            )
            .bindPopup(
                `
                <p class="bh-popup-title">
                    Titik Akhir
                </p>

                <p class="bh-popup-meta">
                    Akhir jalur ${escapeHtml(trailName)}
                </p>
                `
            );

        }



        /*
        |--------------------------------------------------------------------------
        | EXTRACT LINE COORDINATES
        |--------------------------------------------------------------------------
        */

        function extractLineCoordinates(
            geojson
        ) {

            if (
                !geojson
            ) {

                return [];

            }


            if (
                geojson.type ===
                    'LineString'
                &&
                Array.isArray(
                    geojson.coordinates
                )
            ) {

                return geojson
                    .coordinates;

            }


            if (
                geojson.type ===
                    'Feature'
            ) {

                return extractLineCoordinates(
                    geojson.geometry
                );

            }


            if (
                geojson.type ===
                    'FeatureCollection'
                &&
                Array.isArray(
                    geojson.features
                )
            ) {

                for (
                    const feature
                    of geojson.features
                ) {

                    const coordinates =
                        extractLineCoordinates(
                            feature
                        );


                    if (
                        coordinates.length
                    ) {

                        return coordinates;

                    }

                }

            }


            if (
                geojson.type ===
                    'MultiLineString'
                &&
                Array.isArray(
                    geojson.coordinates
                )
            ) {

                return geojson
                    .coordinates
                    .flat();

            }


            return [];

        }



        /*
        |--------------------------------------------------------------------------
        | CHECKPOINT
        |--------------------------------------------------------------------------
        */

        function validCheckpoint(
            checkpoint
        ) {

            return (
                checkpoint
                &&
                checkpoint.latitude !==
                    null
                &&
                checkpoint.longitude !==
                    null
                &&
                Number.isFinite(
                    Number(
                        checkpoint.latitude
                    )
                )
                &&
                Number.isFinite(
                    Number(
                        checkpoint.longitude
                    )
                )
            );

        }



        function addCheckpointMarkers() {

            checkpoints.forEach(
                function (
                    checkpoint,
                    index
                ) {

                    if (
                        !validCheckpoint(
                            checkpoint
                        )
                    ) {

                        return;

                    }


                    const icon =
                        L.divIcon({

                            className:
                                'bh-marker-wrapper',

                            html:
                                `
                                <div class="bh-checkpoint-marker">
                                    ${index + 1}
                                </div>
                                `,

                            iconSize:
                                [
                                    28,
                                    28
                                ],

                            iconAnchor:
                                [
                                    14,
                                    14
                                ]

                        });


                    const popupContent =
                        `
                        <p class="bh-popup-title">
                            ${escapeHtml(
                                checkpoint.name
                                ||
                                `Checkpoint ${index + 1}`
                            )}
                        </p>

                        <div class="bh-popup-meta">

                            Tipe:
                            <strong>
                                ${escapeHtml(
                                    checkpoint.type
                                    ||
                                    'Checkpoint'
                                )}
                            </strong>

                            <br>

                            Elevasi:
                            ${
                                checkpoint.elevation_m
                                    ?
                                    escapeHtml(
                                        String(
                                            checkpoint.elevation_m
                                        )
                                    )
                                    +
                                    ' mdpl'
                                    :
                                    '-'
                            }

                        </div>
                        `;


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
                            icon:
                                icon
                        }
                    )
                    .addTo(
                        map
                    )
                    .bindPopup(
                        popupContent
                    );

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | FIT ROUTE
        |--------------------------------------------------------------------------
        */

        function fitTrail() {

            if (
                trailLayer
            ) {

                const bounds =
                    trailLayer
                        .getBounds();


                if (
                    bounds.isValid()
                ) {

                    map.fitBounds(
                        bounds,
                        {

                            padding:
                                [
                                    35,
                                    35
                                ],

                            maxZoom:
                                17

                        }
                    );


                    return;

                }

            }


            const valid =
                checkpoints
                    .filter(
                        validCheckpoint
                    );


            if (
                valid.length
            ) {

                map.fitBounds(

                    L.latLngBounds(

                        valid.map(
                            function (
                                cp
                            ) {

                                return [

                                    Number(
                                        cp.latitude
                                    ),

                                    Number(
                                        cp.longitude
                                    )

                                ];

                            }
                        )

                    ),

                    {

                        padding:
                            [
                                35,
                                35
                            ],

                        maxZoom:
                            16

                    }

                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | RESET BUTTON
        |--------------------------------------------------------------------------
        */

        function initializeResetButton() {

            document
                .getElementById(
                    'resetMapButton'
                )
                .addEventListener(
                    'click',
                    fitTrail
                );

        }



        /*
        |--------------------------------------------------------------------------
        | NETWORK
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'online',
            async function () {

                updateNetworkUI();


                showToast(
                    'Internet kembali aktif. BaliHiking memperbarui peta.'
                );


                tileErrorCount =
                    0;


                document
                    .getElementById(
                        'mapOfflineNotice'
                    )
                    .classList
                    .remove(
                        'show'
                    );


                if (
                    tileLayer
                ) {

                    tileLayer.redraw();

                }


                await cacheCurrentPage();

            }
        );


        window.addEventListener(
            'offline',
            function () {

                updateNetworkUI();


                showToast(
                    'Mode offline aktif. Jalur dan checkpoint tetap tersedia.',
                    true
                );

            }
        );



        function updateNetworkUI() {

            const status =
                document.getElementById(
                    'networkStatus'
                );


            const text =
                document.getElementById(
                    'networkStatusText'
                );


            const offlineInfo =
                document.getElementById(
                    'offlineInformation'
                );


            if (
                navigator.onLine
            ) {

                status
                    .classList
                    .remove(
                        'offline'
                    );


                status
                    .classList
                    .add(
                        'online'
                    );


                text.textContent =
                    'Online';


                offlineInfo
                    .classList
                    .remove(
                        'show'
                    );


                setTimeout(
                    function () {

                        if (
                            navigator.onLine
                        ) {

                            status
                                .classList
                                .remove(
                                    'show'
                                );

                        }

                    },
                    1600
                );


            } else {


                status
                    .classList
                    .remove(
                        'online'
                    );


                status
                    .classList
                    .add(
                        'offline',
                        'show'
                    );


                text.textContent =
                    'Offline · menggunakan peta tersimpan';


                offlineInfo
                    .classList
                    .add(
                        'show'
                    );


                showCacheTimestamp();

            }

        }



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
                async function () {

                    try {

                        const registration =
                            await navigator
                                .serviceWorker
                                .register(
                                    '/sw.js',
                                    {
                                        scope:
                                            '/'
                                    }
                                );


                        console.log(
                            '[BaliHiking Trail Map] Service Worker:',
                            registration.scope
                        );


                        registration
                            .update()
                            .catch(
                                function () {}
                            );


                    } catch (
                        error
                    ) {

                        console.error(
                            '[BaliHiking Trail Map] SW gagal:',
                            error
                        );

                    }

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | CACHE CURRENT PAGE
        |--------------------------------------------------------------------------
        */

        async function cacheCurrentPage() {

            if (
                !navigator.onLine
                ||
                !('caches' in window)
            ) {

                return;

            }


            try {

                const cache =
                    await caches.open(
                        pageCacheName
                    );


                const response =
                    await fetch(
                        window.location.href,
                        {

                            credentials:
                                'same-origin',

                            cache:
                                'no-store',

                            headers: {

                                'X-BaliHiking-Cache':
                                    'trail-map'

                            }

                        }
                    );


                if (
                    response.ok
                ) {

                    await cache.put(
                        window.location.href,
                        response.clone()
                    );


                    try {

                        localStorage.setItem(
                            pageCacheTimeKey,
                            String(
                                Date.now()
                            )
                        );

                    } catch (
                        error
                    ) {}


                    showCacheTimestamp();

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking Trail Map] Cache halaman gagal:',
                    error
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | CACHE LOCAL ASSETS
        |--------------------------------------------------------------------------
        */

        async function cacheLocalAssets() {

            if (
                !navigator.onLine
                ||
                !('caches' in window)
            ) {

                return;

            }


            const assets = [

                '/manifest.webmanifest',

                '/icons/icon-192.png',

                '/icons/icon-512.png',

                '/icons/icon-maskable-192.png',

                '/icons/icon-maskable-512.png',

                '/vendor/leaflet/leaflet.css',

                '/vendor/leaflet/leaflet.js'

            ];


            try {

                const cache =
                    await caches.open(
                        pageCacheName
                    );


                for (
                    const url
                    of assets
                ) {

                    try {

                        const response =
                            await fetch(
                                url
                            );


                        if (
                            response.ok
                        ) {

                            await cache.put(
                                url,
                                response.clone()
                            );

                        }

                    } catch (
                        error
                    ) {}

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking Trail Map] Cache asset gagal:',
                    error
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | CACHE TIMESTAMP
        |--------------------------------------------------------------------------
        */

        function showCacheTimestamp() {

            let saved =
                null;


            try {

                saved =
                    localStorage.getItem(
                        pageCacheTimeKey
                    );

            } catch (
                error
            ) {}


            if (
                !saved
            ) {

                return;

            }


            const date =
                new Date(
                    Number(
                        saved
                    )
                );


            if (
                Number.isNaN(
                    date.getTime()
                )
            ) {

                return;

            }


            document
                .getElementById(
                    'cacheTime'
                )
                .textContent =
                    date.toLocaleString(
                        'id-ID',
                        {

                            dateStyle:
                                'medium',

                            timeStyle:
                                'short'

                        }
                    );


            document
                .getElementById(
                    'cacheTimeContainer'
                )
                .style
                .display =
                    'block';

        }



        /*
        |--------------------------------------------------------------------------
        | OFFLINE LINKS
        |--------------------------------------------------------------------------
        */

        function initializeOfflineLinks() {

            document
                .querySelectorAll(
                    '[data-offline-link]'
                )
                .forEach(
                    function (
                        link
                    ) {

                        link
                            .addEventListener(
                                'click',
                                async function (
                                    event
                                ) {

                                    if (
                                        navigator.onLine
                                    ) {

                                        return;

                                    }


                                    event.preventDefault();


                                    const cached =
                                        await isCached(
                                            link.href
                                        );


                                    if (
                                        cached
                                    ) {

                                        window.location.href =
                                            link.href;


                                        return;

                                    }


                                    showToast(
                                        'Halaman tujuan belum tersimpan. Buka halaman tersebut minimal sekali ketika online.',
                                        true
                                    );

                                }
                            );

                    }
                );

        }



        /*
        |--------------------------------------------------------------------------
        | IS CACHED
        |--------------------------------------------------------------------------
        */

        async function isCached(
            url
        ) {

            if (
                !('caches' in window)
            ) {

                return false;

            }


            try {

                let response =
                    await caches.match(
                        url
                    );


                if (
                    response
                ) {

                    return true;

                }


                const parsed =
                    new URL(
                        url,
                        window.location.origin
                    );


                response =
                    await caches.match(
                        parsed.pathname
                        +
                        parsed.search
                    );


                if (
                    response
                ) {

                    return true;

                }


                const cacheNames =
                    await caches.keys();


                for (
                    const name
                    of cacheNames
                ) {

                    const cache =
                        await caches.open(
                            name
                        );


                    response =
                        await cache.match(
                            url,
                            {
                                ignoreSearch:
                                    true
                            }
                        );


                    if (
                        response
                    ) {

                        return true;

                    }

                }


                return false;


            } catch (
                error
            ) {

                return false;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | SAFE HTML
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



        /*
        |--------------------------------------------------------------------------
        | TOAST
        |--------------------------------------------------------------------------
        */

        function showToast(
            message,
            warning =
                false
        ) {

            const toast =
                document.getElementById(
                    'pageToast'
                );


            clearTimeout(
                toastTimer
            );


            toast
                .classList
                .remove(
                    'show',
                    'warning'
                );


            if (
                warning
            ) {

                toast
                    .classList
                    .add(
                        'warning'
                    );

            }


            toast.textContent =
                message;


            requestAnimationFrame(
                function () {

                    toast
                        .classList
                        .add(
                            'show'
                        );

                }
            );


            toastTimer =
                setTimeout(
                    function () {

                        toast
                            .classList
                            .remove(
                                'show'
                            );

                    },
                    3800
                );

        }

    </script>


</body>

</html>