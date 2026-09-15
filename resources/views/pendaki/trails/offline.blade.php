<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Peta Offline - {{ $trail->name }}
    </title>

    <style>
        * {
            box-sizing:
                border-box;
        }

        body {
            margin: 0;

            padding: 24px;

            background:
                #f6f7f5;

            color:
                #1a382b;

            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        .container {
            width: 100%;

            max-width:
                1100px;

            margin:
                0 auto;
        }

        .header {
            display:
                flex;

            align-items:
                flex-start;

            justify-content:
                space-between;

            gap:
                20px;

            margin-bottom:
                18px;
        }

        .eyebrow {
            margin:
                0 0 6px;

            color:
                #f06535;

            font-size:
                12px;

            font-weight:
                700;

            text-transform:
                uppercase;
        }

        h1 {
            margin: 0;

            font-size:
                26px;
        }

        .mountain {
            margin-top:
                6px;

            color:
                #67736d;

            font-size:
                13px;
        }

        .offline-badge {
            padding:
                7px 11px;

            border-radius:
                999px;

            background:
                #e9f6ef;

            color:
                #137a49;

            font-size:
                11px;

            font-weight:
                700;
        }

        .map-card {
            overflow:
                hidden;

            border:
                1px solid #dfe4e1;

            border-radius:
                18px;

            background:
                #ffffff;
        }

        #mapSvg {
            display:
                block;

            width:
                100%;

            min-height:
                480px;

            background:
                #eef2ed;
        }

        .stats {
            display:
                grid;

            grid-template-columns:
                repeat(
                    3,
                    1fr
                );

            border-top:
                1px solid #e4e7e5;
        }

        .stat {
            padding:
                16px;

            text-align:
                center;

            border-right:
                1px solid #e4e7e5;
        }

        .stat:last-child {
            border-right:
                none;
        }

        .stat-label {
            color:
                #839089;

            font-size:
                10px;
        }

        .stat-value {
            margin-top:
                5px;

            font-size:
                15px;

            font-weight:
                700;
        }

        .actions {
            display:
                flex;

            flex-wrap:
                wrap;

            gap:
                8px;

            margin:
                15px 0;
        }

        button {
            min-height:
                39px;

            padding:
                0 15px;

            border:
                1px solid #dce1de;

            border-radius:
                10px;

            background:
                white;

            color:
                #1a382b;

            cursor:
                pointer;

            font-size:
                12px;

            font-weight:
                700;
        }

        button.primary {
            border-color:
                #1a382b;

            background:
                #1a382b;

            color:
                white;
        }

        .section {
            margin-top:
                20px;

            padding:
                18px;

            border:
                1px solid #e0e4e2;

            border-radius:
                16px;

            background:
                white;
        }

        .section h2 {
            margin:
                0 0 13px;

            font-size:
                15px;
        }

        .checkpoint {
            display:
                flex;

            align-items:
                center;

            gap:
                12px;

            padding:
                11px 0;

            border-bottom:
                1px solid #eef0ef;
        }

        .checkpoint:last-child {
            border-bottom:
                0;
        }

        .number {
            display:
                flex;

            width:
                29px;

            height:
                29px;

            flex-shrink:
                0;

            align-items:
                center;

            justify-content:
                center;

            border-radius:
                50%;

            background:
                #1a382b;

            color:
                white;

            font-size:
                10px;

            font-weight:
                700;
        }

        .checkpoint-title {
            font-size:
                12px;

            font-weight:
                700;
        }

        .checkpoint-subtitle {
            margin-top:
                3px;

            color:
                #89938e;

            font-size:
                10px;
        }

        .notice {
            margin-top:
                15px;

            padding:
                12px 14px;

            border-radius:
                12px;

            background:
                #fff4ee;

            color:
                #9c4a29;

            font-size:
                11px;

            line-height:
                1.6;
        }

        .footer {
            margin-top:
                18px;

            color:
                #929c96;

            font-size:
                10px;

            text-align:
                center;
        }

        @media (
            max-width: 600px
        ) {
            body {
                padding:
                    12px;
            }

            .header {
                display:
                    block;
            }

            .offline-badge {
                display:
                    inline-block;

                margin-top:
                    12px;
            }

            h1 {
                font-size:
                    20px;
            }

            #mapSvg {
                min-height:
                    420px;
            }
        }

        @media print {
            body {
                padding: 0;

                background:
                    white;
            }

            .actions {
                display:
                    none;
            }

            .map-card,
            .section {
                break-inside:
                    avoid;
            }
        }
    </style>
</head>


<body>

<div class="container">

    <div class="header">

        <div>
            <p class="eyebrow">
                Jalur Bali
            </p>

            <h1>
                {{ $trail->name }}
            </h1>

            <div class="mountain">
                {{ $trail->mountain?->name }}

                @if($trail->mountain?->location)
                    · {{ $trail->mountain->location }}
                @endif
            </div>
        </div>


        <span class="offline-badge">
            PETA OFFLINE
        </span>

    </div>


    <div class="actions">

        <button
            type="button"
            onclick="zoomMap(0.8)"
        >
            + Perbesar
        </button>

        <button
            type="button"
            onclick="zoomMap(1.25)"
        >
            − Perkecil
        </button>

        <button
            type="button"
            onclick="resetMap()"
        >
            Reset
        </button>

        <button
            type="button"
            class="primary"
            onclick="window.print()"
        >
            Cetak / Simpan PDF
        </button>

    </div>


    <div class="map-card">

        <svg
            id="mapSvg"
            viewBox="0 0 1000 700"
            xmlns="http://www.w3.org/2000/svg"
        >
        </svg>


        <div class="stats">

            <div class="stat">
                <div class="stat-label">
                    Jarak
                </div>

                <div class="stat-value">
                    {{ $trail->distance_km
                        ? number_format(
                            $trail->distance_km,
                            1,
                            ',',
                            '.'
                        ) . ' km'
                        : '-'
                    }}
                </div>
            </div>


            <div class="stat">
                <div class="stat-label">
                    Estimasi
                </div>

                <div class="stat-value">
                    {{ $trail->estimated_time_hours
                        ? $trail->estimated_time_hours . ' jam'
                        : '-'
                    }}
                </div>
            </div>


            <div class="stat">
                <div class="stat-label">
                    Kesulitan
                </div>

                <div class="stat-value">
                    {{ $trail->difficulty
                        ? ucfirst($trail->difficulty)
                        : '-'
                    }}
                </div>
            </div>

        </div>

    </div>


    <div class="notice">
        File ini menyimpan data jalur secara lokal dan tidak membutuhkan
        koneksi internet. Gunakan sebagai referensi pendamping. Kondisi
        medan aktual dapat berubah sehingga tetap perhatikan rambu dan
        arahan petugas di lokasi.
    </div>


    @if(count($checkpoints) > 0)

        <section class="section">

            <h2>
                Checkpoint Jalur
            </h2>


            @foreach($checkpoints as $checkpoint)

                <div class="checkpoint">

                    <div class="number">
                        {{ $loop->iteration }}
                    </div>


                    <div>
                        <div class="checkpoint-title">
                            {{ $checkpoint['name'] }}
                        </div>


                        <div class="checkpoint-subtitle">
                            {{ $checkpoint['type'] ?: 'Checkpoint' }}

                            @if($checkpoint['elevation_m'])

                                ·

                                {{ number_format(
                                    $checkpoint['elevation_m'],
                                    0,
                                    ',',
                                    '.'
                                ) }}

                                mdpl

                            @endif
                        </div>
                    </div>

                </div>

            @endforeach

        </section>

    @endif


    <div class="footer">
        Diunduh dari Jalur Bali ·
        {{ now()->locale('id')->translatedFormat('d F Y H:i') }}
    </div>

</div>


<script>
    /*
    |--------------------------------------------------------------------------
    | DATA TERSIMPAN LANGSUNG DI FILE
    |--------------------------------------------------------------------------
    */

    const routeCoordinates =
        @json($routeCoordinates);

    const checkpoints =
        @json($checkpoints);


    const svg =
        document.getElementById(
            'mapSvg'
        );


    const WIDTH =
        1000;

    const HEIGHT =
        700;

    const PADDING =
        85;


    let initialViewBox = {
        x: 0,
        y: 0,
        width: WIDTH,
        height: HEIGHT,
    };


    let currentViewBox = {
        ...initialViewBox
    };


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    function svgElement(
        type,
        attributes = {}
    ) {
        const element =
            document.createElementNS(
                'http://www.w3.org/2000/svg',
                type
            );


        Object
            .entries(
                attributes
            )
            .forEach(
                ([key, value]) => {
                    element.setAttribute(
                        key,
                        value
                    );
                }
            );


        return element;
    }


    /*
    |--------------------------------------------------------------------------
    | EMPTY DATA
    |--------------------------------------------------------------------------
    */

    if (
        !Array.isArray(
            routeCoordinates
        )
        ||
        routeCoordinates.length < 2
    ) {

        const text =
            svgElement(
                'text',
                {
                    x:
                        WIDTH / 2,

                    y:
                        HEIGHT / 2,

                    'text-anchor':
                        'middle',

                    fill:
                        '#7b8780',

                    'font-size':
                        22,

                    'font-family':
                        'Arial',
                }
            );


        text.textContent =
            'Data koordinat jalur belum tersedia.';


        svg.appendChild(
            text
        );

    } else {

        renderMap();

    }


    /*
    |--------------------------------------------------------------------------
    | RENDER MAP
    |--------------------------------------------------------------------------
    */

    function renderMap() {

        const allPoints = [
            ...routeCoordinates,

            ...checkpoints.map(
                checkpoint => [
                    checkpoint.latitude,
                    checkpoint.longitude
                ]
            ),
        ];


        const latitudes =
            allPoints.map(
                point =>
                    Number(
                        point[0]
                    )
            );


        const longitudes =
            allPoints.map(
                point =>
                    Number(
                        point[1]
                    )
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
         * Hindari pembagian nol.
         */
        if (
            maxLat === minLat
        ) {
            maxLat +=
                0.00001;

            minLat -=
                0.00001;
        }


        if (
            maxLng === minLng
        ) {
            maxLng +=
                0.00001;

            minLng -=
                0.00001;
        }


        /*
        |--------------------------------------------------------------------------
        | PROJECTION
        |--------------------------------------------------------------------------
        */

        function project(
            latitude,
            longitude
        ) {

            const x =
                PADDING
                +
                (
                    (
                        longitude
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
                (
                    WIDTH
                    -
                    PADDING
                    *
                    2
                );


            const y =
                PADDING
                +
                (
                    (
                        maxLat
                        -
                        latitude
                    )
                    /
                    (
                        maxLat
                        -
                        minLat
                    )
                )
                *
                (
                    HEIGHT
                    -
                    PADDING
                    *
                    2
                );


            return {
                x,
                y
            };

        }


        /*
        |--------------------------------------------------------------------------
        | BACKGROUND
        |--------------------------------------------------------------------------
        */

        svg.appendChild(
            svgElement(
                'rect',
                {
                    x:
                        0,

                    y:
                        0,

                    width:
                        WIDTH,

                    height:
                        HEIGHT,

                    fill:
                        '#eef2ed',
                }
            )
        );


        /*
        |--------------------------------------------------------------------------
        | GRID
        |--------------------------------------------------------------------------
        */

        for (
            let i = 1;
            i < 8;
            i++
        ) {

            const x =
                (
                    WIDTH / 8
                )
                *
                i;


            const y =
                (
                    HEIGHT / 8
                )
                *
                i;


            svg.appendChild(
                svgElement(
                    'line',
                    {
                        x1:
                            x,

                        y1:
                            0,

                        x2:
                            x,

                        y2:
                            HEIGHT,

                        stroke:
                            '#dfe5e1',

                        'stroke-width':
                            1,
                    }
                )
            );


            svg.appendChild(
                svgElement(
                    'line',
                    {
                        x1:
                            0,

                        y1:
                            y,

                        x2:
                            WIDTH,

                        y2:
                            y,

                        stroke:
                            '#dfe5e1',

                        'stroke-width':
                            1,
                    }
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | NORTH ARROW
        |--------------------------------------------------------------------------
        */

        const north =
            svgElement(
                'text',
                {
                    x:
                        WIDTH - 60,

                    y:
                        45,

                    'text-anchor':
                        'middle',

                    fill:
                        '#1a382b',

                    'font-size':
                        17,

                    'font-weight':
                        'bold',
                }
            );


        north.textContent =
            'N ↑';


        svg.appendChild(
            north
        );


        /*
        |--------------------------------------------------------------------------
        | ROUTE
        |--------------------------------------------------------------------------
        */

        const points =
            routeCoordinates
                .map(
                    coordinate => {

                        const p =
                            project(
                                Number(
                                    coordinate[0]
                                ),

                                Number(
                                    coordinate[1]
                                )
                            );


                        return `${p.x},${p.y}`;

                    }
                )
                .join(' ');


        /*
         * Outline
         */
        svg.appendChild(
            svgElement(
                'polyline',
                {
                    points,

                    fill:
                        'none',

                    stroke:
                        '#ffffff',

                    'stroke-width':
                        15,

                    'stroke-linecap':
                        'round',

                    'stroke-linejoin':
                        'round',
                }
            )
        );


        /*
         * Main line
         */
        svg.appendChild(
            svgElement(
                'polyline',
                {
                    points,

                    fill:
                        'none',

                    stroke:
                        '#1a382b',

                    'stroke-width':
                        8,

                    'stroke-linecap':
                        'round',

                    'stroke-linejoin':
                        'round',
                }
            )
        );


        /*
        |--------------------------------------------------------------------------
        | START
        |--------------------------------------------------------------------------
        */

        const start =
            project(
                Number(
                    routeCoordinates[0][0]
                ),

                Number(
                    routeCoordinates[0][1]
                )
            );


        drawPoint(
            start.x,
            start.y,
            '#f06535',
            'Start'
        );


        /*
        |--------------------------------------------------------------------------
        | FINISH
        |--------------------------------------------------------------------------
        */

        const finishCoordinate =
            routeCoordinates[
                routeCoordinates.length - 1
            ];


        const finish =
            project(
                Number(
                    finishCoordinate[0]
                ),

                Number(
                    finishCoordinate[1]
                )
            );


        drawPoint(
            finish.x,
            finish.y,
            '#1a382b',
            'Puncak'
        );


        /*
        |--------------------------------------------------------------------------
        | CHECKPOINT
        |--------------------------------------------------------------------------
        */

        checkpoints.forEach(
            (
                checkpoint,
                index
            ) => {

                const point =
                    project(
                        Number(
                            checkpoint.latitude
                        ),

                        Number(
                            checkpoint.longitude
                        )
                    );


                const circle =
                    svgElement(
                        'circle',
                        {
                            cx:
                                point.x,

                            cy:
                                point.y,

                            r:
                                13,

                            fill:
                                '#ffffff',

                            stroke:
                                '#1a382b',

                            'stroke-width':
                                5,
                        }
                    );


                svg.appendChild(
                    circle
                );


                const text =
                    svgElement(
                        'text',
                        {
                            x:
                                point.x,

                            y:
                                point.y + 4,

                            'text-anchor':
                                'middle',

                            fill:
                                '#1a382b',

                            'font-size':
                                10,

                            'font-weight':
                                'bold',
                        }
                    );


                text.textContent =
                    index + 1;


                svg.appendChild(
                    text
                );


                drawLabel(
                    point.x,
                    point.y - 24,
                    checkpoint.name
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DRAW POINT
    |--------------------------------------------------------------------------
    */

    function drawPoint(
        x,
        y,
        color,
        label
    ) {

        svg.appendChild(
            svgElement(
                'circle',
                {
                    cx:
                        x,

                    cy:
                        y,

                    r:
                        13,

                    fill:
                        color,

                    stroke:
                        '#ffffff',

                    'stroke-width':
                        5,
                }
            )
        );


        drawLabel(
            x,
            y - 25,
            label
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DRAW LABEL
    |--------------------------------------------------------------------------
    */

    function drawLabel(
        x,
        y,
        value
    ) {

        const text =
            svgElement(
                'text',
                {
                    x,
                    y,

                    'text-anchor':
                        'middle',

                    fill:
                        '#1a382b',

                    'font-size':
                        12,

                    'font-weight':
                        'bold',

                    'paint-order':
                        'stroke',

                    stroke:
                        '#ffffff',

                    'stroke-width':
                        5,

                    'stroke-linejoin':
                        'round',
                }
            );


        text.textContent =
            value;


        svg.appendChild(
            text
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ZOOM
    |--------------------------------------------------------------------------
    */

    function zoomMap(
        factor
    ) {

        const centerX =
            currentViewBox.x
            +
            currentViewBox.width
            /
            2;


        const centerY =
            currentViewBox.y
            +
            currentViewBox.height
            /
            2;


        const width =
            currentViewBox.width
            *
            factor;


        const height =
            currentViewBox.height
            *
            factor;


        currentViewBox = {

            x:
                centerX
                -
                width / 2,

            y:
                centerY
                -
                height / 2,

            width,

            height,

        };


        applyViewBox();

    }


    function resetMap() {

        currentViewBox = {
            ...initialViewBox
        };


        applyViewBox();

    }


    function applyViewBox() {

        svg.setAttribute(
            'viewBox',
            [
                currentViewBox.x,
                currentViewBox.y,
                currentViewBox.width,
                currentViewBox.height,
            ].join(' ')
        );

    }

</script>

</body>

</html>