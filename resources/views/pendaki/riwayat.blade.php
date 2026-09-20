<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover"
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
        Riwayat Pendakian - BaliHiking
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
    {{-- TAILWIND LOCAL --}}
    {{-- ========================================================= --}}

    <script
        src="{{ asset('vendor/tailwindcss.js') }}"
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

                    },

                    fontFamily: {

                        sans: [
                            '-apple-system',
                            'BlinkMacSystemFont',
                            '"Segoe UI"',
                            'Roboto',
                            'Arial',
                            'sans-serif'
                        ],

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


        html {

            background:
                #fbfbfa;

            -webkit-text-size-adjust:
                100%;

        }


        body {

            margin:
                0;

            min-height:
                100vh;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background:
                #fbfbfa;

            color:
                #1a382b;

        }


        img {

            max-width:
                100%;

        }


        button,
        input,
        select {

            font:
                inherit;

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
                    88px +
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
                calc(100vw - 28px);

            padding:
                9px 14px;

            border-radius:
                999px;

            transform:
                translateX(-50%);

            color:
                white;

            background:
                #f06535;

            box-shadow:
                0 10px 30px
                rgba(0, 0, 0, .16);

            font-size:
                10px;

            font-weight:
                700;

            white-space:
                nowrap;

        }


        #networkStatus.show {

            display:
                flex;

        }


        #networkStatus.online {

            background:
                #1a382b;

        }


        #networkStatus.offline {

            background:
                #f06535;

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
        | OFFLINE HEADER BADGE
        |--------------------------------------------------------------------------
        */

        #offlineBadge {

            display:
                none;

        }


        #offlineBadge.show {

            display:
                inline-flex;

        }


        /*
        |--------------------------------------------------------------------------
        | OFFLINE INFORMATION
        |--------------------------------------------------------------------------
        */

        #offlineInformation {

            display:
                none;

        }


        #offlineInformation.show {

            display:
                block;

        }


        /*
        |--------------------------------------------------------------------------
        | LAST CACHE TIME
        |--------------------------------------------------------------------------
        */

        #cacheTimestampContainer {

            display:
                none;

        }


        #cacheTimestampContainer.show {

            display:
                flex;

        }


        /*
        |--------------------------------------------------------------------------
        | TOAST
        |--------------------------------------------------------------------------
        */

        #historyToast {

            position:
                fixed;

            top:
                76px;

            left:
                50%;

            z-index:
                999999;

            width:
                calc(100% - 30px);

            max-width:
                420px;

            padding:
                12px 14px;

            border-radius:
                14px;

            transform:
                translate(
                    -50%,
                    -25px
                );

            opacity:
                0;

            visibility:
                hidden;

            color:
                white;

            background:
                #1a382b;

            box-shadow:
                0 12px 32px
                rgba(0, 0, 0, .17);

            transition:
                all .25s ease;

        }


        #historyToast.show {

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


        #historyToast.warning {

            background:
                #f06535;

        }


        #historyToast.error {

            background:
                #dc2626;

        }


        /*
        |--------------------------------------------------------------------------
        | OFFLINE LINK
        |--------------------------------------------------------------------------
        */

        body.is-offline
        .offline-aware-link {

            position:
                relative;

        }


        body.is-offline
        .offline-aware-link::after {

            content:
                "";

            position:
                absolute;

            top:
                7px;

            right:
                7px;

            width:
                6px;

            height:
                6px;

            border-radius:
                999px;

            background:
                #f06535;

        }


        /*
        |--------------------------------------------------------------------------
        | FILTER SCROLL
        |--------------------------------------------------------------------------
        */

        .hide-scrollbar {

            scrollbar-width:
                none;

        }


        .hide-scrollbar::-webkit-scrollbar {

            display:
                none;

        }


        /*
        |--------------------------------------------------------------------------
        | SAFE AREA
        |--------------------------------------------------------------------------
        */

        @supports (
            padding-bottom:
                env(safe-area-inset-bottom)
        ) {

            body {

                padding-left:
                    env(safe-area-inset-left);

                padding-right:
                    env(safe-area-inset-right);

            }

        }

    </style>

</head>


<body
    id="appBody"
    class="
        bg-brand-cream
        pb-28
        text-brand-dark
        antialiased
    "
>


    {{-- ========================================================= --}}
    {{-- TOAST --}}
    {{-- ========================================================= --}}

    <div
        id="historyToast"
        role="status"
        aria-live="polite"
    >

        <div
            class="
                flex
                items-start
                gap-3
            "
        >

            <span
                class="
                    mt-1
                    h-2
                    w-2
                    shrink-0
                    rounded-full
                    bg-white
                "
            ></span>


            <div>

                <p
                    id="historyToastTitle"
                    class="
                        text-xs
                        font-extrabold
                    "
                >
                    BaliHiking
                </p>


                <p
                    id="historyToastMessage"
                    class="
                        mt-0.5
                        text-[10px]
                        leading-relaxed
                        text-white/80
                    "
                ></p>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- NETWORK --}}
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
            Offline
        </span>

    </div>



    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

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
                max-w-md
                items-center
                justify-between
                px-4
                sm:max-w-3xl
            "
        >


            {{-- BACK --}}

            <a
                href="{{ route('pendaki.dashboard') }}"
                data-offline-link
                class="
                    offline-aware-link
                    flex
                    h-10
                    w-10
                    items-center
                    justify-center
                    rounded-lg
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
                        d="m15 18-6-6 6-6"
                    />

                </svg>

            </a>



            {{-- TITLE --}}

            <div
                class="
                    flex
                    items-center
                    gap-2.5
                "
            >

                <span
                    class="
                        h-8
                        w-8
                        overflow-hidden
                        rounded-lg
                    "
                >

                    <img
                        src="{{ asset('icons/icon-192.png') }}"
                        alt="BaliHiking"
                        class="
                            h-full
                            w-full
                            object-cover
                        "
                    >

                </span>


                <div
                    class="text-left"
                >

                    <h1
                        class="
                            text-sm
                            font-extrabold
                            text-brand-dark
                        "
                    >
                        Riwayat Pendakian
                    </h1>


                    <div
                        class="
                            mt-0.5
                            flex
                            items-center
                            gap-2
                        "
                    >

                        <p
                            class="
                                text-[9px]
                                text-brand-dark/40
                            "
                        >
                            BaliHiking
                        </p>


                        <span
                            id="offlineBadge"
                            class="
                                items-center
                                gap-1
                                text-[8px]
                                font-extrabold
                                uppercase
                                text-brand-orange
                            "
                        >

                            <span
                                class="
                                    h-1.5
                                    w-1.5
                                    rounded-full
                                    bg-brand-orange
                                "
                            ></span>

                            Offline

                        </span>

                    </div>

                </div>

            </div>


            <div
                class="h-10 w-10"
            ></div>

        </div>

    </header>



    {{-- ========================================================= --}}
    {{-- MAIN --}}
    {{-- ========================================================= --}}

    <main
        class="
            mx-auto
            max-w-md
            space-y-6
            px-4
            pt-6
            sm:max-w-3xl
        "
    >


        {{-- ===================================================== --}}
        {{-- OFFLINE INFO --}}
        {{-- ===================================================== --}}

        <section
            id="offlineInformation"
            class="
                rounded-2xl
                border
                border-orange-200
                bg-orange-50
                p-4
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
                        text-brand-orange
                    "
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0ZM12 15.75h.008v.008H12v-.008Z"
                    />

                </svg>


                <div>

                    <p
                        class="
                            text-xs
                            font-extrabold
                            text-brand-orange
                        "
                    >
                        Mode Offline
                    </p>


                    <p
                        class="
                            mt-1
                            text-[10px]
                            leading-relaxed
                            text-orange-800/70
                        "
                    >
                        Riwayat yang tampil merupakan data
                        terakhir yang telah tersimpan di perangkat.
                        Data terbaru dari server akan diperbarui
                        setelah internet tersedia.
                    </p>


                    <div
                        id="cacheTimestampContainer"
                        class="
                            mt-2
                            items-center
                            gap-1
                            text-[9px]
                            font-semibold
                            text-orange-700/70
                        "
                    >

                        <span>
                            Cache:
                        </span>

                        <span
                            id="cacheTimestamp"
                        >
                            -
                        </span>

                    </div>

                </div>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- SUMMARY --}}
        {{-- ===================================================== --}}

        <section
            class="
                grid
                grid-cols-3
                overflow-hidden
                rounded-2xl
                border
                border-brand-dark/10
                bg-white
                shadow-sm
            "
        >


            {{-- TOTAL --}}

            <div
                class="
                    border-r
                    border-brand-dark/10
                    px-2
                    py-4
                    text-center
                "
            >

                <p
                    class="
                        text-xl
                        font-extrabold
                        text-brand-dark
                    "
                >
                    {{ $totalRiwayat }}
                </p>


                <p
                    class="
                        mt-1
                        text-[10px]
                        font-medium
                        text-brand-dark/45
                    "
                >
                    Total
                </p>

            </div>



            {{-- SELESAI --}}

            <div
                class="
                    border-r
                    border-brand-dark/10
                    px-2
                    py-4
                    text-center
                "
            >

                <p
                    class="
                        text-xl
                        font-extrabold
                        text-emerald-600
                    "
                >
                    {{ $totalSelesai }}
                </p>


                <p
                    class="
                        mt-1
                        text-[10px]
                        font-medium
                        text-brand-dark/45
                    "
                >
                    Selesai
                </p>

            </div>



            {{-- BERLANGSUNG --}}

            <div
                class="
                    px-2
                    py-4
                    text-center
                "
            >

                <p
                    class="
                        text-xl
                        font-extrabold
                        text-amber-600
                    "
                >
                    {{ $totalBerlangsung }}
                </p>


                <p
                    class="
                        mt-1
                        text-[10px]
                        font-medium
                        text-brand-dark/45
                    "
                >
                    Berlangsung
                </p>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- FILTER --}}
        {{-- ===================================================== --}}

        <section>

            <div
                class="
                    hide-scrollbar
                    flex
                    gap-2
                    overflow-x-auto
                    pb-1
                "
            >


                <a
                    href="{{ route('pendaki.riwayat') }}"
                    data-offline-link
                    class="
                        offline-aware-link
                        shrink-0
                        rounded-lg
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        transition

                        {{
                            $status === 'all'
                                ? 'bg-brand-dark text-white'
                                : 'border border-brand-dark/10 bg-white text-brand-dark/60 hover:bg-brand-dark/5'
                        }}
                    "
                >
                    Semua
                </a>


                <a
                    href="{{ route('pendaki.riwayat', ['status' => 'completed']) }}"
                    data-offline-link
                    class="
                        offline-aware-link
                        shrink-0
                        rounded-lg
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        transition

                        {{
                            $status === 'completed'
                                ? 'bg-emerald-600 text-white'
                                : 'border border-brand-dark/10 bg-white text-brand-dark/60 hover:bg-brand-dark/5'
                        }}
                    "
                >
                    Selesai
                </a>


                <a
                    href="{{ route('pendaki.riwayat', ['status' => 'ongoing']) }}"
                    data-offline-link
                    class="
                        offline-aware-link
                        shrink-0
                        rounded-lg
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        transition

                        {{
                            $status === 'ongoing'
                                ? 'bg-amber-500 text-white'
                                : 'border border-brand-dark/10 bg-white text-brand-dark/60 hover:bg-brand-dark/5'
                        }}
                    "
                >
                    Berlangsung
                </a>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- TITLE --}}
        {{-- ===================================================== --}}

        <section>

            <h2
                class="
                    text-base
                    font-extrabold
                    text-brand-dark
                "
            >
                Perjalanan Anda
            </h2>


            <p
                class="
                    mt-1
                    text-xs
                    text-brand-dark/45
                "
            >
                Riwayat pendakian yang tercatat di BaliHiking.
            </p>

        </section>



        {{-- ===================================================== --}}
        {{-- RIWAYAT --}}
        {{-- ===================================================== --}}

        <section
            class="space-y-3"
        >

            @forelse($riwayat as $item)

                @php

                    $trail =
                        $item->hikingTrail;

                    $mountain =
                        $trail?->mountain;

                    $isCompleted =
                        ! is_null(
                            $item->completed_at
                        );

                @endphp


                <article
                    class="
                        overflow-hidden
                        rounded-2xl
                        border
                        border-brand-dark/10
                        bg-white
                        shadow-sm
                        transition
                        hover:border-brand-dark/20
                        hover:shadow-md
                    "
                >


                    {{-- ========================================= --}}
                    {{-- MAIN CARD --}}
                    {{-- ========================================= --}}

                    <div
                        class="
                            flex
                            items-start
                            gap-4
                            p-4
                        "
                    >


                        {{-- ICON --}}

                        <div
                            class="
                                flex
                                h-12
                                w-12
                                shrink-0
                                items-center
                                justify-center
                                rounded-xl

                                {{
                                    $isCompleted
                                        ? 'bg-emerald-50 text-emerald-600'
                                        : 'bg-amber-50 text-amber-600'
                                }}
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
                                    d="M3 20h18L15 6l-4 7-2-3-6 10Z"
                                />

                            </svg>

                        </div>



                        {{-- CONTENT --}}

                        <div
                            class="
                                min-w-0
                                flex-1
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

                                <div
                                    class="min-w-0"
                                >

                                    <h3
                                        class="
                                            truncate
                                            text-sm
                                            font-extrabold
                                            text-brand-dark
                                        "
                                    >
                                        {{
                                            $mountain?->name
                                            ??
                                            'Gunung tidak tersedia'
                                        }}
                                    </h3>


                                    <p
                                        class="
                                            mt-0.5
                                            truncate
                                            text-[11px]
                                            text-brand-dark/50
                                        "
                                    >
                                        {{
                                            $trail?->name
                                            ??
                                            'Jalur tidak tersedia'
                                        }}
                                    </p>

                                </div>



                                {{-- STATUS --}}

                                <span
                                    class="
                                        shrink-0
                                        rounded-full
                                        px-2.5
                                        py-1
                                        text-[10px]
                                        font-bold

                                        {{
                                            $isCompleted
                                                ? 'bg-emerald-100 text-emerald-700'
                                                : 'bg-amber-100 text-amber-700'
                                        }}
                                    "
                                >
                                    {{
                                        $isCompleted
                                            ? 'Selesai'
                                            : 'Berlangsung'
                                    }}
                                </span>

                            </div>



                            {{-- DATE --}}

                            <div
                                class="
                                    mt-3
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-x-4
                                    gap-y-2
                                    text-[10px]
                                    text-brand-dark/45
                                "
                            >


                                <div
                                    class="
                                        flex
                                        items-center
                                        gap-1.5
                                    "
                                >

                                    <svg
                                        class="h-3.5 w-3.5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M8 2v3m8-3v3M3.5 9.5h17M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2Z"
                                        />

                                    </svg>


                                    <span>
                                        Mulai
                                        {{
                                            optional(
                                                $item->created_at
                                            )
                                            ->locale('id')
                                            ->translatedFormat(
                                                'd F Y'
                                            )
                                        }}
                                    </span>

                                </div>



                                @if($item->completed_at)

                                    <div
                                        class="
                                            flex
                                            items-center
                                            gap-1.5
                                        "
                                    >

                                        <svg
                                            class="h-3.5 w-3.5"
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


                                        <span>
                                            Selesai
                                            {{
                                                optional(
                                                    $item->completed_at
                                                )
                                                ->locale('id')
                                                ->translatedFormat(
                                                    'd F Y'
                                                )
                                            }}
                                        </span>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>



                    {{-- ========================================= --}}
                    {{-- DETAIL --}}
                    {{-- ========================================= --}}

                    @if($trail)

                        <div
                            class="
                                grid
                                grid-cols-3
                                border-t
                                border-brand-dark/10
                                bg-brand-cream/50
                            "
                        >


                            {{-- JARAK --}}

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
                                    Jarak
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        font-bold
                                    "
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



                            {{-- ESTIMASI --}}

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
                                    Estimasi
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        font-bold
                                    "
                                >

                                    @if($trail->estimated_time_hours)

                                        {{
                                            $trail
                                                ->estimated_time_hours
                                        }}
                                        jam

                                    @else

                                        -

                                    @endif

                                </p>

                            </div>



                            {{-- DIFFICULTY --}}

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
                                    Kesulitan
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        font-bold
                                    "
                                >
                                    {{
                                        $trail->difficulty
                                            ? ucfirst(
                                                $trail->difficulty
                                            )
                                            : '-'
                                    }}
                                </p>

                            </div>

                        </div>



                        {{-- ===================================== --}}
                        {{-- ACTION --}}
                        {{-- ===================================== --}}

                        <div
                            class="
                                flex
                                gap-2
                                border-t
                                border-brand-dark/10
                                p-3
                            "
                        >


                            {{-- LIHAT JALUR --}}

                            <a
                                href="{{ route('pendaki.trail.show', $trail) }}"
                                data-offline-link
                                class="
                                    offline-aware-link
                                    flex
                                    h-10
                                    flex-1
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-xl
                                    border
                                    border-brand-dark/10
                                    bg-white
                                    text-xs
                                    font-bold
                                    text-brand-dark
                                    transition
                                    hover:bg-brand-dark/5
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
                                        d="M9 20 3.55 17.27A1 1 0 013 16.38V5.62a1 1 0 011.45-.9L9 7m0 13 6-3m-6 3V7m6 10 4.55 2.28A1 1 0 0021 18.38V7.62a1 1 0 00-.55-.9L15 4m0 13V4M15 4 9 7"
                                    />

                                </svg>

                                Lihat Jalur

                            </a>



                            {{-- LIVE TRACKING --}}

                            @if(!$isCompleted)

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
                                    class="
                                        offline-aware-link
                                        flex
                                        h-10
                                        flex-1
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-xl
                                        bg-brand-dark
                                        text-xs
                                        font-bold
                                        text-white
                                        transition
                                        hover:bg-brand-dark/90
                                    "
                                >

                                    <svg
                                        class="h-4 w-4"
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

                                    Lanjut Tracking

                                </a>

                            @endif

                        </div>

                    @endif

                </article>



            @empty


                {{-- ================================================= --}}
                {{-- EMPTY --}}
                {{-- ================================================= --}}

                <div
                    class="
                        rounded-2xl
                        border
                        border-dashed
                        border-brand-dark/15
                        bg-white
                        px-6
                        py-12
                        text-center
                    "
                >

                    <div
                        class="
                            mx-auto
                            flex
                            h-12
                            w-12
                            items-center
                            justify-center
                            rounded-xl
                            bg-brand-dark/5
                            text-brand-dark/30
                        "
                    >

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 20h18L15 6l-4 7-2-3-6 10Z"
                            />

                        </svg>

                    </div>


                    <h3
                        class="
                            mt-4
                            text-sm
                            font-bold
                            text-brand-dark
                        "
                    >
                        Belum ada riwayat pendakian
                    </h3>


                    <p
                        class="
                            mx-auto
                            mt-2
                            max-w-xs
                            text-xs
                            leading-5
                            text-brand-dark/45
                        "
                    >

                        @if($status === 'completed')

                            Belum ada pendakian yang selesai.

                        @elseif($status === 'ongoing')

                            Tidak ada pendakian yang sedang berlangsung.

                        @else

                            Riwayat perjalanan akan muncul setelah
                            Anda memilih dan memulai jalur pendakian.

                        @endif

                    </p>


                    <a
                        href="{{ route('pendaki.dashboard') }}"
                        data-offline-link
                        class="
                            offline-aware-link
                            mt-5
                            inline-flex
                            h-10
                            items-center
                            justify-center
                            rounded-xl
                            bg-brand-dark
                            px-5
                            text-xs
                            font-bold
                            text-white
                        "
                    >
                        Cari Jalur Pendakian
                    </a>

                </div>

            @endforelse

        </section>



        {{-- ===================================================== --}}
        {{-- PAGINATION --}}
        {{-- ===================================================== --}}

        @if($riwayat->hasPages())

            <div
                id="historyPagination"
                class="pt-2"
            >

                {{ $riwayat->links() }}

            </div>

        @endif


        <div
            class="h-2"
        ></div>

    </main>



    {{-- ========================================================= --}}
    {{-- BOTTOM NAV --}}
    {{-- ========================================================= --}}

    @include(
        'pendaki.components.bottom-nav',
        [
            'active' => 'riwayat'
        ]
    )



    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | CONFIG
        |--------------------------------------------------------------------------
        */

        const HISTORY_CACHE =
            'balihiking-history-pages-v2';


        const HISTORY_CACHE_TIME_KEY =
            'balihiking_history_cache_time';


        const LIVE_TRACK_DB =
            'balihiking-live-tracking';


        const LIVE_TRACK_DB_VERSION =
            1;


        const LIVE_TRACK_QUEUE_STORE =
            'request_queue';


        let toastTimer =
            null;


        let liveTrackingDatabase =
            null;


        let liveQueueSyncing =
            false;


        /*
        |--------------------------------------------------------------------------
        | ELEMENTS
        |--------------------------------------------------------------------------
        */

        const appBody =
            document.getElementById(
                'appBody'
            );


        const networkStatus =
            document.getElementById(
                'networkStatus'
            );


        const networkStatusText =
            document.getElementById(
                'networkStatusText'
            );


        const offlineBadge =
            document.getElementById(
                'offlineBadge'
            );


        const offlineInformation =
            document.getElementById(
                'offlineInformation'
            );


        const cacheTimestampContainer =
            document.getElementById(
                'cacheTimestampContainer'
            );


        const cacheTimestamp =
            document.getElementById(
                'cacheTimestamp'
            );


        /*
        |--------------------------------------------------------------------------
        | START
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'DOMContentLoaded',
            async function () {

                await updateNetworkUI();


                setupOfflineLinks();


                setupPaginationOfflineLinks();


                showLastCacheTime();


                if (
                    navigator.onLine
                ) {

                    await cacheCurrentHistoryPage();


                    await prefetchHistoryFilters();


                    await syncLiveTrackingQueue();

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
                            '[BaliHiking Riwayat] Service Worker:',
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
                            '[BaliHiking Riwayat] Service Worker gagal:',
                            error
                        );

                    }

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

                await updateNetworkUI();


                showToast(
                    'Internet kembali aktif',
                    'BaliHiking sedang memperbarui data yang tersimpan.',
                    'success'
                );


                await cacheCurrentHistoryPage();


                await prefetchHistoryFilters();


                await syncLiveTrackingQueue();

            }
        );


        window.addEventListener(
            'offline',
            async function () {

                await updateNetworkUI();


                showToast(
                    'Mode Offline',
                    'Riwayat terakhir yang tersimpan tetap dapat dilihat.',
                    'warning'
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | NETWORK UI
        |--------------------------------------------------------------------------
        */

        async function updateNetworkUI() {

            if (
                navigator.onLine
            ) {

                appBody
                    .classList
                    .remove(
                        'is-offline'
                    );


                networkStatus
                    .classList
                    .remove(
                        'offline'
                    );


                networkStatus
                    .classList
                    .add(
                        'online'
                    );


                networkStatusText
                    .innerText =
                        'Online';


                offlineBadge
                    .classList
                    .remove(
                        'show'
                    );


                offlineInformation
                    .classList
                    .remove(
                        'show'
                    );


                setTimeout(
                    function () {

                        if (
                            navigator.onLine
                        ) {

                            networkStatus
                                .classList
                                .remove(
                                    'show'
                                );

                        }

                    },
                    1700
                );


            } else {


                appBody
                    .classList
                    .add(
                        'is-offline'
                    );


                networkStatus
                    .classList
                    .remove(
                        'online'
                    );


                networkStatus
                    .classList
                    .add(
                        'offline',
                        'show'
                    );


                networkStatusText
                    .innerText =
                        'Offline · menampilkan data tersimpan';


                offlineBadge
                    .classList
                    .add(
                        'show'
                    );


                offlineInformation
                    .classList
                    .add(
                        'show'
                    );


                showLastCacheTime();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CACHE CURRENT PAGE
        |--------------------------------------------------------------------------
        */

        async function cacheCurrentHistoryPage() {

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
                        HISTORY_CACHE
                    );


                const response =
                    await fetch(
                        window.location.href,
                        {
                            method:
                                'GET',

                            credentials:
                                'same-origin',

                            cache:
                                'no-store',

                            headers: {

                                'X-BaliHiking-Cache':
                                    '1'

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


                    const now =
                        Date.now();


                    localStorage.setItem(
                        HISTORY_CACHE_TIME_KEY,
                        String(
                            now
                        )
                    );


                    showLastCacheTime();

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking Riwayat] Cache halaman gagal:',
                    error
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | PREFETCH FILTER
        |--------------------------------------------------------------------------
        |
        | Supaya tab Semua / Selesai / Berlangsung punya kemungkinan lebih besar
        | tetap tersedia ketika koneksi hilang.
        |
        */

        async function prefetchHistoryFilters() {

            if (
                !navigator.onLine
                ||
                !('caches' in window)
            ) {

                return;

            }


            const urls = [

                @json(
                    route(
                        'pendaki.riwayat'
                    )
                ),

                @json(
                    route(
                        'pendaki.riwayat',
                        [
                            'status' =>
                                'completed'
                        ]
                    )
                ),

                @json(
                    route(
                        'pendaki.riwayat',
                        [
                            'status' =>
                                'ongoing'
                        ]
                    )
                )

            ];


            try {

                const cache =
                    await caches.open(
                        HISTORY_CACHE
                    );


                for (
                    const url
                    of urls
                ) {

                    try {

                        const response =
                            await fetch(
                                url,
                                {
                                    credentials:
                                        'same-origin',

                                    cache:
                                        'no-store'
                                }
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
                    ) {

                        // skip one failed filter

                    }

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking Riwayat] Prefetch gagal:',
                    error
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | LAST CACHE
        |--------------------------------------------------------------------------
        */

        function showLastCacheTime() {

            let saved =
                null;


            try {

                saved =
                    localStorage.getItem(
                        HISTORY_CACHE_TIME_KEY
                    );

            } catch (
                error
            ) {}


            if (
                !saved
            ) {

                cacheTimestampContainer
                    .classList
                    .remove(
                        'show'
                    );


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


            cacheTimestamp
                .innerText =
                    date.toLocaleString(
                        'id-ID',
                        {
                            dateStyle:
                                'medium',

                            timeStyle:
                                'short'
                        }
                    );


            cacheTimestampContainer
                .classList
                .add(
                    'show'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | OFFLINE LINK
        |--------------------------------------------------------------------------
        */

        function setupOfflineLinks() {

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


                                    const target =
                                        link.href;


                                    const available =
                                        await isPageCached(
                                            target
                                        );


                                    if (
                                        available
                                    ) {

                                        window.location.href =
                                            target;


                                        return;

                                    }


                                    showToast(
                                        'Halaman belum tersimpan',
                                        'Buka halaman tersebut minimal satu kali saat online agar dapat digunakan saat offline.',
                                        'warning'
                                    );

                                }
                            );

                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION LINKS
        |--------------------------------------------------------------------------
        */

        function setupPaginationOfflineLinks() {

            const pagination =
                document.getElementById(
                    'historyPagination'
                );


            if (
                !pagination
            ) {

                return;

            }


            pagination
                .querySelectorAll(
                    'a'
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


                                    const available =
                                        await isPageCached(
                                            link.href
                                        );


                                    if (
                                        available
                                    ) {

                                        window.location.href =
                                            link.href;


                                        return;

                                    }


                                    showToast(
                                        'Halaman belum tersedia offline',
                                        'Halaman riwayat ini belum pernah dibuka saat online.',
                                        'warning'
                                    );

                                }
                            );

                    }
                );

        }


        /*
        |--------------------------------------------------------------------------
        | CACHE CHECK
        |--------------------------------------------------------------------------
        */

        async function isPageCached(
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


                return Boolean(
                    response
                );


            } catch (
                error
            ) {

                return false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | LIVE TRACKING QUEUE
        |--------------------------------------------------------------------------
        |
        | Jika pengguna sebelumnya melakukan Live Tracking dalam kondisi offline,
        | halaman Riwayat juga mencoba membantu mengirim antrean ketika internet
        | sudah kembali.
        |
        */

        function openLiveTrackingDatabase() {

            return new Promise(
                function (
                    resolve,
                    reject
                ) {

                    if (
                        liveTrackingDatabase
                    ) {

                        resolve(
                            liveTrackingDatabase
                        );

                        return;

                    }


                    const request =
                        indexedDB.open(
                            LIVE_TRACK_DB,
                            LIVE_TRACK_DB_VERSION
                        );


                    request.onsuccess =
                        function (
                            event
                        ) {

                            liveTrackingDatabase =
                                event.target.result;


                            resolve(
                                liveTrackingDatabase
                            );

                        };


                    request.onerror =
                        function () {

                            reject(
                                request.error
                            );

                        };


                    request.onupgradeneeded =
                        function () {

                            /*
                            |--------------------------------------------------------------------------
                            | Jangan menciptakan schema baru dari halaman Riwayat.
                            | Schema normal dibuat halaman Live Tracking.
                            |--------------------------------------------------------------------------
                            */

                        };

                }
            );

        }


        async function getLiveTrackingQueue() {

            try {

                const db =
                    await openLiveTrackingDatabase();


                if (
                    !db.objectStoreNames
                        .contains(
                            LIVE_TRACK_QUEUE_STORE
                        )
                ) {

                    return [];

                }


                return new Promise(
                    function (
                        resolve,
                        reject
                    ) {

                        const transaction =
                            db.transaction(
                                LIVE_TRACK_QUEUE_STORE,
                                'readonly'
                            );


                        const store =
                            transaction
                                .objectStore(
                                    LIVE_TRACK_QUEUE_STORE
                                );


                        const request =
                            store.getAll();


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


            } catch (
                error
            ) {

                return [];

            }

        }


        async function deleteLiveTrackingQueueItem(
            id
        ) {

            const db =
                await openLiveTrackingDatabase();


            return new Promise(
                function (
                    resolve,
                    reject
                ) {

                    const transaction =
                        db.transaction(
                            LIVE_TRACK_QUEUE_STORE,
                            'readwrite'
                        );


                    const store =
                        transaction
                            .objectStore(
                                LIVE_TRACK_QUEUE_STORE
                            );


                    const request =
                        store.delete(
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


        async function syncLiveTrackingQueue() {

            if (
                !navigator.onLine
                ||
                liveQueueSyncing
            ) {

                return;

            }


            liveQueueSyncing =
                true;


            try {

                const queue =
                    (
                        await getLiveTrackingQueue()
                    )
                    .sort(
                        function (
                            a,
                            b
                        ) {

                            return (
                                a.created_at
                                -
                                b.created_at
                            );

                        }
                    );


                if (
                    queue.length ===
                        0
                ) {

                    return;

                }


                const csrfToken =
                    document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .content;


                let synced =
                    0;


                for (
                    const item
                    of queue
                ) {

                    try {

                        const response =
                            await fetch(
                                item.endpoint,
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
                                            item.payload
                                        )
                                }
                            );


                        if (
                            response.ok
                        ) {

                            await deleteLiveTrackingQueueItem(
                                item.id
                            );


                            synced++;


                            continue;

                        }


                        if (
                            response.status ===
                                401
                            ||
                            response.status ===
                                419
                            ||
                            response.status >=
                                500
                            ||
                            response.status ===
                                429
                        ) {

                            break;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Jangan otomatis hapus SOS / COMPLETE.
                        |--------------------------------------------------------------------------
                        */

                        if (
                            item.kind ===
                                'sos'
                            ||
                            item.kind ===
                                'complete'
                        ) {

                            break;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Location dengan 4xx invalid dapat dilewati.
                        |--------------------------------------------------------------------------
                        */

                        await deleteLiveTrackingQueueItem(
                            item.id
                        );


                    } catch (
                        error
                    ) {

                        break;

                    }

                }


                if (
                    synced >
                        0
                ) {

                    showToast(
                        'Tracking tersinkronisasi',
                        `${synced} data tracking offline berhasil dikirim ke server BaliHiking.`,
                        'success'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Refresh setelah sync supaya Riwayat dapat menampilkan status terbaru.
                    |--------------------------------------------------------------------------
                    */

                    setTimeout(
                        function () {

                            if (
                                navigator.onLine
                            ) {

                                window.location.reload();

                            }

                        },
                        1500
                    );

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking Riwayat] Sinkronisasi tracking gagal:',
                    error
                );


            } finally {


                liveQueueSyncing =
                    false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | TOAST
        |--------------------------------------------------------------------------
        */

        function showToast(
            title,
            message,
            type =
                'success'
        ) {

            const toast =
                document.getElementById(
                    'historyToast'
                );


            const titleElement =
                document.getElementById(
                    'historyToastTitle'
                );


            const messageElement =
                document.getElementById(
                    'historyToastMessage'
                );


            clearTimeout(
                toastTimer
            );


            toast
                .classList
                .remove(
                    'show',
                    'warning',
                    'error'
                );


            if (
                type ===
                    'warning'
            ) {

                toast
                    .classList
                    .add(
                        'warning'
                    );

            }


            if (
                type ===
                    'error'
            ) {

                toast
                    .classList
                    .add(
                        'error'
                    );

            }


            titleElement
                .innerText =
                    title;


            messageElement
                .innerText =
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
                    4300
                );

        }

    </script>


</body>

</html>