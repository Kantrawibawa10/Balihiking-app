<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $trail->name }} - Jalur Bali
    </title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Leaflet --}}
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    ></script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Google Font --}}
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
                            'sans-serif',
                        ],
                    },
                },
            },
        };
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fbfbfa;
        }

        #trailMap {
            width: 100%;
            height: 360px;
            background: #f3f4f3;
        }

        .leaflet-container {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .trail-marker {
            background: transparent !important;
            border: 0 !important;
        }

        .trail-dot {
            width: 22px;
            height: 22px;
            border: 3px solid white;
            border-radius: 999px;
            background: #1a382b;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, .25);
        }

        .trail-dot.start {
            background: #f06535;
        }

        .checkpoint-marker {
            display: flex;

            width: 25px;
            height: 25px;

            align-items: center;
            justify-content: center;

            border: 3px solid white;
            border-radius: 999px;

            background: #1a382b;
            color: white;

            font-size: 9px;
            font-weight: 800;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, .2);
        }

        summary::-webkit-details-marker {
            display: none;
        }

        @media (max-width: 640px) {
            #trailMap {
                height: 310px;
            }
        }
    </style>
</head>


<body
    class="
        bg-brand-cream
        pb-28
        text-brand-dark
        antialiased
    "
>

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <header
        class="
            sticky
            top-0
            z-40

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
                h-16
                max-w-3xl
                items-center
                gap-3

                px-4
            "
        >

            <a
                href="{{ route('pendaki.mountain.show', $trail->mountain) }}"
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center

                    rounded-lg

                    transition

                    hover:bg-brand-dark/5
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


            <div class="min-w-0">

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wide
                        text-brand-dark/40
                    "
                >
                    Peta Jalur Pendakian
                </p>


                <h1
                    class="
                        truncate
                        text-sm
                        font-extrabold
                    "
                >
                    {{ $trail->name }}
                </h1>

            </div>

        </div>
    </header>


    {{-- =========================================================
        CONTENT
    ========================================================== --}}

    <main
        class="
            mx-auto
            max-w-md
            space-y-6

            px-4
            pt-5

            sm:max-w-3xl
        "
    >

        {{-- =====================================================
            TRAIL INFO
        ====================================================== --}}

        <section>

            <p
                class="
                    text-xs
                    font-semibold
                    text-brand-orange
                "
            >
                {{ $trail->mountain?->name }}
            </p>


            <h2
                class="
                    mt-1

                    text-xl
                    font-extrabold
                    tracking-tight
                "
            >
                {{ $trail->name }}
            </h2>


            <p
                class="
                    mt-1

                    text-xs
                    text-brand-dark/50
                "
            >
                Informasi jalur, checkpoint, panduan keselamatan,
                dan kondisi terbaru pendakian.
            </p>

        </section>


        {{-- =====================================================
            MAP
        ====================================================== --}}

        <section
            class="
                overflow-hidden

                rounded-2xl
                border
                border-brand-dark/10

                bg-white
                shadow-sm
            "
        >

            <div id="trailMap"></div>


            {{-- TRAIL STATS --}}
            <div
                class="
                    grid
                    grid-cols-3

                    border-t
                    border-brand-dark/10
                "
            >

                {{-- JARAK --}}
                <div
                    class="
                        border-r
                        border-brand-dark/10

                        px-3
                        py-4

                        text-center
                    "
                >

                    <p
                        class="
                            text-[10px]
                            text-brand-dark/40
                        "
                    >
                        Jarak
                    </p>


                    <p
                        class="
                            mt-1

                            text-sm
                            font-extrabold
                        "
                    >
                        {{
                            $trail->distance_km
                                ? number_format(
                                    $trail->distance_km,
                                    1,
                                    ',',
                                    '.'
                                ) . ' km'
                                : '-'
                        }}
                    </p>

                </div>


                {{-- ESTIMASI --}}
                <div
                    class="
                        border-r
                        border-brand-dark/10

                        px-3
                        py-4

                        text-center
                    "
                >

                    <p
                        class="
                            text-[10px]
                            text-brand-dark/40
                        "
                    >
                        Estimasi
                    </p>


                    <p
                        class="
                            mt-1

                            text-sm
                            font-extrabold
                        "
                    >
                        {{
                            $trail->estimated_time_hours
                                ? $trail->estimated_time_hours . ' jam'
                                : '-'
                        }}
                    </p>

                </div>


                {{-- KESULITAN --}}
                <div
                    class="
                        px-3
                        py-4

                        text-center
                    "
                >

                    <p
                        class="
                            text-[10px]
                            text-brand-dark/40
                        "
                    >
                        Kesulitan
                    </p>


                    <p
                        class="
                            mt-1

                            text-sm
                            font-extrabold
                        "
                    >
                        {{
                            $trail->difficulty
                                ? ucfirst($trail->difficulty)
                                : '-'
                        }}
                    </p>

                </div>

            </div>

        </section>


        {{-- =====================================================
            PANDUAN & KEAMANAN
        ====================================================== --}}

        @if (
            isset($trail->guides)
            && $trail->guides->isNotEmpty()
        )

            <section>

                <div class="mb-4">

                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-3
                        "
                    >

                        <div>

                            <h2
                                class="
                                    text-base
                                    font-extrabold
                                    text-brand-dark
                                "
                            >
                                Panduan & Keamanan
                            </h2>


                            <p
                                class="
                                    mt-1

                                    text-xs
                                    leading-5
                                    text-brand-dark/45
                                "
                            >
                                Baca informasi berikut sebelum memulai pendakian.
                            </p>

                        </div>


                        <div
                            class="
                                flex
                                h-10
                                w-10
                                shrink-0
                                items-center
                                justify-center

                                rounded-xl

                                bg-brand-dark/5
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
                                    d="M12 3 4 6v5c0 5 3.4 8.7 8 10 4.6-1.3 8-5 8-10V6l-8-3Z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m9 12 2 2 4-4"
                                />
                            </svg>
                        </div>

                    </div>

                </div>


                <div class="space-y-3">

                    @foreach ($trail->guides as $guide)

                        @php

                            $guideStyle = match ($guide->type) {

                                'safety' =>
                                    'border-emerald-200 bg-emerald-50 text-emerald-800',

                                'warning' =>
                                    'border-amber-200 bg-amber-50 text-amber-800',

                                'emergency' =>
                                    'border-red-200 bg-red-50 text-red-800',

                                'equipment' =>
                                    'border-slate-200 bg-slate-50 text-slate-700',

                                'guide' =>
                                    'border-blue-200 bg-blue-50 text-blue-800',

                                default =>
                                    'border-brand-dark/10 bg-white text-brand-dark',

                            };


                            $guideIconBg = match ($guide->type) {

                                'safety' =>
                                    'bg-emerald-100',

                                'warning' =>
                                    'bg-amber-100',

                                'emergency' =>
                                    'bg-red-100',

                                'equipment' =>
                                    'bg-slate-100',

                                'guide' =>
                                    'bg-blue-100',

                                default =>
                                    'bg-brand-dark/5',

                            };

                        @endphp


                        <article
                            class="
                                rounded-2xl
                                border

                                p-4

                                {{ $guideStyle }}
                            "
                        >

                            <div
                                class="
                                    flex
                                    items-start
                                    gap-3
                                "
                            >

                                <div
                                    class="
                                        flex
                                        h-9
                                        w-9
                                        shrink-0
                                        items-center
                                        justify-center

                                        rounded-xl

                                        {{ $guideIconBg }}
                                    "
                                >

                                    @switch($guide->type)

                                        {{-- SAFETY --}}
                                        @case('safety')

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
                                                    d="M12 3 4 6v5c0 5 3.4 8.7 8 10 4.6-1.3 8-5 8-10V6l-8-3Z"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="m9 12 2 2 4-4"
                                                />
                                            </svg>

                                            @break


                                        {{-- WARNING --}}
                                        @case('warning')

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
                                                    d="M12 9v4m0 4h.01M10.3 4.3 2.7 17.5A2 2 0 0 0 4.4 20h15.2a2 2 0 0 0 1.7-2.5L13.7 4.3a2 2 0 0 0-3.4 0Z"
                                                />
                                            </svg>

                                            @break


                                        {{-- EMERGENCY --}}
                                        @case('emergency')

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
                                                    r="9"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M12 8v4m0 4h.01"
                                                />
                                            </svg>

                                            @break


                                        {{-- EQUIPMENT --}}
                                        @case('equipment')

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
                                                    d="M6 8h12l1 12H5L6 8Zm3 0V6a3 3 0 0 1 6 0v2"
                                                />
                                            </svg>

                                            @break


                                        {{-- GUIDE --}}
                                        @default

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
                                                    d="M4 19.5V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v14.5M4 19.5A1.5 1.5 0 0 0 5.5 21H20M4 19.5A1.5 1.5 0 0 1 5.5 18H19"
                                                />
                                            </svg>

                                    @endswitch

                                </div>


                                <div class="min-w-0 flex-1">

                                    <p
                                        class="
                                            text-[10px]
                                            font-bold
                                            uppercase
                                            tracking-wider
                                            opacity-60
                                        "
                                    >
                                        {{ $guide->type_label }}
                                    </p>


                                    <h3
                                        class="
                                            mt-1

                                            text-sm
                                            font-bold
                                        "
                                    >
                                        {{ $guide->title }}
                                    </h3>


                                    <p
                                        class="
                                            mt-2

                                            whitespace-pre-line

                                            text-xs
                                            leading-6
                                            opacity-80
                                        "
                                    >{{ $guide->content }}</p>

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            </section>

        @endif


        {{-- =====================================================
            ACTION BUTTONS
        ====================================================== --}}

        <section
            class="
                grid
                grid-cols-1
                gap-3

                sm:grid-cols-2
            "
        >

            {{-- LIVE TRACKING --}}
            <a
                href="{{ route('pendaki.live-track', [
                    'trail_id' => $trail->id,
                ]) }}"
                class="
                    flex
                    h-12
                    items-center
                    justify-center
                    gap-2

                    rounded-xl

                    bg-brand-dark

                    text-sm
                    font-bold
                    text-white

                    transition

                    hover:bg-brand-dark/90
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

                Mulai Live Tracking
            </a>


            {{-- DOWNLOAD OFFLINE --}}
            <a
                href="{{ route(
                    'pendaki.trail.offline',
                    $trail
                ) }}"
                class="
                    flex
                    h-12
                    items-center
                    justify-center
                    gap-2

                    rounded-xl
                    border
                    border-brand-dark/15

                    bg-white

                    text-sm
                    font-bold
                    text-brand-dark

                    transition

                    hover:bg-brand-dark/5
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
                        d="M12 3v12m0 0 4-4m-4 4-4-4M5 19h14"
                    />
                </svg>

                Unduh Peta Offline
            </a>

        </section>


        {{-- =====================================================
            OFFLINE INFO
        ====================================================== --}}

        <section
            class="
                flex
                items-start
                gap-3

                rounded-xl

                bg-brand-dark/5

                px-4
                py-3
            "
        >

            <svg
                class="
                    mt-0.5
                    h-4
                    w-4
                    shrink-0
                    text-brand-dark/60
                "
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 16h.01M8.5 11.5a5 5 0 017 0M5.5 8.5a9 9 0 0113 0M12 20a1 1 0 100-2 1 1 0 000 2Z"
                />
            </svg>


            <p
                class="
                    text-[11px]
                    leading-5
                    text-brand-dark/55
                "
            >
                Peta offline menyimpan garis jalur, checkpoint,
                titik mulai, dan titik akhir langsung di dalam file
                sehingga tetap dapat dibuka tanpa koneksi internet.
            </p>

        </section>


        {{-- =====================================================
            CHECKPOINT
        ====================================================== --}}

        @if ($checkpoints->isNotEmpty())

            <section>

                <div class="mb-4">

                    <h2
                        class="
                            text-base
                            font-extrabold
                        "
                    >
                        Pos & Titik Penting
                    </h2>


                    <p
                        class="
                            mt-1

                            text-xs
                            text-brand-dark/45
                        "
                    >
                        Titik penting yang terdapat sepanjang jalur pendakian.
                    </p>

                </div>


                <div
                    class="
                        overflow-hidden

                        rounded-2xl
                        border
                        border-brand-dark/10

                        bg-white
                    "
                >

                    @foreach ($checkpoints as $checkpoint)

                        <div
                            class="
                                flex
                                items-center
                                gap-3

                                px-4
                                py-4

                                {{ !$loop->last
                                    ? 'border-b border-brand-dark/10'
                                    : ''
                                }}
                            "
                        >

                            <span
                                class="
                                    flex
                                    h-8
                                    w-8
                                    shrink-0
                                    items-center
                                    justify-center

                                    rounded-full

                                    bg-brand-orange/10

                                    text-xs
                                    font-bold
                                    text-brand-orange
                                "
                            >
                                {{ $loop->iteration }}
                            </span>


                            <div class="min-w-0">

                                <p
                                    class="
                                        text-xs
                                        font-bold
                                    "
                                >
                                    {{ $checkpoint['name'] }}
                                </p>


                                <p
                                    class="
                                        mt-0.5

                                        text-[10px]
                                        text-brand-dark/45
                                    "
                                >
                                    {{ $checkpoint['type'] ?: 'Checkpoint' }}

                                    @if ($checkpoint['elevation_m'])

                                        ·

                                        {{
                                            number_format(
                                                $checkpoint['elevation_m'],
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


        {{-- =====================================================
            FEEDBACK KONDISI JALUR
        ====================================================== --}}

        <section>

            <div class="mb-4">

                <h2
                    class="
                        text-base
                        font-extrabold
                    "
                >
                    Kondisi Jalur
                </h2>


                <p
                    class="
                        mt-1

                        text-xs
                        text-brand-dark/45
                    "
                >
                    Bantu pendaki lain dengan melaporkan kondisi terbaru jalur.
                </p>

            </div>


            <details
                class="
                    group

                    overflow-hidden

                    rounded-2xl
                    border
                    border-brand-dark/10

                    bg-white
                    shadow-sm
                "
            >

                <summary
                    class="
                        flex
                        cursor-pointer
                        list-none
                        items-center
                        justify-between

                        p-4
                    "
                >

                    <div
                        class="
                            flex
                            items-center
                            gap-3
                        "
                    >

                        <div
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
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 10h8m-8 4h5M5 4h14a2 2 0 012 2v10a2 2 0 01-2 2h-5l-4 3v-3H5a2 2 0 01-2-2V6a2 2 0 012-2Z"
                                />
                            </svg>
                        </div>


                        <div>

                            <p
                                class="
                                    text-sm
                                    font-bold
                                "
                            >
                                Berikan Feedback
                            </p>


                            <p
                                class="
                                    mt-0.5

                                    text-[10px]
                                    text-brand-dark/45
                                "
                            >
                                Laporkan kondisi jalur yang Anda temui.
                            </p>

                        </div>

                    </div>


                    <svg
                        class="
                            h-5
                            w-5

                            text-brand-dark/40

                            transition

                            group-open:rotate-180
                        "
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m6 9 6 6 6-6"
                        />
                    </svg>

                </summary>


                <form
                    method="POST"
                    action="{{ route(
                        'pendaki.trail.feedback.store',
                        $trail
                    ) }}"
                    class="
                        border-t
                        border-brand-dark/10

                        p-4
                    "
                >

                    @csrf


                    {{-- STATUS --}}
                    <div>

                        <label
                            for="status"
                            class="
                                block

                                text-xs
                                font-bold
                            "
                        >
                            Kondisi Jalur
                        </label>


                        <select
                            name="status"
                            id="status"
                            required
                            class="
                                mt-2
                                h-11
                                w-full

                                rounded-xl
                                border
                                border-brand-dark/15

                                bg-white

                                px-3

                                text-sm

                                outline-none

                                focus:border-brand-orange
                                focus:ring-2
                                focus:ring-brand-orange/10
                            "
                        >

                            <option value="">
                                Pilih kondisi jalur
                            </option>


                            <option
                                value="aman"
                                @selected(old('status') === 'aman')
                            >
                                Aman
                            </option>


                            <option
                                value="licin"
                                @selected(old('status') === 'licin')
                            >
                                Licin
                            </option>


                            <option
                                value="berlumpur"
                                @selected(old('status') === 'berlumpur')
                            >
                                Berlumpur
                            </option>


                            <option
                                value="longsor"
                                @selected(old('status') === 'longsor')
                            >
                                Longsor
                            </option>


                            <option
                                value="pohon_tumbang"
                                @selected(old('status') === 'pohon_tumbang')
                            >
                                Pohon Tumbang
                            </option>


                            <option
                                value="jalur_tertutup"
                                @selected(old('status') === 'jalur_tertutup')
                            >
                                Jalur Tertutup
                            </option>


                            <option
                                value="jembatan_rusak"
                                @selected(old('status') === 'jembatan_rusak')
                            >
                                Jembatan Rusak
                            </option>


                            <option
                                value="lainnya"
                                @selected(old('status') === 'lainnya')
                            >
                                Lainnya
                            </option>

                        </select>


                        @error('status')

                            <p
                                class="
                                    mt-1

                                    text-[11px]
                                    font-semibold
                                    text-red-500
                                "
                            >
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- NOTE --}}
                    <div class="mt-4">

                        <label
                            for="condition_note"
                            class="
                                block

                                text-xs
                                font-bold
                            "
                        >
                            Keterangan
                        </label>


                        <textarea
                            name="condition_note"
                            id="condition_note"

                            rows="4"

                            required
                            maxlength="1000"

                            placeholder="Contoh: Jalur setelah Pos 2 cukup licin akibat hujan."

                            class="
                                mt-2

                                w-full
                                resize-none

                                rounded-xl
                                border
                                border-brand-dark/15

                                bg-white

                                px-3
                                py-3

                                text-sm

                                outline-none

                                focus:border-brand-orange
                                focus:ring-2
                                focus:ring-brand-orange/10
                            "
                        >{{ old('condition_note') }}</textarea>


                        @error('condition_note')

                            <p
                                class="
                                    mt-1

                                    text-[11px]
                                    font-semibold
                                    text-red-500
                                "
                            >
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <button
                        type="submit"
                        class="
                            mt-4

                            flex
                            h-11
                            w-full
                            items-center
                            justify-center
                            gap-2

                            rounded-xl

                            bg-brand-orange

                            text-xs
                            font-bold
                            text-white

                            transition

                            hover:bg-brand-orange/90
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
                                d="m5 12 14-7-4 14-3-5-7-2Z"
                            />
                        </svg>

                        Kirim Feedback

                    </button>

                </form>

            </details>

        </section>


        {{-- =====================================================
            LAPORAN TERBARU
        ====================================================== --}}

        <section>

            <div class="mb-4">

                <h2
                    class="
                        text-base
                        font-extrabold
                    "
                >
                    Laporan Terbaru
                </h2>


                <p
                    class="
                        mt-1

                        text-xs
                        text-brand-dark/45
                    "
                >
                    Informasi kondisi jalur terbaru dari pendaki.
                </p>

            </div>


            <div class="space-y-3">

                @forelse ($reports as $report)

                    @php

                        $badgeClass = match ($report->status_color) {

                            'emerald' =>
                                'bg-emerald-50 text-emerald-700',

                            'amber' =>
                                'bg-amber-50 text-amber-700',

                            'orange' =>
                                'bg-orange-50 text-orange-700',

                            'red' =>
                                'bg-red-50 text-red-700',

                            default =>
                                'bg-slate-100 text-slate-600',

                        };

                    @endphp


                    <article
                        class="
                            rounded-2xl
                            border
                            border-brand-dark/10

                            bg-white

                            p-4

                            shadow-sm
                        "
                    >

                        <div
                            class="
                                flex
                                items-start
                                justify-between
                                gap-3
                            "
                        >

                            <div class="min-w-0">

                                <p
                                    class="
                                        truncate

                                        text-xs
                                        font-bold
                                    "
                                >
                                    {{ $report->user?->name ?? 'Pendaki' }}
                                </p>


                                <p
                                    class="
                                        mt-0.5

                                        text-[10px]
                                        text-brand-dark/40
                                    "
                                >
                                    {{
                                        optional(
                                            $report->report_date
                                        )
                                        ->locale('id')
                                        ->translatedFormat(
                                            'd F Y, H:i'
                                        )
                                    }}
                                </p>

                            </div>


                            <span
                                class="
                                    shrink-0

                                    rounded-full

                                    px-2.5
                                    py-1

                                    text-[10px]
                                    font-bold

                                    {{ $badgeClass }}
                                "
                            >
                                {{ $report->status_label }}
                            </span>

                        </div>


                        <p
                            class="
                                mt-3

                                text-xs
                                leading-6
                                text-brand-dark/65
                            "
                        >
                            {{ $report->condition_note }}
                        </p>

                    </article>


                @empty

                    <div
                        class="
                            rounded-2xl
                            border
                            border-dashed
                            border-brand-dark/15

                            bg-white

                            px-5
                            py-8

                            text-center
                        "
                    >

                        <div
                            class="
                                mx-auto

                                flex
                                h-10
                                w-10
                                items-center
                                justify-center

                                rounded-full

                                bg-brand-dark/5
                                text-brand-dark/40
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
                                    d="M8 10h8m-8 4h5M5 4h14a2 2 0 012 2v10a2 2 0 01-2 2h-5l-4 3v-3H5a2 2 0 01-2-2V6a2 2 0 012-2Z"
                                />
                            </svg>
                        </div>


                        <p
                            class="
                                mt-3

                                text-xs
                                font-bold
                            "
                        >
                            Belum ada laporan kondisi
                        </p>


                        <p
                            class="
                                mt-1

                                text-[11px]
                                leading-5
                                text-brand-dark/45
                            "
                        >
                            Jadilah pendaki pertama yang memberikan
                            informasi kondisi jalur.
                        </p>

                    </div>

                @endforelse

            </div>

        </section>


        <div class="h-4"></div>

    </main>


    {{-- =========================================================
        BOTTOM NAVIGATION
    ========================================================== --}}

    @include(
        'pendaki.components.bottom-nav',
        [
            'active' => 'dashboard',
        ]
    )


    {{-- =========================================================
        MAP SCRIPT
    ========================================================== --}}

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function () {

                /*
                |--------------------------------------------------------------------------
                | DATA
                |--------------------------------------------------------------------------
                */

                const routeCoordinates =
                    @json($routeCoordinates);

                const checkpoints =
                    @json($checkpoints);

                const startPoint =
                    @json($startPoint);

                const finishPoint =
                    @json($finishPoint);


                /*
                |--------------------------------------------------------------------------
                | MAP INIT
                |--------------------------------------------------------------------------
                */

                const map =
                    L.map(
                        'trailMap',
                        {
                            zoomControl: true,
                            attributionControl: true,
                        }
                    );


                L.tileLayer(
                    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    {
                        maxZoom: 19,

                        attribution:
                            '&copy; OpenStreetMap',
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
                    routeCoordinates.length >= 2
                ) {

                    /*
                     * Outline putih agar jalur terlihat
                     * pada berbagai jenis tile.
                     */
                    L.polyline(
                        routeCoordinates,
                        {
                            color:
                                '#ffffff',

                            weight:
                                8,

                            opacity:
                                .85,

                            lineCap:
                                'round',

                            lineJoin:
                                'round',
                        }
                    )
                    .addTo(
                        map
                    );


                    /*
                     * Jalur utama.
                     */
                    const routeLine =
                        L.polyline(
                            routeCoordinates,
                            {
                                color:
                                    '#1a382b',

                                weight:
                                    4,

                                opacity:
                                    1,

                                lineCap:
                                    'round',

                                lineJoin:
                                    'round',
                            }
                        )
                        .addTo(
                            map
                        );


                    map.fitBounds(
                        routeLine.getBounds(),
                        {
                            padding: [
                                40,
                                40,
                            ],
                        }
                    );

                } else {

                    /*
                     * Fallback Bali.
                     */
                    map.setView(
                        [
                            -8.409518,
                            115.188919,
                        ],
                        9
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | START
                |--------------------------------------------------------------------------
                */

                if (startPoint) {

                    const startIcon =
                        L.divIcon({
                            className:
                                'trail-marker',

                            html:
                                '<div class="trail-dot start"></div>',

                            iconSize:
                                [
                                    22,
                                    22,
                                ],

                            iconAnchor:
                                [
                                    11,
                                    11,
                                ],
                        });


                    L.marker(
                        startPoint,
                        {
                            icon:
                                startIcon,
                        }
                    )
                    .addTo(
                        map
                    )
                    .bindTooltip(
                        'Start'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | CHECKPOINTS
                |--------------------------------------------------------------------------
                */

                if (
                    Array.isArray(
                        checkpoints
                    )
                ) {

                    checkpoints.forEach(
                        function (
                            checkpoint,
                            index
                        ) {

                            const icon =
                                L.divIcon({
                                    className:
                                        'trail-marker',

                                    html:
                                        `
                                        <div class="checkpoint-marker">
                                            ${index + 1}
                                        </div>
                                        `,

                                    iconSize:
                                        [
                                            25,
                                            25,
                                        ],

                                    iconAnchor:
                                        [
                                            12,
                                            12,
                                        ],
                                });


                            L.marker(
                                [
                                    Number(
                                        checkpoint.latitude
                                    ),

                                    Number(
                                        checkpoint.longitude
                                    ),
                                ],
                                {
                                    icon:
                                        icon,
                                }
                            )
                            .addTo(
                                map
                            )
                            .bindTooltip(
                                checkpoint.name
                            );

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | FINISH
                |--------------------------------------------------------------------------
                */

                if (finishPoint) {

                    const finishIcon =
                        L.divIcon({
                            className:
                                'trail-marker',

                            html:
                                '<div class="trail-dot"></div>',

                            iconSize:
                                [
                                    22,
                                    22,
                                ],

                            iconAnchor:
                                [
                                    11,
                                    11,
                                ],
                        });


                    L.marker(
                        finishPoint,
                        {
                            icon:
                                finishIcon,
                        }
                    )
                    .addTo(
                        map
                    )
                    .bindTooltip(
                        'Puncak'
                    );

                }

            }
        );
    </script>


    {{-- =========================================================
        FLASH SUCCESS
    ========================================================== --}}

    @if (session('success'))

        <script>
            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    Swal.fire({
                        icon:
                            'success',

                        title:
                            'Feedback terkirim',

                        text:
                            @json(session('success')),

                        confirmButtonText:
                            'Mengerti',

                        confirmButtonColor:
                            '#1a382b',
                    });

                }
            );
        </script>

    @endif


    {{-- =========================================================
        VALIDATION ERROR
    ========================================================== --}}

    @if ($errors->any())

        <script>
            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    Swal.fire({
                        icon:
                            'warning',

                        title:
                            'Periksa kembali',

                        html:
                            @json(
                                implode(
                                    '<br>',
                                    $errors->all()
                                )
                            ),

                        confirmButtonText:
                            'Mengerti',

                        confirmButtonColor:
                            '#1a382b',
                    });

                }
            );
        </script>

    @endif

</body>

</html>