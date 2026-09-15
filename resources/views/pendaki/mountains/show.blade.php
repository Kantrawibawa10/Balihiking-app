<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $mountain->name }} - Jalur Bali
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

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

</head>


<body class="
        bg-brand-cream
        pb-24
        font-sans
        text-brand-dark
        antialiased
    ">


    {{-- HEADER --}}
    <header
        class="
            sticky top-0 z-40
            border-b border-brand-dark/10
            bg-brand-cream/95
            backdrop-blur-md
        ">

        <div
            class="
                mx-auto
                flex h-16
                max-w-3xl
                items-center
                gap-3
                px-4
            ">

            <a href="{{ route('pendaki.dashboard') }}"
                class="
                    flex h-9 w-9
                    items-center justify-center
                    rounded-lg
                    hover:bg-brand-dark/5
                ">

                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />

                </svg>

            </a>


            <div>

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-wide
                        text-brand-dark/40
                    ">
                    Informasi Gunung
                </p>

                <h1 class="text-sm font-extrabold">
                    {{ $mountain->name }}
                </h1>

            </div>

        </div>

    </header>


    <main class="
            mx-auto
            max-w-3xl
            space-y-6
            px-4 pt-5
        ">


        {{-- HERO --}}
        <section
            class="
                relative
                overflow-hidden
                rounded-2xl
                bg-brand-dark
            ">

            @if ($mountain->cover_image_url)
                <img src="{{ $mountain->cover_image_url }}" alt="{{ $mountain->name }}"
                    class="
                        h-56 w-full
                        object-cover
                        sm:h-72
                    ">

                <div
                    class="
                        absolute inset-0
                        bg-gradient-to-t
                        from-black/80
                        via-black/20
                        to-transparent
                    ">
                </div>
            @else
                <div
                    class="
                        h-56
                        bg-brand-dark
                        sm:h-72
                    ">
                </div>
            @endif


            <div
                class="
                    absolute
                    inset-x-0 bottom-0
                    p-6
                    text-white
                ">

                <h2
                    class="
                        text-2xl
                        font-extrabold
                        tracking-tight
                    ">
                    {{ $mountain->name }}
                </h2>


                <div
                    class="
                        mt-2
                        flex flex-wrap
                        items-center
                        gap-3
                        text-xs
                        text-white/75
                    ">

                    @if ($mountain->location)
                        <span>
                            {{ $mountain->location }}
                        </span>
                    @endif


                    @if ($mountain->elevation_m)
                        <span>
                            {{ number_format($mountain->elevation_m, 0, ',', '.') }}
                            mdpl
                        </span>
                    @endif

                </div>

            </div>

        </section>


        {{-- DESCRIPTION --}}
        @if ($mountain->description)
            <section>

                <h2 class="text-base font-extrabold">
                    Tentang Gunung
                </h2>

                <p
                    class="
                        mt-2
                        text-sm
                        leading-7
                        text-brand-dark/65
                    ">
                    {{ $mountain->description }}
                </p>

            </section>
        @endif


        {{-- TRAILS --}}
        <section>

            <div class="mb-4">

                <h2 class="text-base font-extrabold">
                    Jalur Pendakian
                </h2>

                <p
                    class="
                        mt-1
                        text-xs
                        text-brand-dark/45
                    ">
                    Pilih jalur pendakian yang tersedia.
                </p>

            </div>


            @forelse (
                $mountain->hikingTrails
                as $trail
            )

                <article
                    class="
                        mb-4
                        rounded-2xl
                        border border-brand-dark/10
                        bg-white
                        p-5
                        shadow-sm
                    ">

                    <div
                        class="
                            flex
                            items-start
                            justify-between
                            gap-3
                        ">

                        <div>

                            <h3
                                class="
                                    text-sm
                                    font-extrabold
                                ">
                                {{ $trail->name }}
                            </h3>


                            @if ($trail->difficulty)
                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-brand-dark/45
                                    ">
                                    Tingkat kesulitan:
                                    {{ ucfirst($trail->difficulty) }}
                                </p>
                            @endif

                        </div>


                        <span
                            class="
                                rounded-lg
                                bg-brand-orange/10
                                px-2.5 py-1
                                text-[10px]
                                font-bold
                                text-brand-orange
                            ">
                            {{ $trail->checkpoints_count }}
                            checkpoint
                        </span>

                    </div>


                    <div
                        class="
                            mt-4
                            grid grid-cols-2
                            gap-3
                        ">

                        {{-- DISTANCE --}}
                        <div
                            class="
                                rounded-xl
                                bg-brand-cream
                                p-3
                            ">

                            <p
                                class="
                                    text-[10px]
                                    text-brand-dark/40
                                ">
                                Jarak
                            </p>

                            <p
                                class="
                                    mt-1
                                    text-sm
                                    font-bold
                                ">
                                {{ $trail->distance_km ? number_format($trail->distance_km, 1, ',', '.') . ' km' : '-' }}
                            </p>

                        </div>


                        {{-- TIME --}}
                        <div
                            class="
                                rounded-xl
                                bg-brand-cream
                                p-3
                            ">

                            <p
                                class="
                                    text-[10px]
                                    text-brand-dark/40
                                ">
                                Estimasi
                            </p>

                            <p
                                class="
                                    mt-1
                                    text-sm
                                    font-bold
                                ">
                                {{ $trail->estimated_time_hours ? $trail->estimated_time_hours . ' jam' : '-' }}
                            </p>

                        </div>

                    </div>


                    @if ($trail->checkpoints->isNotEmpty())
                        <div
                            class="
                                mt-5
                                border-t
                                border-brand-dark/10
                                pt-4
                            ">

                            <p
                                class="
                                    mb-3
                                    text-xs
                                    font-bold
                                ">
                                Checkpoint
                            </p>


                            <div class="space-y-3">

                                @foreach ($trail->checkpoints as $checkpoint)
                                    <div
                                        class="
                                            flex
                                            items-center
                                            gap-3
                                        ">

                                        <span
                                            class="
                                                flex h-7 w-7
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-brand-dark
                                                text-[10px]
                                                font-bold
                                                text-white
                                            ">
                                            {{ $loop->iteration }}
                                        </span>


                                        <div>

                                            <p
                                                class="
                                                    text-xs
                                                    font-semibold
                                                ">
                                                {{ $checkpoint->name }}
                                            </p>


                                            <p
                                                class="
                                                    mt-0.5
                                                    text-[10px]
                                                    text-brand-dark/40
                                                ">

                                                {{ $checkpoint->type ?? 'Checkpoint' }}

                                                @if ($checkpoint->elevation_m)
                                                    ·
                                                    {{ number_format($checkpoint->elevation_m, 0, ',', '.') }}
                                                    mdpl
                                                @endif

                                            </p>

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>
                    @endif


                    <a href="{{ route('pendaki.trail.show', $trail) }}"
                        class="
                        mt-5
                        flex h-11
                        w-full
                        items-center
                        justify-center
                        rounded-xl
                        bg-brand-dark
                        text-xs
                        font-bold
                        text-white
                        transition
                        hover:bg-brand-dark/90
                    ">
                        Lihat Peta Jalur
                    </a>

                </article>

            @empty

                <div
                    class="
                        rounded-2xl
                        border border-dashed
                        border-brand-dark/15
                        bg-white
                        px-5 py-10
                        text-center
                    ">

                    <p class="text-sm font-bold">
                        Belum ada jalur aktif
                    </p>

                    <p
                        class="
                            mt-1
                            text-xs
                            text-brand-dark/45
                        ">
                        Jalur pendakian untuk gunung ini
                        belum tersedia.
                    </p>

                </div>

            @endforelse

        </section>

    </main>


    @include('pendaki.components.bottom-nav', [
        'active' => 'dashboard',
    ])

</body>

</html>
