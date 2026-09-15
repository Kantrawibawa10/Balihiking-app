<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0"
    >

    <title>Riwayat Pendakian - Jalur Bali</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

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
        };
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fbfbfa;
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
                max-w-md
                items-center
                justify-between
                px-4
                sm:max-w-3xl
            "
        >
            {{-- Back --}}
            <a
                href="{{ route('pendaki.dashboard') }}"
                class="
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

            {{-- Title --}}
            <div class="text-center">
                <h1
                    class="
                        text-sm
                        font-extrabold
                        text-brand-dark
                    "
                >
                    Riwayat Pendakian
                </h1>

                <p
                    class="
                        mt-0.5
                        text-[10px]
                        text-brand-dark/40
                    "
                >
                    Aktivitas perjalanan Anda
                </p>
            </div>

            <div class="h-10 w-10"></div>
        </div>
    </header>


    {{-- =========================================================
        MAIN
    ========================================================== --}}

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

        {{-- =====================================================
            SUMMARY
        ====================================================== --}}

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

            {{-- Total --}}
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


            {{-- Selesai --}}
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


            {{-- Berlangsung --}}
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


        {{-- =====================================================
            FILTER
        ====================================================== --}}

        <section>
            <div
                class="
                    flex
                    gap-2
                    overflow-x-auto
                    pb-1
                "
            >

                <a
                    href="{{ route('pendaki.riwayat') }}"
                    class="
                        shrink-0
                        rounded-lg
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        transition
                        {{ $status === 'all'
                            ? 'bg-brand-dark text-white'
                            : 'border border-brand-dark/10 bg-white text-brand-dark/60 hover:bg-brand-dark/5'
                        }}
                    "
                >
                    Semua
                </a>


                <a
                    href="{{ route('pendaki.riwayat', ['status' => 'completed']) }}"
                    class="
                        shrink-0
                        rounded-lg
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        transition
                        {{ $status === 'completed'
                            ? 'bg-emerald-600 text-white'
                            : 'border border-brand-dark/10 bg-white text-brand-dark/60 hover:bg-brand-dark/5'
                        }}
                    "
                >
                    Selesai
                </a>


                <a
                    href="{{ route('pendaki.riwayat', ['status' => 'ongoing']) }}"
                    class="
                        shrink-0
                        rounded-lg
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        transition
                        {{ $status === 'ongoing'
                            ? 'bg-amber-500 text-white'
                            : 'border border-brand-dark/10 bg-white text-brand-dark/60 hover:bg-brand-dark/5'
                        }}
                    "
                >
                    Berlangsung
                </a>

            </div>
        </section>


        {{-- =====================================================
            TITLE
        ====================================================== --}}

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
                Riwayat pendakian yang tercatat di sistem.
            </p>
        </section>


        {{-- =====================================================
            RIWAYAT LIST
        ====================================================== --}}

        <section class="space-y-3">

            @forelse($riwayat as $item)

                @php
                    $trail = $item->hikingTrail;
                    $mountain = $trail?->mountain;
                    $isCompleted = ! is_null($item->completed_at);
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

                    {{-- =================================================
                        MAIN CARD
                    ================================================== --}}

                    <div
                        class="
                            flex
                            items-start
                            gap-4
                            p-4
                        "
                    >

                        {{-- Icon --}}
                        <div
                            class="
                                flex
                                h-12
                                w-12
                                shrink-0
                                items-center
                                justify-center
                                rounded-xl

                                {{ $isCompleted
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


                        {{-- Content --}}
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

                                <div class="min-w-0">

                                    <h3
                                        class="
                                            truncate
                                            text-sm
                                            font-extrabold
                                            text-brand-dark
                                        "
                                    >
                                        {{ $mountain?->name ?? 'Gunung tidak tersedia' }}
                                    </h3>


                                    <p
                                        class="
                                            mt-0.5
                                            truncate
                                            text-[11px]
                                            text-brand-dark/50
                                        "
                                    >
                                        {{ $trail?->name ?? 'Jalur tidak tersedia' }}
                                    </p>

                                </div>


                                {{-- Status --}}
                                <span
                                    class="
                                        shrink-0
                                        rounded-full
                                        px-2.5
                                        py-1
                                        text-[10px]
                                        font-bold

                                        {{ $isCompleted
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-amber-100 text-amber-700'
                                        }}
                                    "
                                >
                                    {{ $isCompleted ? 'Selesai' : 'Berlangsung' }}
                                </span>

                            </div>


                            {{-- Dates --}}
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

                                {{-- Mulai --}}
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
                                        {{ optional($item->created_at)->locale('id')->translatedFormat('d F Y') }}
                                    </span>
                                </div>


                                {{-- Selesai --}}
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
                                            {{ optional($item->completed_at)->locale('id')->translatedFormat('d F Y') }}
                                        </span>
                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        DETAIL JALUR
                    ================================================== --}}

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

                            {{-- Jarak --}}
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

                                        {{ number_format(
                                            $trail->distance_km,
                                            1,
                                            ',',
                                            '.'
                                        ) }} km

                                    @else

                                        -

                                    @endif
                                </p>
                            </div>


                            {{-- Estimasi --}}
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

                                        {{ $trail->estimated_time_hours }} jam

                                    @else

                                        -

                                    @endif
                                </p>
                            </div>


                            {{-- Kesulitan --}}
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
                                    {{ $trail->difficulty
                                        ? ucfirst($trail->difficulty)
                                        : '-'
                                    }}
                                </p>
                            </div>

                        </div>


                        {{-- =================================================
                            ACTION
                        ================================================== --}}

                        <div
                            class="
                                flex
                                gap-2
                                border-t
                                border-brand-dark/10
                                p-3
                            "
                        >

                            {{-- Lihat Jalur --}}
                            <a
                                href="{{ route('pendaki.trail.show', $trail) }}"
                                class="
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


                            {{-- Lanjut Tracking --}}
                            @if(!$isCompleted)

                                <a
                                    href="{{ route(
                                        'pendaki.live-track',
                                        [
                                            'trail_id' => $trail->id
                                        ]
                                    ) }}"
                                    class="
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

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}

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

                            Riwayat perjalanan akan muncul setelah Anda
                            memilih dan memulai jalur pendakian.

                        @endif

                    </p>


                    <a
                        href="{{ route('pendaki.dashboard') }}"
                        class="
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


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}

        @if($riwayat->hasPages())

            <div class="pt-2">

                {{ $riwayat->links() }}

            </div>

        @endif


        <div class="h-2"></div>

    </main>


    {{-- =========================================================
        BOTTOM NAV
    ========================================================== --}}

    @include(
        'pendaki.components.bottom-nav',
        [
            'active' => 'riwayat'
        ]
    )

</body>

</html>