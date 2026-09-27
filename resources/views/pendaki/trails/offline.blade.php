<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover"
    >

    <meta
        name="theme-color"
        content="#123d2c"
    >

    <title>
        Download Map Offline - {{ $trail->name ?? 'BaliHiking' }}
    </title>

    <style>
        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }


        :root {
            --green: #123d2c;
            --green-dark: #08291d;
            --green-soft: #edf5f0;

            --orange: #f26335;
            --orange-soft: #fff1eb;

            --page: #f5f7f5;
            --white: #ffffff;

            --text: #17241f;
            --muted: #819188;

            --border: rgba(18, 61, 44, .10);
        }


        html,
        body {
            width: 100%;
            min-height: 100%;

            margin: 0;

            padding: 0;
        }


        body {
            background: var(--page);

            color: var(--text);

            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            -webkit-font-smoothing: antialiased;
        }


        button,
        a {
            font: inherit;
        }


        /*
        |--------------------------------------------------------------------------
        | APP
        |--------------------------------------------------------------------------
        */

        .app {
            width: 100%;

            max-width: 430px;

            min-height: 100dvh;

            margin: 0 auto;

            background: var(--page);
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .header {
            position: sticky;

            top: 0;

            z-index: 30;

            display: grid;

            grid-template-columns:
                42px
                minmax(0, 1fr)
                42px;

            align-items: center;

            min-height: 68px;

            padding:
                env(safe-area-inset-top)
                16px
                0;

            border-bottom:
                1px solid
                var(--border);

            background:
                rgba(
                    255,
                    255,
                    255,
                    .95
                );

            backdrop-filter:
                blur(14px);
        }


        .header-inner {
            display: contents;
        }


        .header-action {
            display: inline-flex;

            width: 40px;

            height: 40px;

            align-items: center;

            justify-content: center;

            border: 0;

            border-radius: 12px;

            background: transparent;

            color: var(--green);

            text-decoration: none;
        }


        .header-action:active {
            background:
                rgba(
                    18,
                    61,
                    44,
                    .06
                );
        }


        .header-action svg {
            width: 21px;

            height: 21px;
        }


        .header-copy {
            min-width: 0;

            text-align: center;
        }


        .header-title {
            margin: 0;

            overflow: hidden;

            color: var(--green);

            font-size: 13px;

            font-weight: 900;

            text-overflow: ellipsis;

            white-space: nowrap;
        }


        .header-subtitle {
            margin: 3px 0 0;

            color: var(--muted);

            font-size: 8px;

            font-weight: 600;
        }


        /*
        |--------------------------------------------------------------------------
        | MAIN
        |--------------------------------------------------------------------------
        */

        .main {
            padding:
                20px
                16px
                calc(
                    38px
                    +
                    env(safe-area-inset-bottom)
                );
        }


        /*
        |--------------------------------------------------------------------------
        | HERO
        |--------------------------------------------------------------------------
        */

        .hero {
            position: relative;

            overflow: hidden;

            padding: 23px;

            border-radius: 24px;

            background:
                linear-gradient(
                    145deg,
                    #123d2c,
                    #08291d
                );

            color: white;
        }


        .hero::before {
            content: '';

            position: absolute;

            top: -90px;

            right: -80px;

            width: 220px;

            height: 220px;

            border:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    .08
                );

            border-radius: 50%;
        }


        .hero::after {
            content: '';

            position: absolute;

            right: -80px;

            bottom: -120px;

            width: 250px;

            height: 250px;

            border:
                1px solid
                rgba(
                    242,
                    99,
                    53,
                    .20
                );

            border-radius: 50%;
        }


        .hero-content {
            position: relative;

            z-index: 2;
        }


        .hero-label {
            color: #78ddb7;

            font-size: 8px;

            font-weight: 900;

            letter-spacing: .15em;

            text-transform: uppercase;
        }


        .hero-title {
            margin:
                11px
                0
                0;

            color: white;

            font-size: 25px;

            line-height: 1.05;

            font-weight: 900;

            letter-spacing: -.04em;
        }


        .hero-description {
            max-width: 310px;

            margin:
                12px
                0
                0;

            color:
                rgba(
                    255,
                    255,
                    255,
                    .60
                );

            font-size: 10px;

            line-height: 1.7;
        }


        /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */

        .stats {
            display: grid;

            grid-template-columns:
                repeat(
                    2,
                    minmax(
                        0,
                        1fr
                    )
                );

            gap: 10px;

            margin-top: 12px;
        }


        .stat-card {
            padding: 14px;

            border:
                1px solid
                var(--border);

            border-radius: 16px;

            background: white;
        }


        .stat-label {
            color: var(--muted);

            font-size: 8px;

            font-weight: 700;
        }


        .stat-value {
            margin-top: 5px;

            color: var(--green);

            font-size: 17px;

            font-weight: 900;
        }


        /*
        |--------------------------------------------------------------------------
        | SECTION
        |--------------------------------------------------------------------------
        */

        .section {
            margin-top: 25px;
        }


        .section-kicker {
            color: var(--orange);

            font-size: 8px;

            font-weight: 900;

            letter-spacing: .15em;

            text-transform: uppercase;
        }


        .section-title {
            margin:
                7px
                0
                0;

            color: var(--green);

            font-size: 21px;

            font-weight: 900;

            letter-spacing: -.03em;
        }


        .section-description {
            margin:
                7px
                0
                0;

            color: var(--muted);

            font-size: 10px;

            line-height: 1.6;
        }


        /*
        |--------------------------------------------------------------------------
        | OPTIONS
        |--------------------------------------------------------------------------
        */

        .options {
            display: grid;

            gap: 12px;

            margin-top: 16px;
        }


        .download-option {
            display: grid;

            grid-template-columns:
                50px
                minmax(
                    0,
                    1fr
                )
                28px;

            gap: 13px;

            align-items: center;

            width: 100%;

            padding: 15px;

            border:
                1px solid
                var(--border);

            border-radius: 19px;

            background: white;

            color: inherit;

            text-align: left;

            cursor: pointer;

            box-shadow:
                0 4px 14px
                rgba(
                    18,
                    61,
                    44,
                    .035
                );

            transition:
                transform .12s ease,
                border-color .12s ease,
                box-shadow .12s ease;
        }


        .download-option:hover {
            border-color:
                rgba(
                    242,
                    99,
                    53,
                    .25
                );

            box-shadow:
                0 10px 24px
                rgba(
                    18,
                    61,
                    44,
                    .07
                );

            transform:
                translateY(-1px);
        }


        .download-option:active {
            transform:
                scale(.99);
        }


        .download-option:disabled {
            opacity: .45;

            cursor: not-allowed;

            transform: none;
        }


        .option-icon {
            display: flex;

            width: 50px;

            height: 50px;

            align-items: center;

            justify-content: center;

            border-radius: 15px;

            background: var(--orange-soft);

            color: var(--orange);
        }


        .option-icon.gpx {
            background: var(--green-soft);

            color: var(--green);
        }


        .option-icon svg {
            width: 22px;

            height: 22px;
        }


        .option-title {
            display: block;

            color: var(--green);

            font-size: 12px;

            font-weight: 900;
        }


        .option-description {
            display: block;

            margin-top: 4px;

            color: var(--muted);

            font-size: 8px;

            line-height: 1.55;
        }


        .option-arrow {
            display: flex;

            align-items: center;

            justify-content: center;

            color: #a1afa7;
        }


        .option-arrow svg {
            width: 18px;

            height: 18px;
        }


        /*
        |--------------------------------------------------------------------------
        | EMPTY
        |--------------------------------------------------------------------------
        */

        .empty-state {
            margin-top: 15px;

            padding: 16px;

            border:
                1px dashed
                rgba(
                    18,
                    61,
                    44,
                    .18
                );

            border-radius: 16px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .55
                );

            color: var(--muted);

            font-size: 9px;

            line-height: 1.6;

            text-align: center;
        }


        /*
        |--------------------------------------------------------------------------
        | INFO
        |--------------------------------------------------------------------------
        */

        .info {
            display: flex;

            gap: 10px;

            margin-top: 18px;

            padding: 14px;

            border-radius: 15px;

            background: #eef3ef;

            color: #718279;

            font-size: 8px;

            line-height: 1.6;
        }


        .info svg {
            width: 17px;

            height: 17px;

            flex: none;

            color: var(--green);
        }


        /*
        |--------------------------------------------------------------------------
        | TOAST
        |--------------------------------------------------------------------------
        */

        .toast {
            position: fixed;

            right: 16px;

            bottom:
                calc(
                    20px
                    +
                    env(safe-area-inset-bottom)
                );

            left: 16px;

            z-index: 100;

            max-width: 398px;

            margin: auto;

            padding:
                13px
                15px;

            border-radius: 14px;

            background: var(--green-dark);

            color: white;

            font-size: 9px;

            font-weight: 700;

            text-align: center;

            opacity: 0;

            pointer-events: none;

            transform:
                translateY(
                    12px
                );

            transition:
                opacity .18s ease,
                transform .18s ease;
        }


        .toast.show {
            opacity: 1;

            transform:
                translateY(
                    0
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DESKTOP
        |--------------------------------------------------------------------------
        */

        @media (
            min-width: 700px
        ) {
            body {
                padding:
                    32px
                    0;
            }


            .app {
                min-height:
                    calc(
                        100dvh
                        -
                        64px
                    );

                overflow: hidden;

                border:
                    1px solid
                    var(--border);

                border-radius: 28px;

                box-shadow:
                    0 20px 60px
                    rgba(
                        18,
                        61,
                        44,
                        .08
                    );
            }


            .header {
                border-radius:
                    28px
                    28px
                    0
                    0;
            }
        }
    </style>

</head>


<body>

<div class="app">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <header class="header">

        <a
            href="{{ url('/pendaki/jalur/' . $trail->id) }}"
            class="header-action"
            aria-label="Kembali ke detail jalur"
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

        </a>


        <div class="header-copy">

            <h1 class="header-title">
                Download Map Offline
            </h1>

            <p class="header-subtitle">
                BaliHiking
            </p>

        </div>


        <div></div>

    </header>


    {{-- ========================================================= --}}
    {{-- CONTENT --}}
    {{-- ========================================================= --}}

    <main class="main">

        {{-- ===================================================== --}}
        {{-- HERO --}}
        {{-- ===================================================== --}}

        <section class="hero">

            <div class="hero-content">

                <div class="hero-label">
                    Jalur Pendakian
                </div>


                <h2 class="hero-title">
                    {{ $trail->name ?? 'Jalur Pendakian' }}
                </h2>


                <p class="hero-description">
                    Simpan jalur pendakian untuk digunakan ketika
                    koneksi internet tidak tersedia selama perjalanan.
                </p>

            </div>

        </section>


        {{-- ===================================================== --}}
        {{-- SUMMARY --}}
        {{-- ===================================================== --}}

        <div class="stats">

            <div class="stat-card">

                <div class="stat-label">
                    Titik Jalur
                </div>

                <div class="stat-value">
                    {{ count($mapPoints ?? []) }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Checkpoint
                </div>

                <div class="stat-value">
                    {{ count($checkpointExport ?? []) }}
                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DOWNLOAD SECTION --}}
        {{-- ===================================================== --}}

        <section class="section">

            <div class="section-kicker">
                Download
            </div>


            <h2 class="section-title">
                Pilih Format Map
            </h2>


            <p class="section-description">
                Gunakan gambar PNG untuk referensi visual atau GPX
                untuk aplikasi navigasi dan perangkat GPS.
            </p>


            <div class="options">

                {{-- ================================================= --}}
                {{-- PNG --}}
                {{-- ================================================= --}}

                <button
                    type="button"
                    id="downloadPng"
                    class="download-option"
                    @disabled(empty($mapPoints))
                >

                    <span class="option-icon">

                        <svg
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <rect
                                x="3"
                                y="4"
                                width="18"
                                height="16"
                                rx="2"
                            />

                            <circle
                                cx="9"
                                cy="9"
                                r="2"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m21 15-5-5L5 20"
                            />
                        </svg>

                    </span>


                    <span>

                        <span class="option-title">
                            PNG Image Map
                        </span>

                        <span class="option-description">
                            Gambar jalur lengkap dengan titik start,
                            finish dan checkpoint.
                        </span>

                    </span>


                    <span class="option-arrow">

                        <svg
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m9 18 6-6-6-6"
                            />
                        </svg>

                    </span>

                </button>


                {{-- ================================================= --}}
                {{-- GPX --}}
                {{-- ================================================= --}}

                <button
                    type="button"
                    id="downloadGpx"
                    class="download-option"
                    @disabled(empty($mapPoints))
                >

                    <span class="option-icon gpx">

                        <svg
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 21s7-4.4 7-11a7 7 0 1 0-14 0c0 6.6 7 11 7 11Z"
                            />

                            <circle
                                cx="12"
                                cy="10"
                                r="2"
                            />
                        </svg>

                    </span>


                    <span>

                        <span class="option-title">
                            GPX Route
                        </span>

                        <span class="option-description">
                            File jalur GPS untuk aplikasi navigasi
                            dan perangkat yang mendukung GPX.
                        </span>

                    </span>


                    <span class="option-arrow">

                        <svg
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m9 18 6-6-6-6"
                            />
                        </svg>

                    </span>

                </button>

            </div>


            @if (empty($mapPoints))

                <div class="empty-state">
                    Koordinat jalur belum tersedia sehingga file PNG
                    dan GPX belum dapat dibuat.
                </div>

            @endif

        </section>


        {{-- ===================================================== --}}
        {{-- INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="info">

            <svg
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
                    stroke-linecap="round"
                    d="M12 11v5M12 8h.01"
                />
            </svg>


            <span>
                PNG dapat disimpan di galeri sebagai referensi jalur.
                GPX dapat diimpor ke aplikasi navigasi yang mendukung
                format GPX.
            </span>

        </div>

    </main>

</div>


{{-- =========================================================== --}}
{{-- TOAST --}}
{{-- =========================================================== --}}

<div
    id="toast"
    class="toast"
></div>


<script>
    /*
    |--------------------------------------------------------------------------
    | SERVER DATA
    |--------------------------------------------------------------------------
    */

    const trail =
        {{ \Illuminate\Support\Js::from($trailExport ?? []) }};


    const rawRoutePoints =
        {{ \Illuminate\Support\Js::from($mapPoints ?? []) }};


    const rawCheckpoints =
        {{ \Illuminate\Support\Js::from($checkpointExport ?? []) }};


    /*
    |--------------------------------------------------------------------------
    | DOM
    |--------------------------------------------------------------------------
    */

    const pngButton =
        document.getElementById(
            'downloadPng'
        );


    const gpxButton =
        document.getElementById(
            'downloadGpx'
        );


    const toastElement =
        document.getElementById(
            'toast'
        );


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE POINT
    |--------------------------------------------------------------------------
    */

    function normalizePoint(
        point
    ) {
        if (
            ! point
            ||
            typeof point
            !==
            'object'
        ) {
            return null;
        }


        const lat =
            Number(
                point.lat
                ??
                point.latitude
            );


        const lng =
            Number(
                point.lng
                ??
                point.lon
                ??
                point.longitude
            );


        if (
            ! Number.isFinite(
                lat
            )
            ||
            ! Number.isFinite(
                lng
            )
        ) {
            return null;
        }


        const elevation =
            Number(
                point.ele
                ??
                point.elevation
                ??
                point.elevation_m
            );


        return {
            ...point,

            lat,

            lng,

            ele:
                Number.isFinite(
                    elevation
                )
                    ? elevation
                    : null,
        };
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZED DATA
    |--------------------------------------------------------------------------
    */

    const routePoints =
        Array.isArray(
            rawRoutePoints
        )
            ? rawRoutePoints
                .map(
                    normalizePoint
                )
                .filter(
                    Boolean
                )
            : [];


    const checkpoints =
        Array.isArray(
            rawCheckpoints
        )
            ? rawCheckpoints
                .map(
                    normalizePoint
                )
                .filter(
                    Boolean
                )
            : [];


    /*
    |--------------------------------------------------------------------------
    | TOAST
    |--------------------------------------------------------------------------
    */

    let toastTimer =
        null;


    function showToast(
        message
    ) {
        if (! toastElement) {
            return;
        }


        window.clearTimeout(
            toastTimer
        );


        toastElement.textContent =
            message;


        toastElement.classList.add(
            'show'
        );


        toastTimer =
            window.setTimeout(
                () => {
                    toastElement.classList.remove(
                        'show'
                    );
                },
                2300
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD FILE
    |--------------------------------------------------------------------------
    */

    function downloadBlob(
        blob,
        filename
    ) {
        const objectUrl =
            URL.createObjectURL(
                blob
            );


        const link =
            document.createElement(
                'a'
            );


        link.href =
            objectUrl;


        link.download =
            filename;


        document.body.appendChild(
            link
        );


        link.click();


        link.remove();


        window.setTimeout(
            () => {
                URL.revokeObjectURL(
                    objectUrl
                );
            },
            1000
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SAFE FILE NAME
    |--------------------------------------------------------------------------
    */

    function downloadBaseName()
    {
        const value =
            String(
                trail.download_base_name
                ??
                trail.name
                ??
                'balihiking-route'
            );


        return value
            .trim()
            .replace(
                /[^a-zA-Z0-9-_]+/g,
                '-'
            )
            .replace(
                /^-+|-+$/g,
                ''
            )
            ||
            'balihiking-route';
    }


    /*
    |--------------------------------------------------------------------------
    | XML ESCAPE
    |--------------------------------------------------------------------------
    */

    function escapeXml(
        value
    ) {
        return String(
            value
            ??
            ''
        )
            .replaceAll(
                '&',
                '&amp;'
            )
            .replaceAll(
                '<',
                '&lt;'
            )
            .replaceAll(
                '>',
                '&gt;'
            )
            .replaceAll(
                '"',
                '&quot;'
            )
            .replaceAll(
                '\'',
                '&apos;'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GPX BUILDER
    |--------------------------------------------------------------------------
    |
    | Deklarasi XML sengaja dibentuk dari beberapa string agar tidak
    | ditafsirkan sebagai tag PHP oleh parser Blade/PHP.
    |--------------------------------------------------------------------------
    */

    function buildGpx()
    {
        if (
            routePoints.length
            ===
            0
        ) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | XML DECLARATION
        |--------------------------------------------------------------------------
        */

        const xmlDeclaration =
            '<'
            +
            '?xml version="1.0" encoding="UTF-8"?'
            +
            '>';


        /*
        |--------------------------------------------------------------------------
        | TRAIL INFORMATION
        |--------------------------------------------------------------------------
        */

        const trailName =
            escapeXml(
                trail.name
                ??
                'Jalur Pendakian'
            );


        const mountainName =
            escapeXml(
                trail.mountain
                ??
                'BaliHiking'
            );


        /*
        |--------------------------------------------------------------------------
        | WAYPOINTS
        |--------------------------------------------------------------------------
        */

        const waypoints =
            checkpoints
                .map(
                    (
                        checkpoint,
                        index
                    ) => {
                        const name =
                            escapeXml(
                                checkpoint.name
                                ??
                                `Checkpoint ${index + 1}`
                            );


                        const type =
                            escapeXml(
                                checkpoint.type
                                ??
                                'checkpoint'
                            );


                        const elevation =
                            checkpoint.ele
                            !==
                            null
                                ? `        <ele>${checkpoint.ele}</ele>`
                                : null;


                        return [
                            `    <wpt lat="${checkpoint.lat}" lon="${checkpoint.lng}">`,

                            elevation,

                            `        <name>${name}</name>`,

                            `        <type>${type}</type>`,

                            '    </wpt>',
                        ]
                            .filter(
                                Boolean
                            )
                            .join(
                                '\n'
                            );
                    }
                );


        /*
        |--------------------------------------------------------------------------
        | TRACK POINTS
        |--------------------------------------------------------------------------
        */

        const trackPoints =
            routePoints
                .map(
                    (
                        point
                    ) => {
                        const elevation =
                            point.ele
                            !==
                            null
                                ? `                <ele>${point.ele}</ele>`
                                : null;


                        return [
                            `            <trkpt lat="${point.lat}" lon="${point.lng}">`,

                            elevation,

                            '            </trkpt>',
                        ]
                            .filter(
                                Boolean
                            )
                            .join(
                                '\n'
                            );
                    }
                );


        /*
        |--------------------------------------------------------------------------
        | GPX
        |--------------------------------------------------------------------------
        */

        const lines = [
            xmlDeclaration,

            '<gpx',

            '    version="1.1"',

            '    creator="BaliHiking"',

            '    xmlns="http://www.topografix.com/GPX/1/1"',

            '    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"',

            '    xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd"',

            '>',

            '    <metadata>',

            `        <name>${trailName}</name>`,

            `        <desc>${mountainName} - BaliHiking</desc>`,

            '    </metadata>',

            '',

            ...waypoints,

            '',

            '    <trk>',

            `        <name>${trailName}</name>`,

            '        <type>hiking</type>',

            '        <trkseg>',

            ...trackPoints,

            '        </trkseg>',

            '    </trk>',

            '</gpx>',
        ];


        return lines
            .filter(
                (
                    line
                ) =>
                    line
                    !==
                    null
                    &&
                    line
                    !==
                    undefined
            )
            .join(
                '\n'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD GPX
    |--------------------------------------------------------------------------
    */

    gpxButton?.addEventListener(
        'click',
        () => {
            try {
                const gpx =
                    buildGpx();


                if (! gpx) {
                    showToast(
                        'Data jalur belum tersedia.'
                    );

                    return;
                }


                const blob =
                    new Blob(
                        [
                            gpx
                        ],
                        {
                            type:
                                'application/gpx+xml;charset=utf-8',
                        }
                    );


                downloadBlob(
                    blob,
                    `${downloadBaseName()}.gpx`
                );


                showToast(
                    'File GPX berhasil disiapkan.'
                );

            } catch (
                error
            ) {
                console.error(
                    'GPX error:',
                    error
                );


                showToast(
                    'File GPX gagal dibuat.'
                );
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | MAP PROJECTION
    |--------------------------------------------------------------------------
    */

    function createProjector(
        points,
        width,
        height,
        padding
    ) {
        const latitudes =
            points.map(
                (
                    point
                ) =>
                    point.lat
            );


        const longitudes =
            points.map(
                (
                    point
                ) =>
                    point.lng
            );


        let minLat =
            Math.min(
                ...latitudes
            );


        let maxLat =
            Math.max(
                ...latitudes
            );


        let minLng =
            Math.min(
                ...longitudes
            );


        let maxLng =
            Math.max(
                ...longitudes
            );


        /*
        |--------------------------------------------------------------------------
        | ZERO RANGE PROTECTION
        |--------------------------------------------------------------------------
        */

        if (
            maxLat
            ===
            minLat
        ) {
            maxLat += .001;

            minLat -= .001;
        }


        if (
            maxLng
            ===
            minLng
        ) {
            maxLng += .001;

            minLng -= .001;
        }


        /*
        |--------------------------------------------------------------------------
        | MARGIN
        |--------------------------------------------------------------------------
        */

        const latMargin =
            (
                maxLat
                -
                minLat
            )
            *
            .12;


        const lngMargin =
            (
                maxLng
                -
                minLng
            )
            *
            .12;


        minLat -=
            latMargin;


        maxLat +=
            latMargin;


        minLng -=
            lngMargin;


        maxLng +=
            lngMargin;


        const drawableWidth =
            width
            -
            (
                padding
                *
                2
            );


        const drawableHeight =
            height
            -
            (
                padding
                *
                2
            );


        return {
            project(
                point
            ) {
                const x =
                    padding
                    +
                    (
                        (
                            point.lng
                            -
                            minLng
                        )
                        /
                        (
                            maxLng
                            -
                            minLng
                        )
                    )
                    *
                    drawableWidth;


                const y =
                    padding
                    +
                    (
                        1
                        -
                        (
                            (
                                point.lat
                                -
                                minLat
                            )
                            /
                            (
                                maxLat
                                -
                                minLat
                            )
                        )
                    )
                    *
                    drawableHeight;


                return {
                    x,

                    y,
                };
            },

            bounds: {
                minLat,

                maxLat,

                minLng,

                maxLng,
            },
        };
    }


    /*
    |--------------------------------------------------------------------------
    | ROUNDED RECTANGLE
    |--------------------------------------------------------------------------
    */

    function roundedRectangle(
        ctx,
        x,
        y,
        width,
        height,
        radius
    ) {
        const r =
            Math.min(
                radius,
                width / 2,
                height / 2
            );


        ctx.beginPath();


        ctx.moveTo(
            x + r,
            y
        );


        ctx.arcTo(
            x + width,
            y,
            x + width,
            y + height,
            r
        );


        ctx.arcTo(
            x + width,
            y + height,
            x,
            y + height,
            r
        );


        ctx.arcTo(
            x,
            y + height,
            x,
            y,
            r
        );


        ctx.arcTo(
            x,
            y,
            x + width,
            y,
            r
        );


        ctx.closePath();
    }


    /*
    |--------------------------------------------------------------------------
    | DRAW MARKER
    |--------------------------------------------------------------------------
    */

    function drawMarker(
        ctx,
        x,
        y,
        color,
        radius = 12
    ) {
        ctx.beginPath();


        ctx.arc(
            x,
            y,
            radius,
            0,
            Math.PI * 2
        );


        ctx.fillStyle =
            color;


        ctx.fill();


        ctx.lineWidth =
            5;


        ctx.strokeStyle =
            '#FFFFFF';


        ctx.stroke();
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD PNG MAP
    |--------------------------------------------------------------------------
    */

    function buildPngMap()
    {
        if (
            routePoints.length
            ===
            0
        ) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | CANVAS
        |--------------------------------------------------------------------------
        */

        const canvas =
            document.createElement(
                'canvas'
            );


        canvas.width =
            1600;


        canvas.height =
            1000;


        const ctx =
            canvas.getContext(
                '2d'
            );


        if (! ctx) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | BACKGROUND
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            '#F6F7F3';


        ctx.fillRect(
            0,
            0,
            canvas.width,
            canvas.height
        );


        /*
        |--------------------------------------------------------------------------
        | TOP HEADER
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            '#123D2C';


        ctx.fillRect(
            0,
            0,
            canvas.width,
            135
        );


        ctx.fillStyle =
            '#78DDB7';


        ctx.font =
            '800 17px system-ui, -apple-system, sans-serif';


        ctx.fillText(
            'BALI HIKING • OFFLINE ROUTE MAP',
            70,
            45
        );


        ctx.fillStyle =
            '#FFFFFF';


        ctx.font =
            '800 35px system-ui, -apple-system, sans-serif';


        ctx.fillText(
            String(
                trail.name
                ??
                'Jalur Pendakian'
            ),
            70,
            93
        );


        ctx.textAlign =
            'right';


        ctx.fillStyle =
            '#A5BEB3';


        ctx.font =
            '500 16px system-ui, -apple-system, sans-serif';


        ctx.fillText(
            String(
                trail.mountain
                ??
                'BaliHiking'
            ),
            1530,
            76
        );


        ctx.textAlign =
            'left';


        /*
        |--------------------------------------------------------------------------
        | MAP FRAME
        |--------------------------------------------------------------------------
        */

        const mapX =
            64;


        const mapY =
            170;


        const mapWidth =
            1472;


        const mapHeight =
            700;


        roundedRectangle(
            ctx,
            mapX,
            mapY,
            mapWidth,
            mapHeight,
            25
        );


        ctx.fillStyle =
            '#FFFFFF';


        ctx.fill();


        ctx.save();


        roundedRectangle(
            ctx,
            mapX,
            mapY,
            mapWidth,
            mapHeight,
            25
        );


        ctx.clip();


        /*
        |--------------------------------------------------------------------------
        | SOFT MAP BACKGROUND
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            '#F8F9F5';


        ctx.fillRect(
            mapX,
            mapY,
            mapWidth,
            mapHeight
        );


        /*
        |--------------------------------------------------------------------------
        | GRID
        |--------------------------------------------------------------------------
        */

        ctx.strokeStyle =
            '#E8ECE6';


        ctx.lineWidth =
            1;


        const gridSize =
            55;


        for (
            let x = mapX;
            x <= mapX + mapWidth;
            x += gridSize
        ) {
            ctx.beginPath();


            ctx.moveTo(
                x,
                mapY
            );


            ctx.lineTo(
                x,
                mapY + mapHeight
            );


            ctx.stroke();
        }


        for (
            let y = mapY;
            y <= mapY + mapHeight;
            y += gridSize
        ) {
            ctx.beginPath();


            ctx.moveTo(
                mapX,
                y
            );


            ctx.lineTo(
                mapX + mapWidth,
                y
            );


            ctx.stroke();
        }


        /*
        |--------------------------------------------------------------------------
        | PROJECTION
        |--------------------------------------------------------------------------
        */

        const allPoints =
            [
                ...routePoints,

                ...checkpoints,
            ];


        const projector =
            createProjector(
                allPoints,
                mapWidth,
                mapHeight,
                95
            );


        function project(
            point
        ) {
            const projected =
                projector.project(
                    point
                );


            return {
                x:
                    mapX
                    +
                    projected.x,

                y:
                    mapY
                    +
                    projected.y,
            };
        }


        /*
        |--------------------------------------------------------------------------
        | ROUTE WHITE BORDER
        |--------------------------------------------------------------------------
        */

        ctx.beginPath();


        routePoints.forEach(
            (
                point,
                index
            ) => {
                const projected =
                    project(
                        point
                    );


                if (
                    index
                    ===
                    0
                ) {
                    ctx.moveTo(
                        projected.x,
                        projected.y
                    );
                } else {
                    ctx.lineTo(
                        projected.x,
                        projected.y
                    );
                }
            }
        );


        ctx.strokeStyle =
            '#FFFFFF';


        ctx.lineWidth =
            17;


        ctx.lineJoin =
            'round';


        ctx.lineCap =
            'round';


        ctx.stroke();


        /*
        |--------------------------------------------------------------------------
        | ROUTE
        |--------------------------------------------------------------------------
        */

        ctx.beginPath();


        routePoints.forEach(
            (
                point,
                index
            ) => {
                const projected =
                    project(
                        point
                    );


                if (
                    index
                    ===
                    0
                ) {
                    ctx.moveTo(
                        projected.x,
                        projected.y
                    );
                } else {
                    ctx.lineTo(
                        projected.x,
                        projected.y
                    );
                }
            }
        );


        ctx.strokeStyle =
            '#123D2C';


        ctx.lineWidth =
            8;


        ctx.stroke();


        /*
        |--------------------------------------------------------------------------
        | CHECKPOINTS
        |--------------------------------------------------------------------------
        */

        checkpoints.forEach(
            (
                checkpoint,
                index
            ) => {
                const projected =
                    project(
                        checkpoint
                    );


                drawMarker(
                    ctx,
                    projected.x,
                    projected.y,
                    '#F26335',
                    10
                );


                const name =
                    String(
                        checkpoint.name
                        ??
                        `Checkpoint ${index + 1}`
                    );


                const shortName =
                    name.length
                    >
                    23
                        ? `${name.slice(0, 21)}…`
                        : name;


                ctx.font =
                    '700 15px system-ui, -apple-system, sans-serif';


                const textWidth =
                    ctx.measureText(
                        shortName
                    ).width;


                const labelX =
                    projected.x
                    +
                    18;


                const labelY =
                    projected.y
                    -
                    17;


                roundedRectangle(
                    ctx,
                    labelX,
                    labelY,
                    textWidth + 24,
                    34,
                    9
                );


                ctx.fillStyle =
                    'rgba(255,255,255,.96)';


                ctx.fill();


                ctx.fillStyle =
                    '#123D2C';


                ctx.fillText(
                    shortName,
                    labelX + 12,
                    labelY + 22
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | START
        |--------------------------------------------------------------------------
        */

        const start =
            project(
                routePoints[
                    0
                ]
            );


        drawMarker(
            ctx,
            start.x,
            start.y,
            '#16A66A',
            15
        );


        /*
        |--------------------------------------------------------------------------
        | FINISH
        |--------------------------------------------------------------------------
        */

        const finish =
            project(
                routePoints[
                    routePoints.length
                    -
                    1
                ]
            );


        drawMarker(
            ctx,
            finish.x,
            finish.y,
            '#F26335',
            15
        );


        /*
        |--------------------------------------------------------------------------
        | NORTH INDICATOR
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            '#123D2C';


        ctx.font =
            '900 22px system-ui, -apple-system, sans-serif';


        ctx.fillText(
            'N',
            mapX + mapWidth - 66,
            mapY + 54
        );


        ctx.beginPath();


        ctx.moveTo(
            mapX + mapWidth - 55,
            mapY + 67
        );


        ctx.lineTo(
            mapX + mapWidth - 66,
            mapY + 93
        );


        ctx.lineTo(
            mapX + mapWidth - 44,
            mapY + 93
        );


        ctx.closePath();


        ctx.fill();


        ctx.restore();


        /*
        |--------------------------------------------------------------------------
        | LEGEND
        |--------------------------------------------------------------------------
        */

        drawMarker(
            ctx,
            79,
            923,
            '#16A66A',
            7
        );


        ctx.fillStyle =
            '#64766D';


        ctx.font =
            '600 14px system-ui, -apple-system, sans-serif';


        ctx.fillText(
            'Start',
            96,
            928
        );


        drawMarker(
            ctx,
            172,
            923,
            '#F26335',
            7
        );


        ctx.fillText(
            'Finish',
            189,
            928
        );


        /*
        |--------------------------------------------------------------------------
        | COORDINATES
        |--------------------------------------------------------------------------
        */

        ctx.fillStyle =
            '#89998F';


        ctx.font =
            '500 12px system-ui, -apple-system, sans-serif';


        const startText =
            `Start ${routePoints[0].lat.toFixed(6)}, ${routePoints[0].lng.toFixed(6)}`;


        ctx.fillText(
            startText,
            70,
            970
        );


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        ctx.textAlign =
            'right';


        ctx.fillStyle =
            '#98A69F';


        ctx.font =
            '500 13px system-ui, -apple-system, sans-serif';


        ctx.fillText(
            'Generated by BaliHiking',
            1530,
            928
        );


        ctx.fillText(
            'Gunakan jalur ini sebagai referensi pendakian offline.',
            1530,
            955
        );


        ctx.textAlign =
            'left';


        return canvas;
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PNG
    |--------------------------------------------------------------------------
    */

    pngButton?.addEventListener(
        'click',
        () => {
            if (
                routePoints.length
                ===
                0
            ) {
                showToast(
                    'Data jalur belum tersedia.'
                );

                return;
            }


            pngButton.disabled =
                true;


            showToast(
                'Menyiapkan PNG map...'
            );


            window.requestAnimationFrame(
                () => {
                    try {
                        const canvas =
                            buildPngMap();


                        if (! canvas) {
                            showToast(
                                'PNG map gagal dibuat.'
                            );

                            return;
                        }


                        canvas.toBlob(
                            (
                                blob
                            ) => {
                                if (! blob) {
                                    showToast(
                                        'PNG map gagal dibuat.'
                                    );

                                    return;
                                }


                                downloadBlob(
                                    blob,
                                    `${downloadBaseName()}-map.png`
                                );


                                showToast(
                                    'PNG map berhasil disiapkan.'
                                );
                            },
                            'image/png',
                            1
                        );

                    } catch (
                        error
                    ) {
                        console.error(
                            'PNG error:',
                            error
                        );


                        showToast(
                            'PNG map gagal dibuat.'
                        );

                    } finally {
                        pngButton.disabled =
                            false;
                    }
                }
            );
        }
    );
</script>

</body>

</html>