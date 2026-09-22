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
        content="{{ $mountain->name }} - Informasi gunung, jalur dan perkiraan cuaca BaliHiking"
    >


    <title>
        {{ $mountain->name }} - BaliHiking
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
        | HERO
        |--------------------------------------------------------------------------
        */

        .mountain-hero {

            position:
                relative;

            min-height:
                224px;

            overflow:
                hidden;

            border-radius:
                16px;

            background:
                #1a382b;

        }


        .mountain-hero-fallback {

            position:
                absolute;

            inset:
                0;

            z-index:
                1;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            flex-direction:
                column;

            gap:
                12px;

            background:
                linear-gradient(
                    145deg,
                    #1a382b,
                    #244b3a
                );

            color:
                rgba(
                    255,
                    255,
                    255,
                    .35
                );

        }


        .mountain-hero-fallback img {

            width:
                64px;

            height:
                64px;

            border-radius:
                16px;

            opacity:
                .35;

        }


        .mountain-hero-image {

            position:
                absolute;

            inset:
                0;

            z-index:
                2;

            width:
                100%;

            height:
                100%;

            object-fit:
                cover;

        }


        .mountain-hero-overlay {

            position:
                absolute;

            inset:
                0;

            z-index:
                3;

            background:
                linear-gradient(
                    to top,
                    rgba(0, 0, 0, .82),
                    rgba(0, 0, 0, .15) 60%,
                    rgba(0, 0, 0, .02)
                );

        }


        .mountain-hero-content {

            position:
                absolute;

            right:
                0;

            bottom:
                0;

            left:
                0;

            z-index:
                4;

        }



        /*
        |--------------------------------------------------------------------------
        | WEATHER
        |--------------------------------------------------------------------------
        */

        .weather-scroll {

            display:
                flex;

            gap:
                10px;

            overflow-x:
                auto;

            padding-bottom:
                4px;

            scrollbar-width:
                none;

            -webkit-overflow-scrolling:
                touch;

        }


        .weather-scroll::-webkit-scrollbar {

            display:
                none;

        }


        .weather-day {

            min-width:
                112px;

            flex:
                0 0 112px;

        }


        #weatherOfflineBadge {

            display:
                none;

        }


        #weatherOfflineBadge.show {

            display:
                inline-flex;

        }


        #weatherStaleBadge {

            display:
                none;

        }


        #weatherStaleBadge.show {

            display:
                inline-flex;

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
                    90px +
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
                rgba(
                    0,
                    0,
                    0,
                    .17
                );

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
        | OFFLINE INFO
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


        #offlineBadge {

            display:
                none;

        }


        #offlineBadge.show {

            display:
                inline-flex;

        }


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

        #mountainToast {

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
                0 13px 35px
                rgba(
                    0,
                    0,
                    0,
                    .18
                );

            transition:
                all .25s ease;

        }


        #mountainToast.show {

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


        #mountainToast.warning {

            background:
                #f06535;

        }



        /*
        |--------------------------------------------------------------------------
        | CHECKPOINT
        |--------------------------------------------------------------------------
        */

        .checkpoint-row {

            position:
                relative;

        }


        .checkpoint-row:not(:last-child)::after {

            content:
                "";

            position:
                absolute;

            top:
                28px;

            bottom:
                -12px;

            left:
                13px;

            width:
                1px;

            background:
                rgba(
                    26,
                    56,
                    43,
                    .12
                );

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


        @media (
            min-width:
                640px
        ) {

            .mountain-hero {

                min-height:
                    288px;

            }


            .weather-day {

                flex:
                    1 1 0;

                min-width:
                    105px;

            }

        }

    </style>

</head>


<body
    id="appBody"
    class="
        bg-brand-cream
        pb-28
        font-sans
        text-brand-dark
        antialiased
    "
>


    {{-- ========================================================= --}}
    {{-- TOAST --}}
    {{-- ========================================================= --}}

    <div
        id="mountainToast"
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
                    id="mountainToastTitle"
                    class="
                        text-xs
                        font-extrabold
                    "
                >
                    BaliHiking
                </p>


                <p
                    id="mountainToastMessage"
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
            Mode Offline
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
            bg-brand-cream/95
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
                href="{{ route('pendaki.dashboard') }}"
                data-offline-link
                class="
                    flex
                    h-9
                    w-9
                    shrink-0
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



            <div
                class="
                    min-w-0
                    flex-1
                "
            >

                <div
                    class="
                        flex
                        items-center
                        gap-2
                    "
                >

                    <p
                        class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-wide
                            text-brand-dark/40
                        "
                    >
                        Informasi Gunung
                    </p>


                    <span
                        id="offlineBadge"
                        class="
                            items-center
                            gap-1
                            text-[8px]
                            font-extrabold
                            uppercase
                            tracking-wide
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


                <h1
                    class="
                        truncate
                        text-sm
                        font-extrabold
                    "
                >
                    {{ $mountain->name }}
                </h1>

            </div>



            <div
                class="
                    h-8
                    w-8
                    shrink-0
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

            </div>

        </div>

    </header>



    {{-- ========================================================= --}}
    {{-- MAIN --}}
    {{-- ========================================================= --}}

    <main
        class="
            mx-auto
            max-w-3xl
            space-y-6
            px-4
            pt-5
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
                        Informasi gunung, jalur dan perkiraan
                        cuaca yang tampil merupakan data terakhir
                        yang tersimpan di perangkat. BaliHiking
                        akan memperbaruinya setelah internet kembali.
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
                            Halaman tersimpan:
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
        {{-- HERO --}}
        {{-- ===================================================== --}}

        <section
            class="mountain-hero"
        >

            <div
                class="mountain-hero-fallback"
            >

                <img
                    src="{{ asset('icons/icon-192.png') }}"
                    alt="BaliHiking"
                >


                <span
                    class="
                        text-xs
                        font-bold
                        text-white/35
                    "
                >
                    BaliHiking
                </span>

            </div>



            @if($mountain->cover_image_url)

                <img
                    id="mountainCoverImage"
                    src="{{ $mountain->cover_image_url }}"
                    alt="{{ $mountain->name }}"
                    class="mountain-hero-image"
                    loading="eager"
                    onerror="this.style.display='none'"
                >

            @endif



            <div
                class="mountain-hero-overlay"
            ></div>



            <div
                class="
                    mountain-hero-content
                    p-6
                    text-white
                "
            >

                <div
                    class="
                        mb-3
                        inline-flex
                        items-center
                        gap-1.5
                        rounded-full
                        border
                        border-white/15
                        bg-white/10
                        px-2.5
                        py-1
                        text-[9px]
                        font-bold
                        backdrop-blur-md
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

                    Gunung Bali

                </div>


                <h2
                    class="
                        text-2xl
                        font-extrabold
                        tracking-tight
                    "
                >
                    {{ $mountain->name }}
                </h2>



                <div
                    class="
                        mt-2
                        flex
                        flex-wrap
                        items-center
                        gap-x-4
                        gap-y-2
                        text-xs
                        text-white/75
                    "
                >


                    @if($mountain->location)

                        <span
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
                                    d="M17.657 16.657 13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0Z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0Z"
                                />

                            </svg>

                            {{ $mountain->location }}

                        </span>

                    @endif



                    @if($mountain->elevation_m)

                        <span
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
                                    d="M3 20h18L15 6l-4 7-2-3-6 10Z"
                                />

                            </svg>

                            {{
                                number_format(
                                    $mountain->elevation_m,
                                    0,
                                    ',',
                                    '.'
                                )
                            }}

                            mdpl

                        </span>

                    @endif



                    <span
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
                                d="M3 20h18L15 6l-4 7-2-3-6 10Z"
                            />

                        </svg>

                        {{
                            $mountain
                                ->hikingTrails
                                ->count()
                        }}

                        jalur

                    </span>

                </div>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- WEATHER --}}
        {{-- ===================================================== --}}

        <section
            id="weatherCard"
            class="
                overflow-hidden
                rounded-2xl
                border
                border-brand-dark/10
                bg-white
                shadow-sm
            "
        >

            <div
                class="
                    flex
                    items-start
                    justify-between
                    gap-3
                    border-b
                    border-brand-dark/10
                    px-5
                    py-4
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
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-sky-50
                            text-sky-600
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
                                d="M17.5 19H6.75a4.75 4.75 0 01-.75-9.44A6 6 0 0117.4 11a4 4 0 01.1 8Z"
                            />

                        </svg>

                    </div>


                    <div>

                        <h2
                            class="
                                text-base
                                font-extrabold
                            "
                        >
                            Perkiraan Cuaca
                        </h2>


                        <p
                            class="
                                mt-0.5
                                text-[10px]
                                text-brand-dark/40
                            "
                        >
                            {{ $mountain->name }}

                            @if($mountain->location)

                                · {{ $mountain->location }}

                            @endif
                        </p>

                    </div>

                </div>



                <div
                    class="
                        flex
                        flex-col
                        items-end
                        gap-1
                    "
                >

                    <span
                        id="weatherOfflineBadge"
                        class="
                            items-center
                            gap-1
                            rounded-full
                            bg-orange-100
                            px-2.5
                            py-1
                            text-[8px]
                            font-black
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


                    <span
                        id="weatherStaleBadge"
                        class="
                            items-center
                            rounded-full
                            bg-amber-100
                            px-2
                            py-1
                            text-[8px]
                            font-black
                            text-amber-700
                        "
                    >
                        Data tersimpan
                    </span>


                    <p
                        id="weatherUpdated"
                        class="
                            text-right
                            text-[8px]
                            text-brand-dark/35
                        "
                    >
                        Memuat cuaca...
                    </p>

                </div>

            </div>



            {{-- CURRENT --}}

            <div
                id="weatherCurrent"
                class="
                    px-5
                    py-5
                "
            >

                <div
                    class="
                        animate-pulse
                        rounded-xl
                        bg-brand-dark/5
                        p-5
                    "
                >

                    <div
                        class="
                            h-4
                            w-28
                            rounded
                            bg-brand-dark/10
                        "
                    ></div>


                    <div
                        class="
                            mt-4
                            h-10
                            w-36
                            rounded
                            bg-brand-dark/10
                        "
                    ></div>

                </div>

            </div>



            {{-- FORECAST --}}

            <div
                class="
                    border-t
                    border-brand-dark/10
                    px-5
                    py-4
                "
            >

                <div
                    class="
                        mb-3
                        flex
                        items-center
                        justify-between
                        gap-3
                    "
                >

                    <p
                        class="
                            text-[10px]
                            font-black
                            uppercase
                            tracking-wide
                            text-brand-dark/40
                        "
                    >
                        5 Hari Ke Depan
                    </p>


                    <button
                        id="refreshWeatherButton"
                        type="button"
                        class="
                            rounded-lg
                            bg-brand-orange/10
                            px-3
                            py-1.5
                            text-[9px]
                            font-bold
                            text-brand-orange
                            transition
                            active:scale-95
                            disabled:opacity-40
                        "
                    >
                        Perbarui
                    </button>

                </div>


                <div
                    id="weatherForecast"
                    class="weather-scroll"
                ></div>

            </div>



            {{-- WARNING --}}

            <div
                class="
                    border-t
                    border-brand-dark/10
                    bg-brand-cream/60
                    px-5
                    py-3
                "
            >

                <div
                    class="
                        flex
                        items-start
                        gap-2
                    "
                >

                    <svg
                        class="
                            mt-0.5
                            h-4
                            w-4
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
                            d="M12 9v4m0 4h.01M10.3 3.8 2.8 17a2 2 0 001.74 3h14.92a2 2 0 001.74-3L13.7 3.8a2 2 0 00-3.4 0Z"
                        />

                    </svg>


                    <p
                        class="
                            text-[9px]
                            leading-relaxed
                            text-brand-dark/45
                        "
                    >
                        Cuaca pegunungan dapat berubah dengan cepat.
                        Gunakan prakiraan ini sebagai informasi
                        pendukung dan tetap perhatikan kondisi
                        aktual sebelum serta selama pendakian.
                    </p>

                </div>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- DESCRIPTION --}}
        {{-- ===================================================== --}}

        @if($mountain->description)

            <section
                class="
                    rounded-2xl
                    border
                    border-brand-dark/10
                    bg-white
                    p-5
                    shadow-sm
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
                            h-9
                            w-9
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-brand-dark/5
                            text-brand-dark
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
                                r="9"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 10v6M12 7h.01"
                            />

                        </svg>

                    </div>


                    <div>

                        <h2
                            class="
                                text-base
                                font-extrabold
                            "
                        >
                            Tentang Gunung
                        </h2>


                        <p
                            class="
                                text-[10px]
                                text-brand-dark/40
                            "
                        >
                            Informasi umum
                        </p>

                    </div>

                </div>


                <p
                    class="
                        mt-4
                        whitespace-pre-line
                        text-sm
                        leading-7
                        text-brand-dark/65
                    "
                >
                    {{ $mountain->description }}
                </p>

            </section>

        @endif



        {{-- ===================================================== --}}
        {{-- TRAILS --}}
        {{-- ===================================================== --}}

        <section>

            <div
                class="
                    mb-4
                    flex
                    items-end
                    justify-between
                    gap-4
                "
            >

                <div>

                    <h2
                        class="
                            text-base
                            font-extrabold
                        "
                    >
                        Jalur Pendakian
                    </h2>


                    <p
                        class="
                            mt-1
                            text-xs
                            text-brand-dark/45
                        "
                    >
                        Pilih jalur pendakian yang tersedia.
                    </p>

                </div>


                <span
                    class="
                        shrink-0
                        rounded-full
                        bg-brand-dark/5
                        px-2.5
                        py-1
                        text-[9px]
                        font-bold
                        text-brand-dark/50
                    "
                >

                    {{
                        $mountain
                            ->hikingTrails
                            ->count()
                    }}

                    jalur

                </span>

            </div>



            @forelse(
                $mountain->hikingTrails
                as $trail
            )

                <article
                    class="
                        mb-4
                        overflow-hidden
                        rounded-2xl
                        border
                        border-brand-dark/10
                        bg-white
                        shadow-sm
                    "
                >

                    <div
                        class="p-5"
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
                                        text-sm
                                        font-extrabold
                                    "
                                >
                                    {{ $trail->name }}
                                </h3>


                                @if($trail->difficulty)

                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-brand-dark/45
                                        "
                                    >
                                        Tingkat kesulitan:

                                        <span
                                            class="
                                                font-semibold
                                                text-brand-dark/65
                                            "
                                        >
                                            {{
                                                ucfirst(
                                                    $trail->difficulty
                                                )
                                            }}
                                        </span>

                                    </p>

                                @endif

                            </div>



                            <span
                                class="
                                    shrink-0
                                    rounded-lg
                                    bg-brand-orange/10
                                    px-2.5
                                    py-1
                                    text-[10px]
                                    font-bold
                                    text-brand-orange
                                "
                            >

                                {{
                                    $trail->checkpoints_count
                                }}

                                checkpoint

                            </span>

                        </div>



                        <div
                            class="
                                mt-4
                                grid
                                grid-cols-2
                                gap-3
                            "
                        >

                            <div
                                class="
                                    rounded-xl
                                    bg-brand-cream
                                    p-3
                                "
                            >

                                <div
                                    class="
                                        flex
                                        items-center
                                        gap-2
                                    "
                                >

                                    <svg
                                        class="
                                            h-4
                                            w-4
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
                                            d="M5 12h14M5 12l3-3m-3 3 3 3m11-3-3-3m3 3-3 3"
                                        />

                                    </svg>


                                    <p
                                        class="
                                            text-[10px]
                                            text-brand-dark/40
                                        "
                                    >
                                        Jarak
                                    </p>

                                </div>


                                <p
                                    class="
                                        mt-2
                                        text-sm
                                        font-bold
                                    "
                                >

                                    {{
                                        $trail->distance_km
                                            ?
                                            number_format(
                                                $trail->distance_km,
                                                1,
                                                ',',
                                                '.'
                                            ) . ' km'
                                            :
                                            '-'
                                    }}

                                </p>

                            </div>



                            <div
                                class="
                                    rounded-xl
                                    bg-brand-cream
                                    p-3
                                "
                            >

                                <div
                                    class="
                                        flex
                                        items-center
                                        gap-2
                                    "
                                >

                                    <svg
                                        class="
                                            h-4
                                            w-4
                                            text-brand-orange
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
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 7v5l3 2"
                                        />

                                    </svg>


                                    <p
                                        class="
                                            text-[10px]
                                            text-brand-dark/40
                                        "
                                    >
                                        Estimasi
                                    </p>

                                </div>


                                <p
                                    class="
                                        mt-2
                                        text-sm
                                        font-bold
                                    "
                                >

                                    {{
                                        $trail->estimated_time_hours
                                            ?
                                            $trail->estimated_time_hours
                                                . ' jam'
                                            :
                                            '-'
                                    }}

                                </p>

                            </div>

                        </div>



                        @if(
                            $trail
                                ->checkpoints
                                ->isNotEmpty()
                        )

                            <div
                                class="
                                    mt-5
                                    border-t
                                    border-brand-dark/10
                                    pt-4
                                "
                            >

                                <div
                                    class="
                                        mb-3
                                        flex
                                        items-center
                                        justify-between
                                    "
                                >

                                    <p
                                        class="
                                            text-xs
                                            font-bold
                                        "
                                    >
                                        Checkpoint
                                    </p>


                                    <span
                                        class="
                                            text-[9px]
                                            font-semibold
                                            text-brand-dark/35
                                        "
                                    >

                                        {{
                                            $trail
                                                ->checkpoints
                                                ->count()
                                        }}

                                        titik

                                    </span>

                                </div>



                                <div
                                    class="space-y-3"
                                >

                                    @foreach(
                                        $trail->checkpoints
                                        as $checkpoint
                                    )

                                        <div
                                            class="
                                                checkpoint-row
                                                flex
                                                items-start
                                                gap-3
                                            "
                                        >

                                            <span
                                                class="
                                                    relative
                                                    z-10
                                                    flex
                                                    h-7
                                                    w-7
                                                    shrink-0
                                                    items-center
                                                    justify-center
                                                    rounded-full
                                                    bg-brand-dark
                                                    text-[10px]
                                                    font-bold
                                                    text-white
                                                "
                                            >
                                                {{ $loop->iteration }}
                                            </span>



                                            <div
                                                class="
                                                    min-w-0
                                                    flex-1
                                                    pt-1
                                                "
                                            >

                                                <p
                                                    class="
                                                        text-xs
                                                        font-semibold
                                                    "
                                                >
                                                    {{ $checkpoint->name }}
                                                </p>


                                                <p
                                                    class="
                                                        mt-0.5
                                                        text-[10px]
                                                        text-brand-dark/40
                                                    "
                                                >

                                                    {{
                                                        $checkpoint->type
                                                        ??
                                                        'Checkpoint'
                                                    }}


                                                    @if(
                                                        $checkpoint
                                                            ->elevation_m
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

                            </div>

                        @endif

                    </div>



                    <div
                        class="
                            border-t
                            border-brand-dark/10
                            bg-brand-cream/40
                            p-3
                        "
                    >

                        <a
                            href="{{
                                route(
                                    'pendaki.trail.show',
                                    $trail
                                )
                            }}"
                            data-offline-link
                            class="
                                flex
                                h-11
                                w-full
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
                                active:scale-[0.99]
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

                            Lihat Peta Jalur

                        </a>

                    </div>

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
                        py-10
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
                            text-brand-dark/25
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


                    <p
                        class="
                            mt-4
                            text-sm
                            font-bold
                        "
                    >
                        Belum ada jalur aktif
                    </p>


                    <p
                        class="
                            mt-1
                            text-xs
                            leading-relaxed
                            text-brand-dark/45
                        "
                    >
                        Jalur pendakian untuk gunung ini
                        belum tersedia.
                    </p>

                </div>

            @endforelse

        </section>


        <div class="h-3"></div>

    </main>



    {{-- ========================================================= --}}
    {{-- BOTTOM NAVIGATION --}}
    {{-- ========================================================= --}}

    @include(
        'pendaki.components.bottom-nav',
        [
            'active' => 'dashboard',
        ]
    )



    {{-- ========================================================= --}}
    {{-- OFFLINE + WEATHER ENGINE --}}
    {{-- ========================================================= --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | PAGE CACHE CONFIG
        |--------------------------------------------------------------------------
        */

        const MOUNTAIN_CACHE =
            'balihiking-mountain-pages-v4';


        const MOUNTAIN_CACHE_TIME_KEY =
            'balihiking_mountain_{{ $mountain->id }}_cache_time';


        const dashboardUrl =
            @json(
                route(
                    'pendaki.dashboard'
                )
            );


        const mountainCoverUrl =
            @json(
                $mountain->cover_image_url
            );


        const trailUrls =
            @json(
                $mountain
                    ->hikingTrails
                    ->map(
                        fn ($trail) =>
                            route(
                                'pendaki.trail.show',
                                $trail
                            )
                    )
                    ->values()
            );



        /*
        |--------------------------------------------------------------------------
        | WEATHER CONFIG
        |--------------------------------------------------------------------------
        */

        const WEATHER_STORAGE_KEY =
            'balihiking_weather_{{ $mountain->id }}';


        const WEATHER_STORAGE_TIME_KEY =
            'balihiking_weather_{{ $mountain->id }}_time';


        const weatherEndpoint =
            @json(
                route(
                    'pendaki.mountain.weather',
                    $mountain
                )
            );


        const initialWeather =
            @json(
                $weather
                ??
                null
            );


        let toastTimer =
            null;



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

                updateNetworkUI();

                showCacheTimestamp();

                setupOfflineLinks();

                setupWeather();


                if (
                    navigator.onLine
                ) {

                    await cacheCurrentMountainPage();

                    await cacheLocalAssets();

                    await cacheMountainCover();

                    await prefetchTrailPages();

                    await refreshWeather(
                        false
                    );

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
                            '[BaliHiking Gunung] Service Worker aktif:',
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
                            '[BaliHiking Gunung] Service Worker gagal:',
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

                updateNetworkUI();

                updateWeatherNetworkUI();


                showToast(
                    'Internet kembali aktif',
                    'BaliHiking sedang memperbarui informasi gunung, jalur, dan cuaca.'
                );


                await cacheCurrentMountainPage();

                await cacheMountainCover();

                await prefetchTrailPages();

                await refreshWeather(
                    false
                );

            }
        );


        window.addEventListener(
            'offline',
            function () {

                updateNetworkUI();

                updateWeatherNetworkUI();


                const savedWeather =
                    loadWeatherFromDevice();


                if (
                    savedWeather
                ) {

                    renderWeather(
                        savedWeather,
                        true
                    );

                }


                showToast(
                    'Mode Offline',
                    'Informasi gunung, jalur dan cuaca terakhir yang tersimpan tetap dapat digunakan.',
                    'warning'
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | NETWORK UI
        |--------------------------------------------------------------------------
        */

        function updateNetworkUI() {

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
                    1600
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
                        'Offline · data gunung tersimpan';


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


                showCacheTimestamp();

            }

        }



        /*
        |--------------------------------------------------------------------------
        | CACHE CURRENT PAGE
        |--------------------------------------------------------------------------
        */

        async function cacheCurrentMountainPage() {

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
                        MOUNTAIN_CACHE
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
                                    'mountain'

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
                            MOUNTAIN_CACHE_TIME_KEY,
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
                    '[BaliHiking Gunung] Cache halaman gagal:',
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

                '/vendor/tailwindcss.js',

                '/icons/icon-192.png',

                '/icons/icon-512.png',

                '/icons/icon-maskable-192.png',

                '/icons/icon-maskable-512.png',

            ];


            try {

                const cache =
                    await caches.open(
                        MOUNTAIN_CACHE
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
                    '[BaliHiking Gunung] Cache asset gagal:',
                    error
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | CACHE COVER
        |--------------------------------------------------------------------------
        */

        async function cacheMountainCover() {

            if (
                !mountainCoverUrl
                ||
                !navigator.onLine
                ||
                !('caches' in window)
            ) {

                return;

            }


            try {

                const cache =
                    await caches.open(
                        MOUNTAIN_CACHE
                    );


                const response =
                    await fetch(
                        mountainCoverUrl,
                        {
                            mode:
                                'no-cors',

                            cache:
                                'force-cache'
                        }
                    );


                if (
                    response.ok
                    ||
                    response.type ===
                        'opaque'
                ) {

                    await cache.put(
                        mountainCoverUrl,
                        response.clone()
                    );

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking Gunung] Cover tidak dapat dicache.'
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | PREFETCH TRAIL
        |--------------------------------------------------------------------------
        */

        async function prefetchTrailPages() {

            if (
                !navigator.onLine
                ||
                !('caches' in window)
                ||
                !Array.isArray(
                    trailUrls
                )
            ) {

                return;

            }


            try {

                const cache =
                    await caches.open(
                        MOUNTAIN_CACHE
                    );


                for (
                    const url
                    of trailUrls
                ) {

                    try {

                        const response =
                            await fetch(
                                url,
                                {
                                    method:
                                        'GET',

                                    credentials:
                                        'same-origin',

                                    cache:
                                        'no-store',

                                    headers: {

                                        'X-BaliHiking-Prefetch':
                                            'trail'

                                    }

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
                    ) {}

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking Gunung] Prefetch jalur gagal:',
                    error
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | OFFLINE LINKS
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
                                        'Buka halaman tersebut minimal satu kali ketika online agar BaliHiking dapat menyimpannya.',
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


                if (
                    response
                ) {

                    return true;

                }


                const cacheNames =
                    await caches.keys();


                for (
                    const cacheName
                    of cacheNames
                ) {

                    const cache =
                        await caches.open(
                            cacheName
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


                    response =
                        await cache.match(
                            parsed.pathname,
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
        | PAGE CACHE TIME
        |--------------------------------------------------------------------------
        */

        function showCacheTimestamp() {

            let saved =
                null;


            try {

                saved =
                    localStorage.getItem(
                        MOUNTAIN_CACHE_TIME_KEY
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
        | WEATHER SETUP
        |--------------------------------------------------------------------------
        */

        function setupWeather() {

            if (
                initialWeather
            ) {

                saveWeatherToDevice(
                    initialWeather
                );


                renderWeather(
                    initialWeather,
                    !navigator.onLine
                );


            } else {


                const savedWeather =
                    loadWeatherFromDevice();


                if (
                    savedWeather
                ) {

                    renderWeather(
                        savedWeather,
                        true
                    );


                } else {


                    renderWeatherUnavailable();

                }

            }


            updateWeatherNetworkUI();


            const button =
                document.getElementById(
                    'refreshWeatherButton'
                );


            if (
                button
            ) {

                button.addEventListener(
                    'click',
                    async function () {

                        if (
                            !navigator.onLine
                        ) {

                            showToast(
                                'Cuaca Offline',
                                'Perkiraan terbaru membutuhkan internet. BaliHiking tetap menampilkan data cuaca terakhir yang tersimpan.',
                                'warning'
                            );


                            return;

                        }


                        await refreshWeather(
                            true
                        );

                    }
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | REFRESH WEATHER
        |--------------------------------------------------------------------------
        */

        async function refreshWeather(
            notify =
                false
        ) {

            if (
                !navigator.onLine
            ) {

                return;

            }


            const button =
                document.getElementById(
                    'refreshWeatherButton'
                );


            if (
                button
            ) {

                button.disabled =
                    true;


                button.textContent =
                    'Memuat...';

            }


            try {

                const response =
                    await fetch(
                        weatherEndpoint,
                        {
                            method:
                                'GET',

                            credentials:
                                'same-origin',

                            cache:
                                'no-store',

                            headers: {

                                'Accept':
                                    'application/json',

                            }

                        }
                    );


                if (
                    !response.ok
                ) {

                    throw new Error(
                        'Weather request failed'
                    );

                }


                const result =
                    await response.json();


                if (
                    !result.success
                    ||
                    !result.weather
                ) {

                    throw new Error(
                        'Weather unavailable'
                    );

                }


                saveWeatherToDevice(
                    result.weather
                );


                renderWeather(
                    result.weather,
                    false
                );


                if (
                    notify
                ) {

                    showToast(
                        'Cuaca Diperbarui',
                        'Perkiraan cuaca terbaru berhasil diperbarui.'
                    );

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking Weather]',
                    error
                );


                const savedWeather =
                    loadWeatherFromDevice();


                if (
                    savedWeather
                ) {

                    renderWeather(
                        savedWeather,
                        true
                    );


                } else {


                    renderWeatherUnavailable();

                }


                if (
                    notify
                ) {

                    showToast(
                        'Cuaca Belum Diperbarui',
                        'BaliHiking menggunakan perkiraan cuaca terakhir yang tersimpan.',
                        'warning'
                    );

                }


            } finally {


                if (
                    button
                ) {

                    button.disabled =
                        false;


                    button.textContent =
                        'Perbarui';

                }

            }

        }



        /*
        |--------------------------------------------------------------------------
        | SAVE WEATHER
        |--------------------------------------------------------------------------
        */

        function saveWeatherToDevice(
            weather
        ) {

            try {

                localStorage.setItem(
                    WEATHER_STORAGE_KEY,
                    JSON.stringify(
                        weather
                    )
                );


                localStorage.setItem(
                    WEATHER_STORAGE_TIME_KEY,
                    String(
                        Date.now()
                    )
                );


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking Weather] Penyimpanan offline gagal.'
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | LOAD WEATHER
        |--------------------------------------------------------------------------
        */

        function loadWeatherFromDevice() {

            try {

                const raw =
                    localStorage.getItem(
                        WEATHER_STORAGE_KEY
                    );


                if (
                    !raw
                ) {

                    return null;

                }


                return JSON.parse(
                    raw
                );


            } catch (
                error
            ) {

                return null;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | WEATHER NETWORK UI
        |--------------------------------------------------------------------------
        */

        function updateWeatherNetworkUI() {

            const badge =
                document.getElementById(
                    'weatherOfflineBadge'
                );


            if (
                !badge
            ) {

                return;

            }


            if (
                navigator.onLine
            ) {

                badge
                    .classList
                    .remove(
                        'show'
                    );


            } else {


                badge
                    .classList
                    .add(
                        'show'
                    );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | RENDER WEATHER
        |--------------------------------------------------------------------------
        */

        function renderWeather(
            weather,
            offline =
                false
        ) {

            if (
                !weather
                ||
                !weather.current
            ) {

                renderWeatherUnavailable();

                return;

            }


            const current =
                weather.current;


            const currentContainer =
                document.getElementById(
                    'weatherCurrent'
                );


            currentContainer.innerHTML =
                `
                <div
                    class="
                        flex
                        flex-col
                        gap-5
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                    "
                >

                    <div
                        class="
                            flex
                            items-center
                            gap-4
                        "
                    >

                        <div
                            class="
                                flex
                                h-16
                                w-16
                                shrink-0
                                items-center
                                justify-center
                                rounded-2xl
                                bg-sky-50
                                text-3xl
                            "
                        >
                            ${weatherIcon(
                                current.icon
                            )}
                        </div>


                        <div>

                            <div
                                class="
                                    flex
                                    items-start
                                    gap-1
                                "
                            >

                                <span
                                    class="
                                        text-4xl
                                        font-black
                                        tracking-tight
                                    "
                                >
                                    ${
                                        current.temperature
                                        ??
                                        '--'
                                    }
                                </span>


                                <span
                                    class="
                                        mt-1
                                        text-sm
                                        font-bold
                                        text-brand-dark/40
                                    "
                                >
                                    °C
                                </span>

                            </div>


                            <p
                                class="
                                    mt-1
                                    text-xs
                                    font-bold
                                "
                            >
                                ${
                                    escapeWeatherHtml(
                                        current.condition
                                        ??
                                        'Cuaca'
                                    )
                                }
                            </p>


                            <p
                                class="
                                    mt-0.5
                                    text-[9px]
                                    text-brand-dark/40
                                "
                            >
                                Terasa seperti

                                ${
                                    current.apparent_temperature
                                    ??
                                    '--'
                                }°C
                            </p>

                        </div>

                    </div>



                    <div
                        class="
                            grid
                            grid-cols-3
                            gap-2
                            sm:min-w-[280px]
                        "
                    >

                        ${weatherInfoBox(
                            'Kelembapan',
                            (
                                current.humidity
                                ??
                                '--'
                            )
                            +
                            '%'
                        )}


                        ${weatherInfoBox(
                            'Angin',
                            (
                                current.wind_speed
                                ??
                                '--'
                            )
                            +
                            ' km/j'
                        )}


                        ${weatherInfoBox(
                            'Awan',
                            (
                                current.cloud_cover
                                ??
                                '--'
                            )
                            +
                            '%'
                        )}

                    </div>

                </div>


                <div
                    class="
                        mt-4
                        grid
                        grid-cols-3
                        gap-2
                    "
                >

                    ${weatherInfoBox(
                        'Hujan',
                        (
                            current.precipitation
                            ??
                            0
                        )
                        +
                        ' mm'
                    )}


                    ${weatherInfoBox(
                        'Arah Angin',
                        current.wind_direction !==
                            null
                            &&
                            current.wind_direction !==
                            undefined

                            ?

                            current.wind_direction
                            +
                            '°'

                            :

                            '--'
                    )}


                    ${weatherInfoBox(
                        'Lokasi',
                        weather.coordinate_source ===
                            'database'

                            ?

                            'Koordinat'

                            :

                            'Alamat'
                    )}

                </div>
                `;


            renderDailyWeather(
                weather.daily
                ||
                []
            );


            const updated =
                document.getElementById(
                    'weatherUpdated'
                );


            const staleBadge =
                document.getElementById(
                    'weatherStaleBadge'
                );


            if (
                weather.stale
            ) {

                staleBadge
                    .classList
                    .add(
                        'show'
                    );


            } else {


                staleBadge
                    .classList
                    .remove(
                        'show'
                    );

            }


            if (
                offline
            ) {

                let savedTime =
                    null;


                try {

                    savedTime =
                        localStorage.getItem(
                            WEATHER_STORAGE_TIME_KEY
                        );

                } catch (
                    error
                ) {}


                if (
                    savedTime
                ) {

                    const date =
                        new Date(
                            Number(
                                savedTime
                            )
                        );


                    updated.textContent =
                        'Offline · tersimpan '
                        +
                        date.toLocaleString(
                            'id-ID',
                            {
                                dateStyle:
                                    'short',

                                timeStyle:
                                    'short'
                            }
                        );


                } else {


                    updated.textContent =
                        'Offline · data tersimpan';

                }


            } else {


                updated.textContent =
                    weather.updated_label
                        ?
                        'Update '
                        +
                        weather.updated_label
                        :
                        'Cuaca terbaru';

            }


            updateWeatherNetworkUI();

        }



        /*
        |--------------------------------------------------------------------------
        | INFO BOX
        |--------------------------------------------------------------------------
        */

        function weatherInfoBox(
            label,
            value
        ) {

            return `
                <div
                    class="
                        rounded-xl
                        bg-brand-cream
                        px-2
                        py-3
                        text-center
                    "
                >

                    <p
                        class="
                            text-[8px]
                            text-brand-dark/40
                        "
                    >
                        ${escapeWeatherHtml(label)}
                    </p>


                    <p
                        class="
                            mt-1
                            truncate
                            text-[10px]
                            font-black
                        "
                    >
                        ${escapeWeatherHtml(value)}
                    </p>

                </div>
            `;

        }



        /*
        |--------------------------------------------------------------------------
        | DAILY FORECAST
        |--------------------------------------------------------------------------
        */

        function renderDailyWeather(
            days
        ) {

            const container =
                document.getElementById(
                    'weatherForecast'
                );


            container.innerHTML =
                '';


            if (
                !Array.isArray(
                    days
                )
                ||
                days.length ===
                    0
            ) {

                container.innerHTML =
                    `
                    <p
                        class="
                            text-[10px]
                            text-brand-dark/40
                        "
                    >
                        Prakiraan harian belum tersedia.
                    </p>
                    `;


                return;

            }


            days.forEach(
                function (
                    day,
                    index
                ) {

                    const element =
                        document.createElement(
                            'div'
                        );


                    element.className =
                        'weather-day rounded-xl border border-brand-dark/10 bg-brand-cream p-3 text-center';


                    element.innerHTML =
                        `
                        <p
                            class="
                                text-[9px]
                                font-black
                                ${
                                    index === 0
                                        ?
                                        'text-brand-orange'
                                        :
                                        'text-brand-dark/50'
                                }
                            "
                        >
                            ${
                                index === 0
                                    ?
                                    'Hari ini'
                                    :
                                    escapeWeatherHtml(
                                        day.day_name
                                        ??
                                        '-'
                                    )
                            }
                        </p>


                        <p
                            class="
                                mt-0.5
                                truncate
                                text-[8px]
                                text-brand-dark/35
                            "
                        >
                            ${
                                escapeWeatherHtml(
                                    day.day_full
                                    ??
                                    ''
                                )
                            }
                        </p>


                        <div
                            class="
                                my-2
                                text-2xl
                            "
                        >
                            ${weatherIcon(
                                day.icon
                            )}
                        </div>


                        <p
                            class="
                                truncate
                                text-[9px]
                                font-semibold
                            "
                        >
                            ${
                                escapeWeatherHtml(
                                    day.condition
                                    ??
                                    '-'
                                )
                            }
                        </p>


                        <p
                            class="
                                mt-2
                                text-xs
                                font-black
                            "
                        >

                            ${
                                day.temperature_max
                                ??
                                '--'
                            }°

                            <span
                                class="
                                    font-semibold
                                    text-brand-dark/35
                                "
                            >

                                /

                                ${
                                    day.temperature_min
                                    ??
                                    '--'
                                }°

                            </span>

                        </p>


                        <div
                            class="
                                mt-2
                                flex
                                items-center
                                justify-center
                                gap-1
                                text-[8px]
                                font-bold
                                text-sky-600
                            "
                        >

                            <span>
                                💧
                            </span>

                            <span>
                                ${
                                    day.rain_probability
                                    ??
                                    0
                                }%
                            </span>

                        </div>


                        ${
                            day.wind_max !==
                                null
                                &&
                                day.wind_max !==
                                undefined

                                ?

                                `
                                <p
                                    class="
                                        mt-1
                                        text-[8px]
                                        text-brand-dark/35
                                    "
                                >
                                    Angin
                                    ${day.wind_max}
                                    km/j
                                </p>
                                `

                                :

                                ''
                        }


                        ${
                            day.sunrise
                                &&
                                day.sunset

                                ?

                                `
                                <p
                                    class="
                                        mt-1
                                        text-[8px]
                                        text-brand-dark/30
                                    "
                                >
                                    ☀
                                    ${day.sunrise}

                                    ·

                                    ${day.sunset}
                                </p>
                                `

                                :

                                ''
                        }
                        `;


                    container
                        .appendChild(
                            element
                        );

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | WEATHER ICON
        |--------------------------------------------------------------------------
        |
        | Menggunakan emoji agar tidak membutuhkan internet.
        |--------------------------------------------------------------------------
        */

        function weatherIcon(
            icon
        ) {

            switch (
                icon
            ) {

                case 'sun':

                    return '☀️';


                case 'cloud-sun':

                    return '🌤️';


                case 'fog':

                    return '🌫️';


                case 'drizzle':

                    return '🌦️';


                case 'rain':

                    return '🌧️';


                case 'storm':

                    return '⛈️';


                case 'cloud':

                default:

                    return '☁️';

            }

        }



        /*
        |--------------------------------------------------------------------------
        | WEATHER UNAVAILABLE
        |--------------------------------------------------------------------------
        */

        function renderWeatherUnavailable() {

            const current =
                document.getElementById(
                    'weatherCurrent'
                );


            const forecast =
                document.getElementById(
                    'weatherForecast'
                );


            current.innerHTML =
                `
                <div
                    class="
                        rounded-xl
                        bg-brand-dark/[0.04]
                        px-5
                        py-6
                        text-center
                    "
                >

                    <div
                        class="text-3xl"
                    >
                        ☁️
                    </div>


                    <p
                        class="
                            mt-3
                            text-sm
                            font-bold
                        "
                    >
                        Cuaca belum tersedia
                    </p>


                    <p
                        class="
                            mx-auto
                            mt-1
                            max-w-sm
                            text-[10px]
                            leading-relaxed
                            text-brand-dark/45
                        "
                    >
                        BaliHiking belum berhasil mendapatkan
                        koordinat atau prakiraan cuaca untuk gunung ini.
                        Pastikan nama/lokasi gunung sudah benar.
                    </p>

                </div>
                `;


            forecast.innerHTML =
                '';


            document
                .getElementById(
                    'weatherUpdated'
                )
                .textContent =
                    navigator.onLine
                        ?
                        'Data belum tersedia'
                        :
                        'Offline';


            updateWeatherNetworkUI();

        }



        /*
        |--------------------------------------------------------------------------
        | WEATHER ESCAPE
        |--------------------------------------------------------------------------
        */

        function escapeWeatherHtml(
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
            title,
            message,
            type =
                'success'
        ) {

            const toast =
                document.getElementById(
                    'mountainToast'
                );


            const titleElement =
                document.getElementById(
                    'mountainToastTitle'
                );


            const messageElement =
                document.getElementById(
                    'mountainToastMessage'
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
                type ===
                    'warning'
            ) {

                toast
                    .classList
                    .add(
                        'warning'
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
                    4000
                );

        }

    </script>


</body>

</html>