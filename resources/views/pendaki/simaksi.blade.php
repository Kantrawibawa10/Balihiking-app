<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, viewport-fit=cover"
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
        name="apple-mobile-web-app-title"
        content="BaliHiking"
    >

    <title>
        SIMAKSI - BaliHiking
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
    {{-- TAILWIND LOCAL --}}
    {{-- ========================================================= --}}

    <script src="{{ asset('vendor/tailwindcss.js') }}"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#1a382b',
                            orange: '#f06535',
                            cream: '#fbfbfa',
                        },
                    },

                    fontFamily: {
                        sans: [
                            '-apple-system',
                            'BlinkMacSystemFont',
                            '"Segoe UI"',
                            'Roboto',
                            'Arial',
                            'sans-serif',
                        ],
                    },
                },
            },
        };
    </script>


    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
            background: #fbfbfa;
        }

        body {
            color: #1a382b;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;
        }

        /*
        |--------------------------------------------------------------------------
        | APP WIDTH
        |--------------------------------------------------------------------------
        */

        .app-width {
            width: 100%;
            max-width: 430px;

            margin-left: auto;
            margin-right: auto;
        }

        /*
        |--------------------------------------------------------------------------
        | MODAL
        |--------------------------------------------------------------------------
        */

        body.modal-open {
            overflow: hidden;
        }

        /*
        | Bottom nav wajib hilang saat form dibuka.
        */
        body.modal-open #portal-bottom-navigation {
            display: none !important;
        }

        #simaksiModal {
            display: none;
        }

        #simaksiModal.show {
            display: flex;
        }

        .modal-sheet {
            max-height:
                calc(
                    100dvh -
                    env(safe-area-inset-top) -
                    8px
                );
        }

        /*
        |--------------------------------------------------------------------------
        | SCROLLBAR
        |--------------------------------------------------------------------------
        */

        .hide-scrollbar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /*
        |--------------------------------------------------------------------------
        | AJAX REPORT
        |--------------------------------------------------------------------------
        */

        #simaksiReportPanel {
            transition:
                opacity .15s ease;
        }

        #simaksiReportPanel.is-loading {
            opacity: .45;
            pointer-events: none;
        }

        /*
        |--------------------------------------------------------------------------
        | NETWORK
        |--------------------------------------------------------------------------
        */

        #networkStatus {
            position: fixed;

            left: 50%;

            bottom:
                calc(
                    88px +
                    env(safe-area-inset-bottom)
                );

            z-index: 99999;

            display: none;

            transform:
                translateX(-50%);

            padding: 9px 14px;

            border-radius: 999px;

            background: #f06535;

            color: white;

            font-size: 10px;
            font-weight: 700;

            white-space: nowrap;

            box-shadow:
                0 8px 24px
                rgba(0, 0, 0, .15);
        }

        #networkStatus.show {
            display: block;
        }

        /*
        |--------------------------------------------------------------------------
        | TOAST
        |--------------------------------------------------------------------------
        */

        #toast {
            position: fixed;

            top:
                calc(
                    76px +
                    env(safe-area-inset-top)
                );

            left: 50%;

            z-index: 100000;

            display: none;

            width:
                calc(
                    100% -
                    32px
                );

            max-width: 390px;

            transform:
                translateX(-50%);

            padding:
                12px 14px;

            border-radius: 14px;

            background: #1a382b;

            color: white;

            font-size: 10px;
            font-weight: 700;

            line-height: 1.5;

            box-shadow:
                0 12px 30px
                rgba(0, 0, 0, .16);
        }

        #toast.show {
            display: block;
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


    {{-- ========================================================= --}}
    {{-- NETWORK --}}
    {{-- ========================================================= --}}

    <div id="networkStatus">
        Offline · data akan disinkronkan
    </div>


    {{-- ========================================================= --}}
    {{-- TOAST --}}
    {{-- ========================================================= --}}

    <div id="toast"></div>


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
                app-width

                flex
                h-16

                items-center
                justify-between

                px-4
            "
        >

            <a
                href="{{ route('pendaki.dashboard') }}"
                class="
                    -ml-2

                    flex
                    h-10
                    w-10

                    items-center
                    justify-center

                    rounded-xl

                    text-brand-dark

                    active:bg-brand-dark/5
                "
            >
                <svg
                    class="h-6 w-6"
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
                class="
                    flex
                    items-center
                    gap-2
                "
            >
                <img
                    src="{{ asset('icons/icon-192.png') }}"
                    alt="BaliHiking"
                    class="
                        h-8
                        w-8
                        rounded-lg
                        object-cover
                    "
                >

                <div>
                    <h1
                        class="
                            text-sm
                            font-black
                        "
                    >
                        SIMAKSI
                    </h1>

                    <p
                        class="
                            text-[8px]
                            font-semibold
                            text-brand-dark/40
                        "
                    >
                        BaliHiking
                    </p>
                </div>
            </div>


            <div class="w-10"></div>

        </div>
    </header>


    {{-- ========================================================= --}}
    {{-- MAIN --}}
    {{-- ========================================================= --}}

    <main
        class="
            app-width

            space-y-5

            px-4
            py-5
        "
    >


        {{-- ===================================================== --}}
        {{-- HERO --}}
        {{-- ===================================================== --}}

        <section
            class="
                rounded-3xl

                bg-brand-dark

                p-5

                text-white

                shadow-sm
            "
        >
            <div
                class="
                    flex
                    items-start
                    justify-between
                    gap-4
                "
            >

                <div class="min-w-0">

                    <p
                        class="
                            text-[9px]
                            font-black
                            uppercase
                            tracking-[0.15em]
                            text-white/45
                        "
                    >
                        Izin Pendakian
                    </p>


                    <h2
                        class="
                            mt-1.5

                            text-xl
                            font-black
                        "
                    >
                        Registrasi SIMAKSI
                    </h2>


                    <p
                        class="
                            mt-1.5

                            text-[10px]
                            leading-5
                            text-white/60
                        "
                    >
                        Ajukan izin pendakian dan pantau
                        statusnya langsung dari aplikasi.
                    </p>

                </div>


                <div
                    class="
                        flex
                        h-11
                        w-11

                        shrink-0

                        items-center
                        justify-center

                        rounded-2xl

                        bg-white/10
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
                            d="M9 12h6m-6 4h4M6 3h9l3 3v15H6V3Z"
                        />
                    </svg>
                </div>

            </div>


            <button
                type="button"
                id="openSimaksiModal"
                class="
                    mt-5

                    flex
                    h-11
                    w-full

                    items-center
                    justify-center
                    gap-2

                    rounded-xl

                    bg-brand-orange

                    text-[10px]
                    font-black
                    text-white

                    active:scale-[0.98]
                "
            >
                <svg
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2.3"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 5v14M5 12h14"
                    />
                </svg>

                Ajukan SIMAKSI
            </button>

        </section>


        {{-- ===================================================== --}}
        {{-- REPORT COVER CARD --}}
        {{-- ===================================================== --}}

        <section
            id="simaksiReportPanel"
            class="
                overflow-hidden

                rounded-3xl

                border
                border-brand-dark/10

                bg-white

                shadow-sm
            "
        >

            <div
                class="
                    space-y-4

                    p-4
                "
            >

                {{-- ================================================= --}}
                {{-- REPORT HEADER --}}
                {{-- ================================================= --}}

                <div
                    class="
                        flex
                        items-start
                        justify-between
                        gap-3
                    "
                >
                    <div>

                        <p
                            class="
                                text-[9px]
                                font-black
                                uppercase
                                tracking-[0.15em]
                                text-brand-orange
                            "
                        >
                            Riwayat Permohonan
                        </p>


                        <h2
                            class="
                                mt-1

                                text-lg
                                font-black
                            "
                        >
                            SIMAKSI Saya
                        </h2>


                        <p
                            class="
                                mt-1

                                text-[10px]
                                text-brand-dark/40
                            "
                        >
                            Pantau status izin pendakian Anda.
                        </p>

                    </div>


                    <div
                        id="offlineQueueBadge"
                        class="
                            hidden

                            shrink-0

                            rounded-full

                            bg-orange-100

                            px-2.5
                            py-1.5

                            text-[8px]
                            font-black
                            text-orange-700
                        "
                    ></div>

                </div>


                {{-- ================================================= --}}
                {{-- SUMMARY --}}
                {{-- ================================================= --}}

                <div
                    class="
                        grid
                        grid-cols-4
                        gap-2
                    "
                >

                    <div
                        class="
                            rounded-xl

                            bg-brand-cream

                            px-1
                            py-3

                            text-center
                        "
                    >
                        <p
                            class="
                                text-[7px]
                                text-brand-dark/35
                            "
                        >
                            Total
                        </p>

                        <p
                            class="
                                mt-1
                                text-lg
                                font-black
                            "
                        >
                            {{ $totalSimaksi }}
                        </p>
                    </div>


                    <div
                        class="
                            rounded-xl

                            bg-amber-50

                            px-1
                            py-3

                            text-center
                        "
                    >
                        <p
                            class="
                                text-[7px]
                                text-amber-700/60
                            "
                        >
                            Pending
                        </p>

                        <p
                            class="
                                mt-1
                                text-lg
                                font-black
                                text-amber-700
                            "
                        >
                            {{ $totalPending }}
                        </p>
                    </div>


                    <div
                        class="
                            rounded-xl

                            bg-emerald-50

                            px-1
                            py-3

                            text-center
                        "
                    >
                        <p
                            class="
                                text-[7px]
                                text-emerald-700/60
                            "
                        >
                            ACC
                        </p>

                        <p
                            class="
                                mt-1
                                text-lg
                                font-black
                                text-emerald-700
                            "
                        >
                            {{ $totalApproved }}
                        </p>
                    </div>


                    <div
                        class="
                            rounded-xl

                            bg-red-50

                            px-1
                            py-3

                            text-center
                        "
                    >
                        <p
                            class="
                                text-[7px]
                                text-red-700/60
                            "
                        >
                            Ditolak
                        </p>

                        <p
                            class="
                                mt-1
                                text-lg
                                font-black
                                text-red-700
                            "
                        >
                            {{ $totalRejected }}
                        </p>
                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- FILTER --}}
                {{-- ================================================= --}}

                <div
                    class="
                        hide-scrollbar

                        flex
                        gap-2

                        overflow-x-auto
                    "
                >
                    @foreach([
                        'all' => 'Semua',
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ] as $filterValue => $filterLabel)

                        <a
                            href="{{
                                route(
                                    'pendaki.simaksi',
                                    [
                                        'status' =>
                                            $filterValue,
                                    ]
                                )
                            }}"
                            data-simaksi-filter
                            class="
                                shrink-0

                                rounded-xl

                                px-3.5
                                py-2.5

                                text-[9px]
                                font-black

                                transition
                                active:scale-[0.97]

                                {{
                                    $status === $filterValue
                                        ? 'bg-brand-dark text-white'
                                        : 'bg-brand-cream text-brand-dark/45'
                                }}
                            "
                        >
                            {{ $filterLabel }}
                        </a>

                    @endforeach
                </div>


                {{-- ================================================= --}}
                {{-- OFFLINE QUEUE --}}
                {{-- ================================================= --}}

                <div
                    id="offlineSimaksiContainer"
                    class="
                        hidden

                        overflow-hidden

                        rounded-2xl

                        border
                        border-orange-200

                        bg-orange-50
                    "
                >
                    <div
                        class="
                            border-b
                            border-orange-200

                            px-4
                            py-3
                        "
                    >
                        <p
                            class="
                                text-[10px]
                                font-black
                                text-orange-700
                            "
                        >
                            Menunggu Sinkronisasi
                        </p>

                        <p
                            class="
                                mt-0.5

                                text-[8px]
                                text-orange-700/60
                            "
                        >
                            Data tersimpan sementara di perangkat.
                        </p>
                    </div>


                    <div
                        id="offlineSimaksiList"
                        class="
                            divide-y
                            divide-orange-200
                        "
                    ></div>
                </div>


                {{-- ================================================= --}}
                {{-- HISTORY CARDS --}}
                {{-- ================================================= --}}

                <div
                    class="
                        space-y-3
                    "
                >
                    @forelse(
                        $simaksis
                        as $simaksi
                    )

                        @php
                            $normalizedStatus =
                                strtolower(
                                    trim(
                                        (string)
                                        $simaksi->status
                                    )
                                );

                            $isApproved =
                                in_array(
                                    $normalizedStatus,
                                    [
                                        'approved',
                                        'disetujui',
                                        'accepted',
                                    ],
                                    true
                                );

                            $isRejected =
                                in_array(
                                    $normalizedStatus,
                                    [
                                        'rejected',
                                        'ditolak',
                                        'declined',
                                    ],
                                    true
                                );
                        @endphp


                        <article
                            class="
                                rounded-2xl

                                border
                                border-brand-dark/[0.07]

                                bg-brand-cream/50

                                p-4
                            "
                        >

                            {{-- HEADER --}}

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
                                            text-[8px]
                                            font-black
                                            uppercase
                                            tracking-wide
                                            text-brand-orange
                                        "
                                    >
                                        SIMAKSI
                                        #{{ $simaksi->id }}
                                    </p>


                                    <h3
                                        class="
                                            mt-1

                                            truncate

                                            text-sm
                                            font-black
                                        "
                                    >
                                        {{
                                            $simaksi->mountain?->name
                                            ??
                                            $simaksi->gunung
                                        }}
                                    </h3>


                                    <p
                                        class="
                                            mt-0.5

                                            text-[8px]
                                            text-brand-dark/35
                                        "
                                    >
                                        Diajukan
                                        {{
                                            $simaksi
                                                ->created_at
                                                ->format(
                                                    'd/m/Y H:i'
                                                )
                                        }}
                                    </p>

                                </div>


                                @if($isApproved)

                                    <span
                                        class="
                                            shrink-0

                                            rounded-full

                                            bg-emerald-100

                                            px-2.5
                                            py-1

                                            text-[8px]
                                            font-black
                                            text-emerald-700
                                        "
                                    >
                                        Disetujui
                                    </span>

                                @elseif($isRejected)

                                    <span
                                        class="
                                            shrink-0

                                            rounded-full

                                            bg-red-100

                                            px-2.5
                                            py-1

                                            text-[8px]
                                            font-black
                                            text-red-700
                                        "
                                    >
                                        Ditolak
                                    </span>

                                @else

                                    <span
                                        class="
                                            shrink-0

                                            rounded-full

                                            bg-amber-100

                                            px-2.5
                                            py-1

                                            text-[8px]
                                            font-black
                                            text-amber-700
                                        "
                                    >
                                        Pending
                                    </span>

                                @endif

                            </div>


                            {{-- DATES --}}

                            <div
                                class="
                                    mt-4

                                    grid
                                    grid-cols-[1fr_auto_1fr]
                                    items-center
                                    gap-2
                                "
                            >

                                <div
                                    class="
                                        rounded-xl
                                        bg-white
                                        p-3
                                    "
                                >
                                    <p
                                        class="
                                            text-[8px]
                                            text-brand-dark/35
                                        "
                                    >
                                        Naik
                                    </p>

                                    <p
                                        class="
                                            mt-1

                                            text-[10px]
                                            font-black
                                        "
                                    >
                                        {{
                                            $simaksi
                                                ->tanggal_naik
                                                ->format(
                                                    'd/m/Y'
                                                )
                                        }}
                                    </p>
                                </div>


                                <div
                                    class="
                                        flex
                                        h-7
                                        w-7

                                        items-center
                                        justify-center

                                        rounded-full

                                        bg-white

                                        text-brand-dark/30
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
                                            d="M5 12h14m-4-4 4 4-4 4"
                                        />
                                    </svg>
                                </div>


                                <div
                                    class="
                                        rounded-xl
                                        bg-white
                                        p-3
                                    "
                                >
                                    <p
                                        class="
                                            text-[8px]
                                            text-brand-dark/35
                                        "
                                    >
                                        Turun
                                    </p>

                                    <p
                                        class="
                                            mt-1

                                            text-[10px]
                                            font-black
                                        "
                                    >
                                        {{
                                            $simaksi
                                                ->tanggal_turun
                                                ->format(
                                                    'd/m/Y'
                                                )
                                        }}
                                    </p>
                                </div>

                            </div>


                            {{-- DETAIL --}}

                            <div
                                class="
                                    mt-3

                                    flex
                                    items-center
                                    justify-between
                                    gap-3

                                    border-t
                                    border-brand-dark/[0.06]

                                    pt-3

                                    text-[9px]
                                    text-brand-dark/45
                                "
                            >
                                <span>
                                    <strong
                                        class="
                                            text-brand-dark
                                        "
                                    >
                                        {{
                                            $simaksi
                                                ->jumlah_anggota
                                        }}
                                    </strong>

                                    anggota
                                </span>


                                <span
                                    class="
                                        truncate
                                        text-right
                                    "
                                >
                                    {{
                                        $simaksi
                                            ->nomor_darurat
                                    }}
                                </span>
                            </div>


                            {{-- ADMIN NOTE --}}

                            @if(
                                $simaksi->catatan_admin
                            )

                                <div
                                    class="
                                        mt-3

                                        rounded-xl

                                        bg-white

                                        p-3
                                    "
                                >
                                    <p
                                        class="
                                            text-[8px]
                                            font-black
                                            uppercase
                                            tracking-wide
                                            text-brand-dark/35
                                        "
                                    >
                                        Catatan Admin
                                    </p>


                                    <p
                                        class="
                                            mt-1

                                            text-[9px]
                                            leading-5
                                            text-brand-dark/60
                                        "
                                    >
                                        {{
                                            $simaksi
                                                ->catatan_admin
                                        }}
                                    </p>
                                </div>

                            @endif

                        </article>


                    @empty

                        <div
                            class="
                                rounded-2xl

                                bg-brand-cream

                                px-5
                                py-10

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

                                    bg-white

                                    text-brand-dark/30
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
                                        d="M9 12h6m-6 4h4M6 3h9l3 3v15H6V3Z"
                                    />
                                </svg>
                            </div>


                            <p
                                class="
                                    mt-3

                                    text-xs
                                    font-black
                                "
                            >
                                Belum ada SIMAKSI
                            </p>


                            <p
                                class="
                                    mt-1

                                    text-[9px]
                                    leading-5
                                    text-brand-dark/40
                                "
                            >
                                Tekan Ajukan SIMAKSI untuk
                                membuat permohonan baru.
                            </p>
                        </div>

                    @endforelse

                </div>


                {{-- ================================================= --}}
                {{-- PAGINATION --}}
                {{-- ================================================= --}}

                @if(
                    $simaksis->hasPages()
                )

                    <div
                        class="
                            flex
                            items-center
                            justify-between

                            border-t
                            border-brand-dark/[0.06]

                            pt-3
                        "
                    >

                        @if(
                            $simaksis->onFirstPage()
                        )

                            <span
                                class="
                                    flex
                                    h-9
                                    w-9

                                    items-center
                                    justify-center

                                    rounded-xl

                                    bg-brand-cream

                                    text-[10px]
                                    text-brand-dark/20
                                "
                            >
                                ←
                            </span>

                        @else

                            <a
                                href="{{
                                    $simaksis
                                        ->previousPageUrl()
                                }}"
                                data-simaksi-page
                                class="
                                    flex
                                    h-9
                                    w-9

                                    items-center
                                    justify-center

                                    rounded-xl

                                    bg-brand-cream

                                    text-[10px]
                                    font-black
                                "
                            >
                                ←
                            </a>

                        @endif


                        <span
                            class="
                                text-[8px]
                                font-bold
                                text-brand-dark/40
                            "
                        >
                            {{
                                $simaksis
                                    ->currentPage()
                            }}

                            /

                            {{
                                $simaksis
                                    ->lastPage()
                            }}
                        </span>


                        @if(
                            $simaksis->hasMorePages()
                        )

                            <a
                                href="{{
                                    $simaksis
                                        ->nextPageUrl()
                                }}"
                                data-simaksi-page
                                class="
                                    flex
                                    h-9
                                    w-9

                                    items-center
                                    justify-center

                                    rounded-xl

                                    bg-brand-dark

                                    text-[10px]
                                    font-black
                                    text-white
                                "
                            >
                                →
                            </a>

                        @else

                            <span
                                class="
                                    flex
                                    h-9
                                    w-9

                                    items-center
                                    justify-center

                                    rounded-xl

                                    bg-brand-cream

                                    text-[10px]
                                    text-brand-dark/20
                                "
                            >
                                →
                            </span>

                        @endif

                    </div>

                @endif

            </div>

        </section>

    </main>


    {{-- ========================================================= --}}
    {{-- BOTTOM NAV --}}
    {{-- ========================================================= --}}

    @include(
        'pendaki.components.bottom-nav',
        [
            'active' => 'simaksi',
        ]
    )


    {{-- ========================================================= --}}
    {{-- MODAL --}}
    {{-- ========================================================= --}}

    <div
        id="simaksiModal"
        class="
            fixed
            inset-0
            z-[99999]

            items-end
            justify-center

            bg-black/40

            sm:items-center
            sm:p-4
        "
        aria-hidden="true"
    >

        {{-- BACKDROP --}}

        <button
            type="button"
            id="simaksiModalBackdrop"
            class="
                absolute
                inset-0

                h-full
                w-full
            "
            tabindex="-1"
            aria-label="Tutup modal"
        ></button>


        {{-- MODAL SHEET --}}

        <div
            class="
                modal-sheet

                relative
                z-10

                flex
                w-full
                max-w-md
                flex-col

                overflow-hidden

                rounded-t-3xl

                bg-white

                shadow-2xl

                sm:rounded-3xl
            "
        >

            {{-- DRAG HANDLE --}}

            <div
                class="
                    shrink-0

                    pb-1
                    pt-3

                    sm:hidden
                "
            >
                <div
                    class="
                        mx-auto
                        h-1
                        w-10
                        rounded-full
                        bg-brand-dark/10
                    "
                ></div>
            </div>


            {{-- HEADER MODAL --}}

            <div
                class="
                    shrink-0

                    border-b
                    border-brand-dark/[0.06]

                    px-5
                    pb-4
                    pt-3
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

                    <div>

                        <p
                            class="
                                text-[8px]
                                font-black
                                uppercase
                                tracking-[0.15em]
                                text-brand-orange
                            "
                        >
                            Izin Pendakian
                        </p>


                        <h2
                            class="
                                mt-1

                                text-lg
                                font-black
                            "
                        >
                            Ajukan SIMAKSI
                        </h2>


                        <p
                            class="
                                mt-1

                                text-[9px]
                                text-brand-dark/40
                            "
                        >
                            Isi informasi perjalanan dengan benar.
                        </p>

                    </div>


                    <button
                        type="button"
                        id="closeSimaksiModal"
                        class="
                            flex
                            h-9
                            w-9

                            shrink-0

                            items-center
                            justify-center

                            rounded-xl

                            bg-brand-dark/5

                            text-brand-dark/50

                            active:bg-brand-dark/10
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
                                d="M6 18 18 6M6 6l12 12"
                            />
                        </svg>
                    </button>

                </div>


                <div
                    id="formError"
                    class="
                        mt-3
                        hidden

                        rounded-xl

                        bg-red-50

                        px-3
                        py-2

                        text-[9px]
                        font-semibold
                        leading-5
                        text-red-700
                    "
                ></div>

            </div>


            {{-- ================================================= --}}
            {{-- FORM --}}
            {{-- ================================================= --}}

            <form
                id="simaksiForm"
                action="{{ route('pendaki.simaksi.store') }}"
                method="POST"
                class="
                    flex
                    min-h-0
                    flex-1
                    flex-col
                "
            >
                @csrf


                {{-- ============================================= --}}
                {{-- SCROLLABLE BODY --}}
                {{-- ============================================= --}}

                <div
                    class="
                        min-h-0
                        flex-1

                        space-y-4

                        overflow-y-auto
                        overscroll-contain

                        px-5
                        py-4
                    "
                >

                    {{-- GUNUNG --}}

                    <div>
                        <label
                            for="gunung"
                            class="
                                mb-1.5
                                block
                                text-[9px]
                                font-black
                            "
                        >
                            Pilih Gunung
                        </label>


                        <select
                            id="gunung"
                            name="gunung"
                            required
                            class="
                                h-11
                                w-full

                                rounded-xl

                                border
                                border-brand-dark/15

                                bg-brand-cream

                                px-3

                                text-[10px]
                                font-bold

                                outline-none

                                focus:border-brand-orange
                            "
                        >
                            <option value="">
                                Pilih gunung
                            </option>


                            @foreach(
                                $mountains
                                as $mountain
                            )

                                <option
                                    value="{{ $mountain->name }}"
                                >
                                    {{ $mountain->name }}

                                    @if(
                                        $mountain->elevation_m
                                    )

                                        ({{
                                            number_format(
                                                $mountain->elevation_m,
                                                0,
                                                ',',
                                                '.'
                                            )
                                        }} mdpl)

                                    @endif
                                </option>

                            @endforeach
                        </select>
                    </div>


                    {{-- DATES --}}

                    <div
                        class="
                            grid
                            grid-cols-2
                            gap-3
                        "
                    >

                        <div>

                            <label
                                for="tanggal_naik"
                                class="
                                    mb-1.5
                                    block
                                    text-[9px]
                                    font-black
                                "
                            >
                                Tanggal Naik
                            </label>


                            <input
                                type="date"
                                id="tanggal_naik"
                                name="tanggal_naik"
                                required
                                class="
                                    h-11
                                    w-full

                                    rounded-xl

                                    border
                                    border-brand-dark/15

                                    bg-brand-cream

                                    px-3

                                    text-[9px]
                                    font-semibold

                                    outline-none

                                    focus:border-brand-orange
                                "
                            >

                        </div>


                        <div>

                            <label
                                for="tanggal_turun"
                                class="
                                    mb-1.5
                                    block
                                    text-[9px]
                                    font-black
                                "
                            >
                                Tanggal Turun
                            </label>


                            <input
                                type="date"
                                id="tanggal_turun"
                                name="tanggal_turun"
                                required
                                class="
                                    h-11
                                    w-full

                                    rounded-xl

                                    border
                                    border-brand-dark/15

                                    bg-brand-cream

                                    px-3

                                    text-[9px]
                                    font-semibold

                                    outline-none

                                    focus:border-brand-orange
                                "
                            >

                        </div>

                    </div>


                    {{-- ANGGOTA --}}

                    <div>

                        <label
                            for="jumlah_anggota"
                            class="
                                mb-1.5
                                block
                                text-[9px]
                                font-black
                            "
                        >
                            Jumlah Anggota
                        </label>


                        <input
                            type="number"
                            id="jumlah_anggota"
                            name="jumlah_anggota"
                            value="1"
                            min="1"
                            max="100"
                            required
                            class="
                                h-11
                                w-full

                                rounded-xl

                                border
                                border-brand-dark/15

                                bg-brand-cream

                                px-3

                                text-[10px]
                                font-bold

                                outline-none

                                focus:border-brand-orange
                            "
                        >

                    </div>


                    {{-- DARURAT --}}

                    <div>

                        <label
                            for="nomor_darurat"
                            class="
                                mb-1.5
                                block
                                text-[9px]
                                font-black
                            "
                        >
                            Nomor Kontak Darurat
                        </label>


                        <input
                            type="tel"
                            id="nomor_darurat"
                            name="nomor_darurat"
                            placeholder="08xxxxxxxxxx"
                            required
                            class="
                                h-11
                                w-full

                                rounded-xl

                                border
                                border-brand-dark/15

                                bg-brand-cream

                                px-3

                                text-[10px]
                                font-semibold

                                outline-none

                                placeholder:text-brand-dark/25

                                focus:border-brand-orange
                            "
                        >

                    </div>


                    {{-- OFFLINE INFO --}}

                    <div
                        class="
                            rounded-xl
                            bg-brand-dark/[0.04]
                            p-3
                        "
                    >
                        <p
                            class="
                                text-[8px]
                                leading-5
                                text-brand-dark/40
                            "
                        >
                            Jika koneksi terputus, permohonan akan
                            disimpan sementara di perangkat dan dikirim
                            otomatis setelah koneksi kembali.
                        </p>
                    </div>

                </div>


                {{-- ============================================= --}}
                {{-- FIXED / STICKY FOOTER --}}
                {{-- ============================================= --}}

                <div
                    class="
                        shrink-0

                        border-t
                        border-brand-dark/[0.06]

                        bg-white

                        px-5
                        pt-3

                        pb-[calc(12px+env(safe-area-inset-bottom))]
                    "
                >
                    <button
                        type="submit"
                        id="submitButton"
                        class="
                            flex
                            h-11
                            w-full

                            items-center
                            justify-center

                            rounded-xl

                            bg-brand-orange

                            text-[10px]
                            font-black
                            text-white

                            shadow-lg
                            shadow-brand-orange/20

                            active:scale-[0.98]

                            disabled:opacity-50
                        "
                    >
                        <span id="submitText">
                            Kirim Permohonan SIMAKSI
                        </span>
                    </button>
                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>
        /*
        |--------------------------------------------------------------------------
        | CONFIG
        |--------------------------------------------------------------------------
        */

        const simaksiStoreEndpoint =
            @json(
                route(
                    'pendaki.simaksi.store'
                )
            );

        const csrfToken =
            document
                .querySelector(
                    'meta[name="csrf-token"]'
                )
                .content;

        const DB_NAME =
            'balihiking-simaksi';

        const DB_VERSION =
            1;

        const DB_STORE =
            'simaksi_queue';


        let dbInstance =
            null;

        let syncRunning =
            false;

        let reportAbortController =
            null;

        let toastTimer =
            null;


        /*
        |--------------------------------------------------------------------------
        | ELEMENTS
        |--------------------------------------------------------------------------
        */

        const modal =
            document.getElementById(
                'simaksiModal'
            );

        const openModalButton =
            document.getElementById(
                'openSimaksiModal'
            );

        const closeModalButton =
            document.getElementById(
                'closeSimaksiModal'
            );

        const modalBackdrop =
            document.getElementById(
                'simaksiModalBackdrop'
            );

        const form =
            document.getElementById(
                'simaksiForm'
            );

        const formError =
            document.getElementById(
                'formError'
            );

        const submitButton =
            document.getElementById(
                'submitButton'
            );

        const submitText =
            document.getElementById(
                'submitText'
            );

        const networkStatus =
            document.getElementById(
                'networkStatus'
            );

        const toast =
            document.getElementById(
                'toast'
            );


        /*
        |--------------------------------------------------------------------------
        | MODAL
        |--------------------------------------------------------------------------
        */

        function openModal()
        {
            hideFormError();

            setupDates();

            modal
                .classList
                .add(
                    'show'
                );

            modal.setAttribute(
                'aria-hidden',
                'false'
            );

            document
                .body
                .classList
                .add(
                    'modal-open'
                );
        }


        function closeModal()
        {
            modal
                .classList
                .remove(
                    'show'
                );

            modal.setAttribute(
                'aria-hidden',
                'true'
            );

            document
                .body
                .classList
                .remove(
                    'modal-open'
                );

            hideFormError();
        }


        openModalButton
            .addEventListener(
                'click',
                openModal
            );


        closeModalButton
            .addEventListener(
                'click',
                closeModal
            );


        modalBackdrop
            .addEventListener(
                'click',
                closeModal
            );


        document
            .addEventListener(
                'keydown',
                function (
                    event
                ) {
                    if (
                        event.key ===
                        'Escape'
                    ) {
                        closeModal();
                    }
                }
            );


        /*
        |--------------------------------------------------------------------------
        | TOAST
        |--------------------------------------------------------------------------
        */

        function showToast(
            message
        ) {
            if (
                toastTimer
            ) {
                clearTimeout(
                    toastTimer
                );
            }


            toast.textContent =
                message;


            toast
                .classList
                .add(
                    'show'
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
                    2600
                );
        }


        /*
        |--------------------------------------------------------------------------
        | FORM ERROR
        |--------------------------------------------------------------------------
        */

        function showFormError(
            message
        ) {
            formError.textContent =
                message;


            formError
                .classList
                .remove(
                    'hidden'
                );
        }


        function hideFormError()
        {
            formError.textContent =
                '';


            formError
                .classList
                .add(
                    'hidden'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | INDEXED DB
        |--------------------------------------------------------------------------
        */

        function openDb()
        {
            if (
                dbInstance
            ) {
                return Promise.resolve(
                    dbInstance
                );
            }


            return new Promise(
                function (
                    resolve,
                    reject
                ) {
                    const request =
                        indexedDB.open(
                            DB_NAME,
                            DB_VERSION
                        );


                    request.onupgradeneeded =
                        function (
                            event
                        ) {
                            const db =
                                event.target.result;


                            if (
                                !db
                                    .objectStoreNames
                                    .contains(
                                        DB_STORE
                                    )
                            ) {
                                db.createObjectStore(
                                    DB_STORE,
                                    {
                                        keyPath:
                                            'id',

                                        autoIncrement:
                                            true,
                                    }
                                );
                            }
                        };


                    request.onsuccess =
                        function (
                            event
                        ) {
                            dbInstance =
                                event.target.result;


                            resolve(
                                dbInstance
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


        async function getQueue()
        {
            const db =
                await openDb();


            return new Promise(
                function (
                    resolve,
                    reject
                ) {
                    const transaction =
                        db.transaction(
                            DB_STORE,
                            'readonly'
                        );


                    const store =
                        transaction
                            .objectStore(
                                DB_STORE
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
        }


        async function addQueue(
            payload
        ) {
            const db =
                await openDb();


            return new Promise(
                function (
                    resolve,
                    reject
                ) {
                    const transaction =
                        db.transaction(
                            DB_STORE,
                            'readwrite'
                        );


                    const store =
                        transaction
                            .objectStore(
                                DB_STORE
                            );


                    const request =
                        store.add({
                            payload:
                                payload,

                            created_at:
                                Date.now(),
                        });


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


        async function deleteQueue(
            id
        ) {
            const db =
                await openDb();


            return new Promise(
                function (
                    resolve,
                    reject
                ) {
                    const transaction =
                        db.transaction(
                            DB_STORE,
                            'readwrite'
                        );


                    const store =
                        transaction
                            .objectStore(
                                DB_STORE
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


        /*
        |--------------------------------------------------------------------------
        | AJAX REPORT
        |--------------------------------------------------------------------------
        */

        async function loadReport(
            url,
            pushState = true
        ) {
            const panel =
                document.getElementById(
                    'simaksiReportPanel'
                );


            if (
                !panel
            ) {
                return;
            }


            if (
                reportAbortController
            ) {
                reportAbortController
                    .abort();
            }


            reportAbortController =
                new AbortController();


            panel
                .classList
                .add(
                    'is-loading'
                );


            try {
                const response =
                    await fetch(
                        url,
                        {
                            method:
                                'GET',

                            credentials:
                                'same-origin',

                            headers: {
                                'Accept':
                                    'text/html',

                                'X-Requested-With':
                                    'XMLHttpRequest',
                            },

                            signal:
                                reportAbortController
                                    .signal,
                        }
                    );


                if (
                    !response.ok
                ) {
                    throw new Error(
                        'Gagal memuat SIMAKSI.'
                    );
                }


                const html =
                    await response.text();


                const parsed =
                    new DOMParser()
                        .parseFromString(
                            html,
                            'text/html'
                        );


                const newPanel =
                    parsed
                        .getElementById(
                            'simaksiReportPanel'
                        );


                if (
                    !newPanel
                ) {
                    throw new Error(
                        'Panel SIMAKSI tidak ditemukan.'
                    );
                }


                panel.replaceWith(
                    newPanel
                );


                if (
                    pushState
                ) {
                    history.pushState(
                        {},
                        '',
                        url
                    );
                }


                await renderOfflineQueue();

            } catch (
                error
            ) {
                if (
                    error.name ===
                    'AbortError'
                ) {
                    return;
                }


                console.error(
                    error
                );


                showToast(
                    'Data SIMAKSI gagal dimuat.'
                );

            } finally {
                document
                    .getElementById(
                        'simaksiReportPanel'
                    )
                    ?.classList
                    .remove(
                        'is-loading'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER / PAGINATION
        |--------------------------------------------------------------------------
        */

        document
            .addEventListener(
                'click',
                function (
                    event
                ) {
                    const filter =
                        event
                            .target
                            .closest(
                                '[data-simaksi-filter]'
                            );


                    if (
                        filter
                    ) {
                        event.preventDefault();


                        loadReport(
                            filter.href,
                            true
                        );


                        return;
                    }


                    const pagination =
                        event
                            .target
                            .closest(
                                '[data-simaksi-page]'
                            );


                    if (
                        pagination
                    ) {
                        event.preventDefault();


                        loadReport(
                            pagination.href,
                            true
                        );
                    }
                }
            );


        window
            .addEventListener(
                'popstate',
                function () {
                    loadReport(
                        window.location.href,
                        false
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | FORM
        |--------------------------------------------------------------------------
        */

        form
            .addEventListener(
                'submit',
                async function (
                    event
                ) {
                    event.preventDefault();


                    hideFormError();


                    const payload = {
                        gunung:
                            form.gunung.value,

                        tanggal_naik:
                            form.tanggal_naik.value,

                        tanggal_turun:
                            form.tanggal_turun.value,

                        jumlah_anggota:
                            form.jumlah_anggota.value,

                        nomor_darurat:
                            form.nomor_darurat.value,
                    };


                    if (
                        !navigator.onLine
                    ) {
                        await addQueue(
                            payload
                        );


                        resetForm();


                        closeModal();


                        await renderOfflineQueue();


                        showToast(
                            'SIMAKSI disimpan offline dan akan dikirim otomatis.'
                        );


                        return;
                    }


                    await submitSimaksi(
                        payload
                    );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | ONLINE SUBMIT
        |--------------------------------------------------------------------------
        */

        async function submitSimaksi(
            payload
        ) {
            submitButton.disabled =
                true;


            submitText.textContent =
                'Mengirim...';


            try {
                const response =
                    await fetch(
                        simaksiStoreEndpoint,
                        {
                            method:
                                'POST',

                            credentials:
                                'same-origin',

                            headers: {
                                'Accept':
                                    'application/json',

                                'Content-Type':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken,

                                'X-Requested-With':
                                    'XMLHttpRequest',
                            },

                            body:
                                JSON.stringify(
                                    payload
                                ),
                        }
                    );


                if (
                    !response.ok
                ) {
                    let message =
                        'Permohonan gagal dikirim.';


                    const contentType =
                        response
                            .headers
                            .get(
                                'content-type'
                            )
                        ||
                        '';


                    if (
                        contentType.includes(
                            'application/json'
                        )
                    ) {
                        const data =
                            await response.json();


                        if (
                            data.errors
                        ) {
                            message =
                                Object
                                    .values(
                                        data.errors
                                    )
                                    .flat()
                                    .join(
                                        ' '
                                    );

                        } else if (
                            data.message
                        ) {
                            message =
                                data.message;
                        }
                    }


                    showFormError(
                        message
                    );


                    return;
                }


                resetForm();


                closeModal();


                await loadReport(
                    window.location.href,
                    false
                );


                showToast(
                    'Permohonan SIMAKSI berhasil dikirim.'
                );

            } catch (
                error
            ) {
                console.error(
                    error
                );


                await addQueue(
                    payload
                );


                resetForm();


                closeModal();


                await renderOfflineQueue();


                showToast(
                    'Koneksi terputus. SIMAKSI disimpan di perangkat.'
                );

            } finally {
                submitButton.disabled =
                    false;


                submitText.textContent =
                    'Kirim Permohonan SIMAKSI';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | RESET FORM
        |--------------------------------------------------------------------------
        */

        function resetForm()
        {
            form.reset();


            form
                .jumlah_anggota
                .value =
                    1;


            setupDates();
        }


        /*
        |--------------------------------------------------------------------------
        | OFFLINE QUEUE
        |--------------------------------------------------------------------------
        */

        async function renderOfflineQueue()
        {
            const queue =
                await getQueue();


            const container =
                document.getElementById(
                    'offlineSimaksiContainer'
                );


            const list =
                document.getElementById(
                    'offlineSimaksiList'
                );


            const badge =
                document.getElementById(
                    'offlineQueueBadge'
                );


            if (
                !container
                ||
                !list
                ||
                !badge
            ) {
                return;
            }


            if (
                queue.length ===
                0
            ) {
                container
                    .classList
                    .add(
                        'hidden'
                    );


                badge
                    .classList
                    .add(
                        'hidden'
                    );


                list.innerHTML =
                    '';


                return;
            }


            container
                .classList
                .remove(
                    'hidden'
                );


            badge
                .classList
                .remove(
                    'hidden'
                );


            badge.textContent =
                `${queue.length} offline`;


            list.innerHTML =
                '';


            queue.forEach(
                function (
                    item,
                    index
                ) {
                    const payload =
                        item.payload;


                    const wrapper =
                        document.createElement(
                            'div'
                        );


                    wrapper.className =
                        'p-4';


                    wrapper.innerHTML =
                        `
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
                                        text-[8px]
                                        font-black
                                        uppercase
                                        text-orange-700
                                    "
                                >
                                    Offline #${index + 1}
                                </p>

                                <p
                                    class="
                                        mt-1
                                        truncate
                                        text-xs
                                        font-black
                                    "
                                >
                                    ${escapeHtml(
                                        payload.gunung
                                    )}
                                </p>

                                <p
                                    class="
                                        mt-1
                                        text-[9px]
                                        text-brand-dark/45
                                    "
                                >
                                    ${formatDate(
                                        payload.tanggal_naik
                                    )}
                                    –
                                    ${formatDate(
                                        payload.tanggal_turun
                                    )}
                                    ·
                                    ${escapeHtml(
                                        payload.jumlah_anggota
                                    )}
                                    orang
                                </p>

                            </div>

                            <span
                                class="
                                    shrink-0

                                    rounded-full

                                    bg-orange-100

                                    px-2.5
                                    py-1

                                    text-[8px]
                                    font-black
                                    text-orange-700
                                "
                            >
                                Belum Sinkron
                            </span>
                        </div>
                        `;


                    list.appendChild(
                        wrapper
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | AUTO SYNC
        |--------------------------------------------------------------------------
        */

        async function syncQueue()
        {
            if (
                !navigator.onLine
                ||
                syncRunning
            ) {
                return;
            }


            syncRunning =
                true;


            let synced =
                0;


            try {
                const queue =
                    await getQueue();


                for (
                    const item
                    of queue
                ) {
                    try {
                        const response =
                            await fetch(
                                simaksiStoreEndpoint,
                                {
                                    method:
                                        'POST',

                                    credentials:
                                        'same-origin',

                                    headers: {
                                        'Accept':
                                            'application/json',

                                        'Content-Type':
                                            'application/json',

                                        'X-CSRF-TOKEN':
                                            csrfToken,

                                        'X-Requested-With':
                                            'XMLHttpRequest',
                                    },

                                    body:
                                        JSON.stringify(
                                            item.payload
                                        ),
                                }
                            );


                        if (
                            !response.ok
                        ) {
                            break;
                        }


                        await deleteQueue(
                            item.id
                        );


                        synced++;

                    } catch (
                        error
                    ) {
                        break;
                    }
                }


                await renderOfflineQueue();


                if (
                    synced >
                    0
                ) {
                    await loadReport(
                        window.location.href,
                        false
                    );


                    showToast(
                        `${synced} SIMAKSI berhasil disinkronkan.`
                    );
                }

            } finally {
                syncRunning =
                    false;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | NETWORK UI
        |--------------------------------------------------------------------------
        */

        function updateNetwork()
        {
            if (
                navigator.onLine
            ) {
                networkStatus
                    .classList
                    .remove(
                        'show'
                    );

            } else {
                networkStatus
                    .classList
                    .add(
                        'show'
                    );
            }
        }


        window
            .addEventListener(
                'online',
                async function () {
                    updateNetwork();


                    await syncQueue();
                }
            );


        window
            .addEventListener(
                'offline',
                updateNetwork
            );


        /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */

        function setupDates()
        {
            const naik =
                document.getElementById(
                    'tanggal_naik'
                );


            const turun =
                document.getElementById(
                    'tanggal_turun'
                );


            if (
                !naik
                ||
                !turun
            ) {
                return;
            }


            const today =
                new Date()
                    .toISOString()
                    .split(
                        'T'
                    )[0];


            naik.min =
                today;


            turun.min =
                naik.value
                    ||
                    today;


            naik.onchange =
                function () {
                    turun.min =
                        naik.value;


                    if (
                        turun.value
                        &&
                        turun.value <
                            naik.value
                    ) {
                        turun.value =
                            naik.value;
                    }
                };
        }


        /*
        |--------------------------------------------------------------------------
        | HELPERS
        |--------------------------------------------------------------------------
        */

        function escapeHtml(
            value
        ) {
            return String(
                value ?? ''
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


        function formatDate(
            value
        ) {
            if (
                !value
            ) {
                return '-';
            }


            const parts =
                value.split(
                    '-'
                );


            if (
                parts.length !==
                3
            ) {
                return value;
            }


            return (
                parts[2]
                +
                '/'
                +
                parts[1]
                +
                '/'
                +
                parts[0]
            );
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
            window
                .addEventListener(
                    'load',
                    function () {
                        navigator
                            .serviceWorker
                            .register(
                                '/sw.js'
                            )
                            .catch(
                                console.error
                            );
                    }
                );
        }


        /*
        |--------------------------------------------------------------------------
        | START
        |--------------------------------------------------------------------------
        */

        document
            .addEventListener(
                'DOMContentLoaded',
                async function () {
                    setupDates();


                    updateNetwork();


                    try {
                        await openDb();


                        await renderOfflineQueue();


                        if (
                            navigator.onLine
                        ) {
                            await syncQueue();
                        }

                    } catch (
                        error
                    ) {
                        console.error(
                            error
                        );
                    }
                }
            );
    </script>

</body>
</html>