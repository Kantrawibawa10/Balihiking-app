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
        name="apple-mobile-web-app-title"
        content="BaliHiking"
    >

    <meta
        name="application-name"
        content="BaliHiking"
    >


    <title>
        Pendaftaran SIMAKSI - BaliHiking
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
    {{-- LOCAL TAILWIND --}}
    {{-- ========================================================= --}}

    <script
        src="{{ asset('vendor/tailwindcss.js') }}"
    ></script>


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



    <style>

        * {
            box-sizing:
                border-box;
        }


        html {

            background:
                #fbfbfa;

        }


        body {

            margin:
                0;

            min-height:
                100vh;

            background:
                #fbfbfa;

            color:
                #1a382b;

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
        | NETWORK
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

            padding:
                9px 14px;

            transform:
                translateX(-50%);

            border-radius:
                999px;

            background:
                #f06535;

            color:
                white;

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
                block;

        }


        /*
        |--------------------------------------------------------------------------
        | OFFLINE ROW
        |--------------------------------------------------------------------------
        */

        .offline-row {

            background:
                #fff8f3;

        }


        /*
        |--------------------------------------------------------------------------
        | TABLE SCROLL
        |--------------------------------------------------------------------------
        */

        .table-scroll {

            width:
                100%;

            overflow-x:
                auto;

            -webkit-overflow-scrolling:
                touch;

        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE / DESKTOP
        |--------------------------------------------------------------------------
        */

        .desktop-table {

            display:
                none;

        }


        .mobile-list {

            display:
                block;

        }


        @media (
            min-width:
                768px
        ) {

            .desktop-table {

                display:
                    block;

            }


            .mobile-list {

                display:
                    none;

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


    {{-- ========================================================= --}}
    {{-- NETWORK STATUS --}}
    {{-- ========================================================= --}}

    <div
        id="networkStatus"
    >
        Offline · data akan disinkronkan
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
                max-w-5xl
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
                "
            >

                <svg
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2.4"
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
                        Registrasi SIMAKSI
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


            <div
                class="w-10"
            ></div>

        </div>

    </header>



    {{-- ========================================================= --}}
    {{-- MAIN --}}
    {{-- ========================================================= --}}

    <main
        class="
            mx-auto
            max-w-5xl
            space-y-7
            px-4
            py-6
        "
    >


        {{-- ===================================================== --}}
        {{-- SUCCESS --}}
        {{-- ===================================================== --}}

        @if(session('success'))

            <div
                class="
                    rounded-2xl
                    bg-brand-dark
                    p-4
                    text-xs
                    font-semibold
                    text-white
                "
            >
                {{ session('success') }}
            </div>

        @endif



        {{-- ===================================================== --}}
        {{-- FORM --}}
        {{-- ===================================================== --}}

        <section
            class="
                mx-auto
                w-full
                max-w-xl
            "
        >

            <form
                action="{{ route('pendaki.simaksi.store') }}"
                method="POST"
                id="simaksiForm"
                class="
                    space-y-4
                    rounded-3xl
                    border
                    border-brand-dark/10
                    bg-white
                    p-6
                    shadow-sm
                "
            >

                @csrf


                <div>

                    <span
                        class="
                            border-b-2
                            border-brand-orange
                            pb-1
                            text-[10px]
                            font-black
                            uppercase
                            tracking-widest
                            text-brand-orange
                        "
                    >
                        Izin Pendakian
                    </span>


                    <h2
                        class="
                            mt-3
                            text-xl
                            font-black
                        "
                    >
                        Form Permohonan
                    </h2>


                    <p
                        class="
                            mt-1
                            text-xs
                            leading-relaxed
                            text-brand-dark/55
                        "
                    >
                        Isi data perjalanan secara akurat untuk
                        mendukung keselamatan selama pendakian.
                    </p>

                </div>



                {{-- GUNUNG --}}

                <div>

                    <label
                        class="
                            mb-1.5
                            block
                            text-xs
                            font-bold
                        "
                    >
                        Pilih Gunung
                    </label>


                    <select
                        name="gunung"
                        required
                        class="
                            h-12
                            w-full
                            rounded-xl
                            border
                            border-brand-dark/15
                            bg-brand-cream
                            px-3.5
                            text-xs
                            font-bold
                            outline-none
                            focus:border-brand-orange
                            focus:ring-1
                            focus:ring-brand-orange
                        "
                    >

                        <option
                            value="Gunung Agung"
                            @selected(
                                old('gunung') ===
                                'Gunung Agung'
                            )
                        >
                            Gunung Agung (3.031 mdpl)
                        </option>


                        <option
                            value="Gunung Batur"
                            @selected(
                                old('gunung') ===
                                'Gunung Batur'
                            )
                        >
                            Gunung Batur (1.717 mdpl)
                        </option>


                        <option
                            value="Gunung Abang"
                            @selected(
                                old('gunung') ===
                                'Gunung Abang'
                            )
                        >
                            Gunung Abang (2.152 mdpl)
                        </option>

                    </select>


                    @error('gunung')

                        <p
                            class="
                                mt-1
                                text-[10px]
                                text-red-600
                            "
                        >
                            {{ $message }}
                        </p>

                    @enderror

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
                            class="
                                mb-1.5
                                block
                                text-xs
                                font-bold
                            "
                        >
                            Tanggal Naik
                        </label>


                        <input
                            type="date"
                            name="tanggal_naik"
                            id="tanggal_naik"
                            value="{{ old('tanggal_naik') }}"
                            required
                            class="
                                h-12
                                w-full
                                rounded-xl
                                border
                                border-brand-dark/15
                                bg-brand-cream
                                px-3
                                text-xs
                                font-semibold
                                outline-none
                                focus:border-brand-orange
                                focus:ring-1
                                focus:ring-brand-orange
                            "
                        >

                    </div>


                    <div>

                        <label
                            class="
                                mb-1.5
                                block
                                text-xs
                                font-bold
                            "
                        >
                            Tanggal Turun
                        </label>


                        <input
                            type="date"
                            name="tanggal_turun"
                            id="tanggal_turun"
                            value="{{ old('tanggal_turun') }}"
                            required
                            class="
                                h-12
                                w-full
                                rounded-xl
                                border
                                border-brand-dark/15
                                bg-brand-cream
                                px-3
                                text-xs
                                font-semibold
                                outline-none
                                focus:border-brand-orange
                                focus:ring-1
                                focus:ring-brand-orange
                            "
                        >

                    </div>

                </div>



                {{-- JUMLAH --}}

                <div>

                    <label
                        class="
                            mb-1.5
                            block
                            text-xs
                            font-bold
                        "
                    >
                        Jumlah Anggota Rombongan
                    </label>


                    <input
                        type="number"
                        name="jumlah_anggota"
                        min="1"
                        max="100"
                        value="{{ old('jumlah_anggota', 1) }}"
                        required
                        class="
                            h-12
                            w-full
                            rounded-xl
                            border
                            border-brand-dark/15
                            bg-brand-cream
                            px-3.5
                            text-xs
                            font-bold
                            outline-none
                            focus:border-brand-orange
                            focus:ring-1
                            focus:ring-brand-orange
                        "
                    >

                </div>



                {{-- EMERGENCY --}}

                <div>

                    <label
                        class="
                            mb-1.5
                            block
                            text-xs
                            font-bold
                        "
                    >
                        Nomor Kontak Darurat
                    </label>


                    <input
                        type="tel"
                        name="nomor_darurat"
                        value="{{ old('nomor_darurat') }}"
                        placeholder="08xxxxxxxxxx"
                        required
                        class="
                            h-12
                            w-full
                            rounded-xl
                            border
                            border-brand-dark/15
                            bg-brand-cream
                            px-3.5
                            text-xs
                            font-semibold
                            outline-none
                            placeholder:text-brand-dark/30
                            focus:border-brand-orange
                            focus:ring-1
                            focus:ring-brand-orange
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
                            text-[10px]
                            leading-relaxed
                            text-brand-dark/50
                        "
                    >
                        Jika internet terputus, permohonan akan
                        disimpan terlebih dahulu di perangkat dan
                        dikirim otomatis ketika BaliHiking kembali
                        online.
                    </p>

                </div>



                <button
                    type="submit"
                    id="submitButton"
                    class="
                        flex
                        h-12
                        w-full
                        items-center
                        justify-center
                        rounded-xl
                        bg-brand-orange
                        text-xs
                        font-bold
                        text-white
                        shadow-lg
                        shadow-brand-orange/20
                        transition
                        active:scale-[0.98]
                        disabled:opacity-50
                    "
                >
                    <span id="submitText">
                        Kirim Permohonan SIMAKSI →
                    </span>
                </button>

            </form>

        </section>



        {{-- ===================================================== --}}
        {{-- DATA SIMAKSI --}}
        {{-- ===================================================== --}}

        <section
            class="
                space-y-4
            "
        >


            {{-- HEADER --}}

            <div
                class="
                    flex
                    flex-col
                    gap-3
                    sm:flex-row
                    sm:items-end
                    sm:justify-between
                "
            >

                <div>

                    <span
                        class="
                            text-[10px]
                            font-black
                            uppercase
                            tracking-widest
                            text-brand-orange
                        "
                    >
                        Riwayat Permohonan
                    </span>


                    <h2
                        class="
                            mt-1
                            text-lg
                            font-black
                        "
                    >
                        Data SIMAKSI Saya
                    </h2>


                    <p
                        class="
                            mt-1
                            text-xs
                            text-brand-dark/45
                        "
                    >
                        Pantau status permohonan izin pendakian Anda.
                    </p>

                </div>


                <div
                    id="offlineQueueBadge"
                    class="
                        hidden
                        rounded-full
                        bg-orange-100
                        px-3
                        py-1.5
                        text-[9px]
                        font-bold
                        text-brand-orange
                    "
                >
                </div>

            </div>



            {{-- ================================================= --}}
            {{-- SUMMARY --}}
            {{-- ================================================= --}}

            <div
                class="
                    grid
                    grid-cols-2
                    gap-3
                    sm:grid-cols-4
                "
            >


                <div
                    class="
                        rounded-2xl
                        border
                        border-brand-dark/10
                        bg-white
                        p-4
                    "
                >

                    <p
                        class="
                            text-[9px]
                            text-brand-dark/40
                        "
                    >
                        Total
                    </p>


                    <p
                        class="
                            mt-1
                            text-xl
                            font-black
                        "
                    >
                        {{ $totalSimaksi }}
                    </p>

                </div>



                <div
                    class="
                        rounded-2xl
                        border
                        border-amber-100
                        bg-amber-50
                        p-4
                    "
                >

                    <p
                        class="
                            text-[9px]
                            text-amber-700/60
                        "
                    >
                        Pending
                    </p>


                    <p
                        class="
                            mt-1
                            text-xl
                            font-black
                            text-amber-700
                        "
                    >
                        {{ $totalPending }}
                    </p>

                </div>



                <div
                    class="
                        rounded-2xl
                        border
                        border-emerald-100
                        bg-emerald-50
                        p-4
                    "
                >

                    <p
                        class="
                            text-[9px]
                            text-emerald-700/60
                        "
                    >
                        Disetujui
                    </p>


                    <p
                        class="
                            mt-1
                            text-xl
                            font-black
                            text-emerald-700
                        "
                    >
                        {{ $totalApproved }}
                    </p>

                </div>



                <div
                    class="
                        rounded-2xl
                        border
                        border-red-100
                        bg-red-50
                        p-4
                    "
                >

                    <p
                        class="
                            text-[9px]
                            text-red-700/60
                        "
                    >
                        Ditolak
                    </p>


                    <p
                        class="
                            mt-1
                            text-xl
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
                    flex
                    gap-2
                    overflow-x-auto
                    pb-1
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
                                        $filterValue
                                ]
                            )
                        }}"
                        class="
                            shrink-0
                            rounded-lg
                            px-4
                            py-2
                            text-[10px]
                            font-bold
                            transition

                            {{
                                $status === $filterValue
                                    ? 'bg-brand-dark text-white'
                                    : 'border border-brand-dark/10 bg-white text-brand-dark/55'
                            }}
                        "
                    >
                        {{ $filterLabel }}
                    </a>

                @endforeach

            </div>



            {{-- ================================================= --}}
            {{-- OFFLINE LOCAL DATA --}}
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
                            text-xs
                            font-black
                            text-brand-orange
                        "
                    >
                        Menunggu Sinkronisasi
                    </p>


                    <p
                        class="
                            mt-0.5
                            text-[9px]
                            text-orange-700/60
                        "
                    >
                        Data berikut masih tersimpan di perangkat.
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
            {{-- DESKTOP TABLE --}}
            {{-- ================================================= --}}

            <div
                class="
                    desktop-table
                    overflow-hidden
                    rounded-2xl
                    border
                    border-brand-dark/10
                    bg-white
                    shadow-sm
                "
            >

                <div
                    class="table-scroll"
                >

                    <table
                        class="
                            w-full
                            min-w-[850px]
                            border-collapse
                            text-left
                        "
                    >

                        <thead
                            class="
                                bg-brand-dark/[0.04]
                            "
                        >

                            <tr>

                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-[9px]
                                        font-black
                                        uppercase
                                        tracking-wide
                                        text-brand-dark/40
                                    "
                                >
                                    No
                                </th>


                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-[9px]
                                        font-black
                                        uppercase
                                        text-brand-dark/40
                                    "
                                >
                                    Gunung
                                </th>


                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-[9px]
                                        font-black
                                        uppercase
                                        text-brand-dark/40
                                    "
                                >
                                    Tanggal Naik
                                </th>


                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-[9px]
                                        font-black
                                        uppercase
                                        text-brand-dark/40
                                    "
                                >
                                    Tanggal Turun
                                </th>


                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-[9px]
                                        font-black
                                        uppercase
                                        text-brand-dark/40
                                    "
                                >
                                    Anggota
                                </th>


                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-[9px]
                                        font-black
                                        uppercase
                                        text-brand-dark/40
                                    "
                                >
                                    Kontak Darurat
                                </th>


                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-[9px]
                                        font-black
                                        uppercase
                                        text-brand-dark/40
                                    "
                                >
                                    Status
                                </th>


                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-[9px]
                                        font-black
                                        uppercase
                                        text-brand-dark/40
                                    "
                                >
                                    Tanggal Pengajuan
                                </th>

                            </tr>

                        </thead>


                        <tbody
                            class="
                                divide-y
                                divide-brand-dark/5
                            "
                        >

                            @forelse(
                                $simaksis
                                as $simaksi
                            )

                                <tr
                                    class="
                                        transition
                                        hover:bg-brand-cream/60
                                    "
                                >

                                    <td
                                        class="
                                            px-4
                                            py-4
                                            text-xs
                                            font-semibold
                                            text-brand-dark/50
                                        "
                                    >
                                        {{
                                            $simaksis->firstItem()
                                            +
                                            $loop->index
                                        }}
                                    </td>


                                    <td
                                        class="
                                            px-4
                                            py-4
                                        "
                                    >

                                        <p
                                            class="
                                                text-xs
                                                font-extrabold
                                            "
                                        >
                                            {{ $simaksi->gunung }}
                                        </p>

                                    </td>


                                    <td
                                        class="
                                            px-4
                                            py-4
                                            text-xs
                                            font-semibold
                                        "
                                    >
                                        {{
                                            $simaksi
                                                ->tanggal_naik
                                                ->format('d/m/Y')
                                        }}
                                    </td>


                                    <td
                                        class="
                                            px-4
                                            py-4
                                            text-xs
                                            font-semibold
                                        "
                                    >
                                        {{
                                            $simaksi
                                                ->tanggal_turun
                                                ->format('d/m/Y')
                                        }}
                                    </td>


                                    <td
                                        class="
                                            px-4
                                            py-4
                                            text-xs
                                            font-semibold
                                        "
                                    >
                                        {{
                                            $simaksi
                                                ->jumlah_anggota
                                        }}

                                        orang
                                    </td>


                                    <td
                                        class="
                                            px-4
                                            py-4
                                            text-xs
                                            font-semibold
                                        "
                                    >
                                        {{
                                            $simaksi
                                                ->nomor_darurat
                                        }}
                                    </td>


                                    <td
                                        class="
                                            px-4
                                            py-4
                                        "
                                    >

                                        @if(
                                            $simaksi->status ===
                                            'approved'
                                        )

                                            <span
                                                class="
                                                    inline-flex
                                                    items-center
                                                    gap-1.5
                                                    rounded-full
                                                    bg-emerald-100
                                                    px-2.5
                                                    py-1
                                                    text-[9px]
                                                    font-black
                                                    text-emerald-700
                                                "
                                            >

                                                <span
                                                    class="
                                                        h-1.5
                                                        w-1.5
                                                        rounded-full
                                                        bg-emerald-600
                                                    "
                                                ></span>

                                                Disetujui

                                            </span>


                                        @elseif(
                                            $simaksi->status ===
                                            'rejected'
                                        )

                                            <span
                                                class="
                                                    inline-flex
                                                    items-center
                                                    gap-1.5
                                                    rounded-full
                                                    bg-red-100
                                                    px-2.5
                                                    py-1
                                                    text-[9px]
                                                    font-black
                                                    text-red-700
                                                "
                                            >

                                                <span
                                                    class="
                                                        h-1.5
                                                        w-1.5
                                                        rounded-full
                                                        bg-red-600
                                                    "
                                                ></span>

                                                Ditolak

                                            </span>


                                        @else

                                            <span
                                                class="
                                                    inline-flex
                                                    items-center
                                                    gap-1.5
                                                    rounded-full
                                                    bg-amber-100
                                                    px-2.5
                                                    py-1
                                                    text-[9px]
                                                    font-black
                                                    text-amber-700
                                                "
                                            >

                                                <span
                                                    class="
                                                        h-1.5
                                                        w-1.5
                                                        rounded-full
                                                        bg-amber-500
                                                    "
                                                ></span>

                                                Pending

                                            </span>

                                        @endif

                                    </td>


                                    <td
                                        class="
                                            px-4
                                            py-4
                                            text-[10px]
                                            text-brand-dark/45
                                        "
                                    >

                                        {{
                                            $simaksi
                                                ->created_at
                                                ->format(
                                                    'd/m/Y H:i'
                                                )
                                        }}

                                    </td>

                                </tr>


                                @if(
                                    $simaksi->catatan_admin
                                )

                                    <tr>

                                        <td
                                            colspan="8"
                                            class="
                                                bg-brand-cream/40
                                                px-4
                                                py-2.5
                                            "
                                        >

                                            <p
                                                class="
                                                    text-[9px]
                                                    leading-relaxed
                                                    text-brand-dark/55
                                                "
                                            >

                                                <span
                                                    class="font-bold"
                                                >
                                                    Catatan:
                                                </span>

                                                {{
                                                    $simaksi
                                                        ->catatan_admin
                                                }}

                                            </p>

                                        </td>

                                    </tr>

                                @endif


                            @empty

                                <tr>

                                    <td
                                        colspan="8"
                                        class="
                                            px-6
                                            py-12
                                            text-center
                                        "
                                    >

                                        <p
                                            class="
                                                text-sm
                                                font-bold
                                            "
                                        >
                                            Belum ada data SIMAKSI
                                        </p>


                                        <p
                                            class="
                                                mt-1
                                                text-xs
                                                text-brand-dark/40
                                            "
                                        >
                                            Permohonan yang Anda kirim akan tampil di sini.
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- MOBILE LIST --}}
            {{-- ================================================= --}}

            <div
                class="
                    mobile-list
                    space-y-3
                "
            >

                @forelse(
                    $simaksis
                    as $simaksi
                )

                    <article
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
                            class="p-4"
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
                                            text-[9px]
                                            font-bold
                                            uppercase
                                            text-brand-orange
                                        "
                                    >
                                        SIMAKSI
                                        #{{ $simaksi->id }}
                                    </p>


                                    <h3
                                        class="
                                            mt-1
                                            text-sm
                                            font-black
                                        "
                                    >
                                        {{ $simaksi->gunung }}
                                    </h3>

                                </div>



                                @if(
                                    $simaksi->status ===
                                    'approved'
                                )

                                    <span
                                        class="
                                            rounded-full
                                            bg-emerald-100
                                            px-2.5
                                            py-1
                                            text-[9px]
                                            font-black
                                            text-emerald-700
                                        "
                                    >
                                        Disetujui
                                    </span>


                                @elseif(
                                    $simaksi->status ===
                                    'rejected'
                                )

                                    <span
                                        class="
                                            rounded-full
                                            bg-red-100
                                            px-2.5
                                            py-1
                                            text-[9px]
                                            font-black
                                            text-red-700
                                        "
                                    >
                                        Ditolak
                                    </span>


                                @else

                                    <span
                                        class="
                                            rounded-full
                                            bg-amber-100
                                            px-2.5
                                            py-1
                                            text-[9px]
                                            font-black
                                            text-amber-700
                                        "
                                    >
                                        Pending
                                    </span>

                                @endif

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

                                    <p
                                        class="
                                            text-[9px]
                                            text-brand-dark/40
                                        "
                                    >
                                        Tanggal Naik
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            font-bold
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
                                        rounded-xl
                                        bg-brand-cream
                                        p-3
                                    "
                                >

                                    <p
                                        class="
                                            text-[9px]
                                            text-brand-dark/40
                                        "
                                    >
                                        Tanggal Turun
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            font-bold
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



                            <div
                                class="
                                    mt-3
                                    flex
                                    items-center
                                    justify-between
                                    gap-3
                                    text-[10px]
                                    text-brand-dark/50
                                "
                            >

                                <span>
                                    {{
                                        $simaksi
                                            ->jumlah_anggota
                                    }}
                                    anggota
                                </span>


                                <span>
                                    {{
                                        $simaksi
                                            ->nomor_darurat
                                    }}
                                </span>

                            </div>



                            @if(
                                $simaksi->catatan_admin
                            )

                                <div
                                    class="
                                        mt-3
                                        rounded-xl
                                        bg-brand-dark/[0.04]
                                        p-3
                                    "
                                >

                                    <p
                                        class="
                                            text-[9px]
                                            font-bold
                                            text-brand-dark/50
                                        "
                                    >
                                        Catatan Admin
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-[10px]
                                            leading-relaxed
                                            text-brand-dark/65
                                        "
                                    >
                                        {{
                                            $simaksi
                                                ->catatan_admin
                                        }}
                                    </p>

                                </div>

                            @endif

                        </div>



                        <div
                            class="
                                border-t
                                border-brand-dark/5
                                bg-brand-cream/50
                                px-4
                                py-2.5
                            "
                        >

                            <p
                                class="
                                    text-[9px]
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

                    </article>


                @empty

                    <div
                        class="
                            rounded-2xl
                            border
                            border-dashed
                            border-brand-dark/15
                            bg-white
                            py-10
                            text-center
                        "
                    >

                        <p
                            class="
                                text-sm
                                font-bold
                            "
                        >
                            Belum ada SIMAKSI
                        </p>


                        <p
                            class="
                                mt-1
                                text-xs
                                text-brand-dark/40
                            "
                        >
                            Kirim permohonan melalui form di atas.
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
                    class="pt-2"
                >
                    {{ $simaksis->links() }}
                </div>

            @endif

        </section>

    </main>



    {{-- ========================================================= --}}
    {{-- BOTTOM NAV --}}
    {{-- ========================================================= --}}

    @include(
        'pendaki.components.bottom-nav',
        [
            'active' => 'simaksi'
        ]
    )



    {{-- ========================================================= --}}
    {{-- OFFLINE ENGINE --}}
    {{-- ========================================================= --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | CONFIG
        |--------------------------------------------------------------------------
        */

        const SIMAKSI_DB_NAME =
            'balihiking-simaksi';


        const SIMAKSI_DB_VERSION =
            1;


        const SIMAKSI_STORE =
            'simaksi_queue';


        const simaksiEndpoint =
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


        let simaksiDb =
            null;


        let syncing =
            false;



        /*
        |--------------------------------------------------------------------------
        | DB
        |--------------------------------------------------------------------------
        */

        function openDb() {

            return new Promise(
                function (
                    resolve,
                    reject
                ) {

                    if (
                        simaksiDb
                    ) {

                        resolve(
                            simaksiDb
                        );

                        return;

                    }


                    const request =
                        indexedDB.open(
                            SIMAKSI_DB_NAME,
                            SIMAKSI_DB_VERSION
                        );


                    request.onupgradeneeded =
                        function (
                            event
                        ) {

                            const db =
                                event.target.result;


                            if (
                                !db.objectStoreNames
                                    .contains(
                                        SIMAKSI_STORE
                                    )
                            ) {

                                db.createObjectStore(
                                    SIMAKSI_STORE,
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

                            simaksiDb =
                                event.target.result;


                            resolve(
                                simaksiDb
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



        async function getQueue() {

            const db =
                await openDb();


            return new Promise(
                function (
                    resolve,
                    reject
                ) {

                    const transaction =
                        db.transaction(
                            SIMAKSI_STORE,
                            'readonly'
                        );


                    const store =
                        transaction
                            .objectStore(
                                SIMAKSI_STORE
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
                            SIMAKSI_STORE,
                            'readwrite'
                        );


                    const store =
                        transaction
                            .objectStore(
                                SIMAKSI_STORE
                            );


                    const request =
                        store.add({
                            payload:
                                payload,

                            created_at:
                                Date.now()
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
                            SIMAKSI_STORE,
                            'readwrite'
                        );


                    const store =
                        transaction
                            .objectStore(
                                SIMAKSI_STORE
                            );


                    const request =
                        store.delete(
                            id
                        );


                    request.onsuccess =
                        resolve;


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
        | FORM
        |--------------------------------------------------------------------------
        */

        const form =
            document.getElementById(
                'simaksiForm'
            );


        const submitButton =
            document.getElementById(
                'submitButton'
            );


        const submitText =
            document.getElementById(
                'submitText'
            );


        form.addEventListener(
            'submit',
            async function (
                event
            ) {

                event.preventDefault();


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


                    await renderOfflineQueue();


                    alert(
                        'Permohonan SIMAKSI disimpan di perangkat dan akan dikirim ketika internet kembali.'
                    );


                    form.reset();

                    form.jumlah_anggota.value =
                        1;


                    return;

                }


                await sendPayload(
                    payload
                );

        });



        /*
        |--------------------------------------------------------------------------
        | SEND
        |--------------------------------------------------------------------------
        */

        async function sendPayload(
            payload
        ) {

            submitButton.disabled =
                true;


            submitText.textContent =
                'Mengirim...';


            try {

                const response =
                    await fetch(
                        simaksiEndpoint,
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
                                    csrfToken

                            },

                            body:
                                JSON.stringify(
                                    payload
                                )

                        }
                    );


                const data =
                    await response.json();


                if (
                    !response.ok
                ) {

                    let message =
                        data.message
                        ||
                        'Permohonan gagal dikirim.';


                    if (
                        data.errors
                    ) {

                        message =
                            Object.values(
                                data.errors
                            )
                            .flat()
                            .join(
                                '\n'
                            );

                    }


                    alert(
                        message
                    );


                    return false;

                }


                window.location.href =
                    @json(
                        route(
                            'pendaki.simaksi'
                        )
                    );


                return true;


            } catch (
                error
            ) {

                await addQueue(
                    payload
                );


                await renderOfflineQueue();


                alert(
                    'Koneksi terputus. Permohonan diamankan di perangkat.'
                );


                return false;


            } finally {


                submitButton.disabled =
                    false;


                submitText.textContent =
                    navigator.onLine
                        ?
                        'Kirim Permohonan SIMAKSI →'
                        :
                        'Simpan SIMAKSI Offline';

            }

        }



        /*
        |--------------------------------------------------------------------------
        | RENDER OFFLINE QUEUE
        |--------------------------------------------------------------------------
        */

        async function renderOfflineQueue() {

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
                `${queue.length} menunggu sinkronisasi`;


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

                            <div>

                                <p
                                    class="
                                        text-[9px]
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
                                        text-xs
                                        font-black
                                        text-brand-dark
                                    "
                                >
                                    ${escapeHtml(
                                        payload.gunung
                                    )}
                                </p>

                                <p
                                    class="
                                        mt-1
                                        text-[10px]
                                        text-brand-dark/50
                                    "
                                >
                                    ${formatDate(payload.tanggal_naik)}
                                    -
                                    ${formatDate(payload.tanggal_turun)}
                                    ·
                                    ${escapeHtml(payload.jumlah_anggota)}
                                    anggota
                                </p>

                            </div>

                            <span
                                class="
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

        async function syncQueue() {

            if (
                !navigator.onLine
                ||
                syncing
            ) {

                return;

            }


            syncing =
                true;


            try {

                const queue =
                    await getQueue();


                let synced =
                    0;


                for (
                    const item
                    of queue
                ) {

                    try {

                        const response =
                            await fetch(
                                simaksiEndpoint,
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

                            await deleteQueue(
                                item.id
                            );


                            synced++;

                        }


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

                    window.location.reload();

                }


            } finally {

                syncing =
                    false;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | NETWORK UI
        |--------------------------------------------------------------------------
        */

        function updateNetworkUI() {

            const status =
                document.getElementById(
                    'networkStatus'
                );


            if (
                navigator.onLine
            ) {

                status
                    .classList
                    .remove(
                        'show'
                    );


                submitText.textContent =
                    'Kirim Permohonan SIMAKSI →';


            } else {


                status
                    .classList
                    .add(
                        'show'
                    );


                submitText.textContent =
                    'Simpan SIMAKSI Offline';

            }

        }



        window.addEventListener(
            'online',
            async function () {

                updateNetworkUI();

                await syncQueue();

        });


        window.addEventListener(
            'offline',
            updateNetworkUI
        );



        /*
        |--------------------------------------------------------------------------
        | DATES
        |--------------------------------------------------------------------------
        */

        function setupDates() {

            const naik =
                document.getElementById(
                    'tanggal_naik'
                );


            const turun =
                document.getElementById(
                    'tanggal_turun'
                );


            const today =
                new Date()
                    .toISOString()
                    .split('T')[0];


            naik.min =
                today;


            turun.min =
                today;


            naik.addEventListener(
                'change',
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

                }
            );

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

            window.addEventListener(
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

        document.addEventListener(
            'DOMContentLoaded',
            async function () {

                await openDb();

                await renderOfflineQueue();

                updateNetworkUI();

                setupDates();


                if (
                    navigator.onLine
                ) {

                    await syncQueue();

                }

            }
        );

    </script>


</body>

</html>