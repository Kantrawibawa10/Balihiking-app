<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0"
    >

    <title>Portal Pendaki - Jalur Bali</title>


    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>


    {{-- Alpine --}}
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
    ></script>


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
                            'sans-serif'
                        ],

                    }

                }

            }

        }

    </script>


    <style>

        body {

            font-family:
                'Plus Jakarta Sans',
                sans-serif;

            background:
                #fbfbfa;

        }


        [x-cloak] {

            display:
                none !important;

        }

    </style>

</head>


<body
    class="bg-brand-cream pb-28 text-brand-dark antialiased"
>


    {{--
    |--------------------------------------------------------------------------
    | NAVBAR
    |--------------------------------------------------------------------------
    --}}

    <header
        class="
            sticky top-0 z-40
            border-b border-brand-dark/10
            bg-brand-cream/95
            backdrop-blur-md
        "
    >

        <div
            class="
                mx-auto flex h-16
                max-w-md
                items-center justify-between
                px-4
                sm:max-w-3xl
            "
        >

            {{-- LOGO --}}
            <a
                href="{{ route('pendaki.dashboard') }}"
                class="flex items-center gap-2"
            >

                <svg
                    class="h-6 w-6 text-brand-orange"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2.5"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 3l4 8 5-5 5 15H2L8 3z"
                    />

                </svg>


                <span
                    class="text-lg font-extrabold"
                >

                    Portal

                    <span
                        class="text-brand-orange"
                    >
                        Pendaki
                    </span>

                </span>

            </a>


            {{-- PROFILE --}}
            <div
                x-data="{ open: false }"
                class="relative"
            >

                <button
                    type="button"
                    @click="open = !open"
                    class="
                        rounded-full
                        border border-brand-dark/20
                        p-1
                        focus:outline-none
                    "
                >

                    <img
                        src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1a382b&color=fff' }}"
                        alt="{{ $user->name }}"
                        class="h-8 w-8 rounded-full object-cover"
                    >

                </button>


                <div
                    x-show="open"
                    x-cloak
                    @click.away="open = false"
                    class="
                        absolute right-0 z-50
                        mt-2 w-56
                        overflow-hidden
                        rounded-xl
                        border border-brand-dark/10
                        bg-white
                        shadow-xl
                    "
                >

                    <div
                        class="
                            border-b border-brand-dark/10
                            px-4 py-3
                        "
                    >

                        <p
                            class="text-[10px] font-semibold uppercase tracking-wide text-brand-dark/40"
                        >
                            Masuk sebagai
                        </p>

                        <p
                            class="mt-1 truncate text-sm font-bold"
                        >
                            {{ $user->name }}
                        </p>

                    </div>


                    <a
                        href="{{ route('pendaki.profil') }}"
                        class="
                            block px-4 py-3
                            text-sm font-semibold
                            hover:bg-brand-cream
                        "
                    >
                        Pengaturan Profil
                    </a>


                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="border-t border-brand-dark/10"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="
                                w-full px-4 py-3
                                text-left text-sm
                                font-semibold text-red-600
                                hover:bg-red-50
                            "
                        >
                            Keluar Akun
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </header>


    {{--
    |--------------------------------------------------------------------------
    | CONTENT
    |--------------------------------------------------------------------------
    --}}

    <main
        class="
            mx-auto
            max-w-md
            space-y-7
            px-4 pt-6
            sm:max-w-3xl
        "
    >


        {{--
        |--------------------------------------------------------------------------
        | WELCOME
        |--------------------------------------------------------------------------
        --}}

        <section
            class="
                relative overflow-hidden
                rounded-2xl
                bg-brand-dark
                p-6
                text-white
                shadow-lg
                shadow-brand-dark/10
            "
        >

            <div class="relative z-10">

                <div
                    class="
                        mb-4 inline-flex
                        items-center gap-2
                        rounded-full
                        border border-white/15
                        bg-white/10
                        px-3 py-1
                        text-xs font-semibold
                    "
                >

                    <span
                        class="h-2 w-2 rounded-full bg-brand-orange"
                    ></span>

                    Pendaki Terverifikasi

                </div>


                <h1
                    class="text-2xl font-extrabold tracking-tight"
                >
                    Halo, {{ $user->name }}!
                </h1>


                <p
                    class="
                        mt-1
                        max-w-xl
                        text-xs
                        leading-relaxed
                        text-white/75
                        sm:text-sm
                    "
                >
                    Temukan jalur pendakian dan pantau perjalanan
                    Anda secara realtime.
                </p>

            </div>


            <svg
                class="
                    pointer-events-none
                    absolute
                    -bottom-10 -right-5
                    h-44 w-44
                    text-white
                    opacity-[0.07]
                "
                fill="currentColor"
                viewBox="0 0 24 24"
            >

                <path
                    d="M3 20l7-12 3 4 5-8 3 16H3z"
                />

            </svg>

        </section>


        {{--
        |--------------------------------------------------------------------------
        | QUICK ACTIONS
        |--------------------------------------------------------------------------
        --}}

        <section>

            <div
                class="mb-4 flex items-center justify-between"
            >

                <h2
                    class="text-base font-extrabold"
                >
                    Aksi Cepat
                </h2>

                <span
                    class="text-xs font-semibold text-brand-dark/40"
                >
                    Layanan utama
                </span>

            </div>


            <div
                class="grid grid-cols-2 gap-4"
            >

                {{-- SIMAKSI --}}
                <a
                    href="{{ route('pendaki.simaksi') }}"
                    class="
                        group
                        rounded-2xl
                        border border-brand-dark/10
                        bg-white
                        p-5
                        shadow-sm
                        transition
                        hover:border-brand-orange/40
                        hover:shadow-md
                    "
                >

                    <div
                        class="
                            mb-4
                            flex h-12 w-12
                            items-center justify-center
                            rounded-xl
                            bg-brand-orange/10
                            text-brand-orange
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
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"
                            />

                        </svg>

                    </div>


                    <p
                        class="text-sm font-extrabold"
                    >
                        Izin SIMAKSI
                    </p>

                    <p
                        class="mt-1 text-[11px] text-brand-dark/50"
                    >
                        Daftar SIMAKSI online
                    </p>

                </a>


                {{-- LIVE TRACK --}}
                <a
                    href="{{ route('pendaki.live-track') }}"
                    class="
                        rounded-2xl
                        border border-brand-dark/10
                        bg-white
                        p-5
                        shadow-sm
                        transition
                        hover:border-brand-dark/30
                        hover:shadow-md
                    "
                >

                    <div
                        class="
                            mb-4
                            flex h-12 w-12
                            items-center justify-center
                            rounded-xl
                            bg-brand-dark/10
                            text-brand-dark
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
                                d="M17.657 16.657 13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            />

                        </svg>

                    </div>


                    <p class="text-sm font-extrabold">
                        Live Tracking
                    </p>

                    <p
                        class="mt-1 text-[11px] text-brand-dark/50"
                    >
                        Pantau posisi GPS
                    </p>

                </a>


                {{-- HISTORY --}}
                <a
                    href="{{ route('pendaki.riwayat') }}"
                    class="
                        rounded-2xl
                        border border-brand-dark/10
                        bg-white p-5
                        shadow-sm
                        transition
                        hover:border-amber-400/50
                        hover:shadow-md
                    "
                >

                    <div
                        class="
                            mb-4
                            flex h-12 w-12
                            items-center justify-center
                            rounded-xl
                            bg-amber-50
                            text-amber-700
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
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />

                        </svg>

                    </div>


                    <p class="text-sm font-extrabold">
                        Riwayat
                    </p>

                    <p
                        class="mt-1 text-[11px] text-brand-dark/50"
                    >
                        Catatan pendakian
                    </p>

                </a>


                {{-- SOS --}}
                <a
                    href="{{ route('pendaki.live-track') }}"
                    class="
                        rounded-2xl
                        border border-rose-200
                        bg-white p-5
                        shadow-sm
                        transition
                        hover:border-rose-400
                        hover:shadow-md
                    "
                >

                    <div
                        class="
                            mb-4
                            flex h-12 w-12
                            items-center justify-center
                            rounded-xl
                            bg-rose-50
                            text-rose-600
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
                                d="M15 17h5l-1.405-1.405A2 2 0 0118 14.158V11a6 6 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1"
                            />

                        </svg>

                    </div>


                    <p
                        class="text-sm font-extrabold text-rose-600"
                    >
                        Bantuan SOS
                    </p>

                    <p
                        class="mt-1 text-[11px] text-brand-dark/50"
                    >
                        Sinyal darurat
                    </p>

                </a>

            </div>

        </section>


        {{--
        |--------------------------------------------------------------------------
        | JALUR PENDAKIAN
        |--------------------------------------------------------------------------
        --}}

        <section>

            <div
                class="mb-4"
            >

                <h2
                    class="text-base font-extrabold"
                >
                    Jalur Pendakian Bali
                </h2>

                <p
                    class="mt-1 text-xs text-brand-dark/50"
                >
                    Temukan gunung dan informasi jalur pendakian.
                </p>

            </div>


            {{-- SEARCH --}}
            <form
                method="GET"
                action="{{ route('pendaki.dashboard') }}"
                class="mb-4"
            >

                <div class="relative">

                    <svg
                        class="
                            absolute
                            left-3.5 top-1/2
                            h-4 w-4
                            -translate-y-1/2
                            text-brand-dark/35
                        "
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"
                        />

                    </svg>


                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari gunung atau jalur..."
                        class="
                            h-11 w-full
                            rounded-xl
                            border border-brand-dark/10
                            bg-white
                            pl-10 pr-4
                            text-sm
                            outline-none
                            transition
                            placeholder:text-brand-dark/30
                            focus:border-brand-orange/40
                            focus:ring-2
                            focus:ring-brand-orange/10
                        "
                    >

                </div>

            </form>


            {{-- LIST --}}
            @if ($mountains->isNotEmpty())

                <div
                    class="space-y-3"
                >

                    @foreach ($mountains as $mountain)

                        <a
                            href="{{ route('pendaki.mountain.show', $mountain) }}"
                            class="
                                group
                                flex items-center
                                gap-4
                                rounded-2xl
                                border border-brand-dark/10
                                bg-white
                                p-3
                                shadow-sm
                                transition
                                hover:border-brand-orange/40
                                hover:shadow-md
                            "
                        >

                            {{-- IMAGE --}}
                            <div
                                class="
                                    flex h-[72px] w-[72px]
                                    shrink-0
                                    items-center justify-center
                                    overflow-hidden
                                    rounded-xl
                                    bg-brand-dark/5
                                "
                            >

                                @if ($mountain->cover_image_url)

                                    <img
                                        src="{{ $mountain->cover_image_url }}"
                                        alt="{{ $mountain->name }}"
                                        class="h-full w-full object-cover"
                                    >

                                @else

                                    <svg
                                        class="
                                            h-8 w-8
                                            text-brand-dark/25
                                        "
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 20h18L15 6l-4 7-2-3-6 10Z"
                                        />

                                    </svg>

                                @endif

                            </div>


                            {{-- CONTENT --}}
                            <div
                                class="min-w-0 flex-1"
                            >

                                <div
                                    class="flex items-start justify-between gap-2"
                                >

                                    <div>

                                        <h3
                                            class="
                                                truncate
                                                text-sm
                                                font-extrabold
                                                transition
                                                group-hover:text-brand-orange
                                            "
                                        >
                                            {{ $mountain->name }}
                                        </h3>


                                        @if ($mountain->location)

                                            <p
                                                class="
                                                    mt-0.5 truncate
                                                    text-[11px]
                                                    text-brand-dark/45
                                                "
                                            >
                                                {{ $mountain->location }}
                                            </p>

                                        @endif

                                    </div>


                                    @if ($mountain->elevation_m)

                                        <span
                                            class="
                                                shrink-0
                                                rounded-md
                                                bg-brand-dark/5
                                                px-2 py-1
                                                text-[10px]
                                                font-bold
                                                text-brand-dark/60
                                            "
                                        >
                                            {{ number_format($mountain->elevation_m, 0, ',', '.') }}
                                            mdpl
                                        </span>

                                    @endif

                                </div>


                                <div
                                    class="mt-3 flex items-center gap-3"
                                >

                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1
                                            text-[11px]
                                            font-semibold
                                            text-brand-dark/55
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

                                        {{ $mountain->active_trails_count }}
                                        jalur

                                    </span>


                                    @if (
                                        $mountain->hikingTrails->isNotEmpty()
                                    )

                                        @php
                                            $firstTrail =
                                                $mountain
                                                    ->hikingTrails
                                                    ->first();
                                        @endphp

                                        @if ($firstTrail->difficulty)

                                            <span
                                                class="
                                                    text-[11px]
                                                    font-medium
                                                    text-brand-dark/45
                                                "
                                            >
                                                {{ ucfirst($firstTrail->difficulty) }}
                                            </span>

                                        @endif

                                    @endif

                                </div>

                            </div>


                            {{-- ARROW --}}
                            <svg
                                class="
                                    h-5 w-5
                                    shrink-0
                                    text-brand-dark/25
                                    transition
                                    group-hover:translate-x-0.5
                                    group-hover:text-brand-orange
                                "
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

                        </a>

                    @endforeach

                </div>

            @else

                <div
                    class="
                        rounded-2xl
                        border border-dashed
                        border-brand-dark/15
                        bg-white
                        px-6 py-10
                        text-center
                    "
                >

                    <svg
                        class="
                            mx-auto
                            h-8 w-8
                            text-brand-dark/20
                        "
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"
                        />

                    </svg>


                    <p
                        class="mt-3 text-sm font-bold"
                    >
                        Gunung tidak ditemukan
                    </p>

                    <p
                        class="
                            mt-1
                            text-xs
                            text-brand-dark/45
                        "
                    >
                        Coba gunakan kata pencarian lain.
                    </p>


                    @if ($search !== '')

                        <a
                            href="{{ route('pendaki.dashboard') }}"
                            class="
                                mt-4
                                inline-block
                                text-xs
                                font-bold
                                text-brand-orange
                            "
                        >
                            Hapus pencarian
                        </a>

                    @endif

                </div>

            @endif

        </section>


        <div class="h-3"></div>

    </main>


    {{--
    |--------------------------------------------------------------------------
    | BOTTOM NAV
    |--------------------------------------------------------------------------
    --}}

    @include(
        'pendaki.components.bottom-nav',
        [
            'active' => 'dashboard'
        ]
    )

</body>

</html>