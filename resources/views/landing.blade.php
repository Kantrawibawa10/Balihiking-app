<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Jalur Bali - Sistem Informasi Pendakian Gunung Bali
    </title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine --}}
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
    ></script>

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        jalur: {
                            green: '#173D32',
                            greenSoft: '#235345',
                            orange: '#E96B3C',
                            cream: '#F7F8F6',
                            text: '#17211D',
                            muted: '#68736D',
                        }
                    },

                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body
    class="bg-jalur-cream text-jalur-text antialiased"
    x-data="{ mobileMenuOpen: false }"
>


    {{--
    |--------------------------------------------------------------------------
    | NAVBAR
    |--------------------------------------------------------------------------
    --}}

    <header
        class="sticky top-0 z-50 border-b border-black/5 bg-white/90 backdrop-blur-md"
    >

        <div
            class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10"
        >

            <div
                class="flex h-16 items-center justify-between sm:h-[72px]"
            >

                {{-- LOGO --}}
                <a
                    href="{{ route('home') }}"
                    class="flex items-center gap-2.5"
                >

                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-jalur-green text-white"
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
                                d="M3 20h18L15 6l-4 7-2-3-6 10Z"
                            />
                        </svg>

                    </span>

                    <span
                        class="text-base font-semibold tracking-tight text-jalur-green sm:text-lg"
                    >
                        Jalur Bali
                    </span>

                </a>


                {{-- DESKTOP --}}
                <nav
                    class="hidden items-center gap-7 md:flex"
                >

                    <a
                        href="#features"
                        class="text-sm font-medium text-gray-600 transition hover:text-jalur-green"
                    >
                        Fitur
                    </a>


                    @auth

                        @if (auth()->user()->role === 'admin')

                            <a
                                href="/admin"
                                class="text-sm font-medium text-gray-600 transition hover:text-jalur-green"
                            >
                                Admin
                            </a>

                        @else

                            <a
                                href="{{ route('pendaki.dashboard') }}"
                                class="text-sm font-medium text-gray-600 transition hover:text-jalur-green"
                            >
                                Dashboard
                            </a>

                        @endif


                        <div
                            class="flex items-center gap-2"
                        >

                            <div
                                class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full border border-gray-200 bg-gray-100"
                            >

                                @if (auth()->user()->avatar)

                                    <img
                                        src="{{ auth()->user()->avatar }}"
                                        alt="{{ auth()->user()->name }}"
                                        class="h-full w-full object-cover"
                                    >

                                @else

                                    <svg
                                        class="h-4 w-4 text-gray-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.5 20.25a7.5 7.5 0 0115 0"
                                        />
                                    </svg>

                                @endif

                            </div>

                            <span
                                class="max-w-[130px] truncate text-sm font-medium text-gray-700"
                            >
                                {{ auth()->user()->name }}
                            </span>

                        </div>


                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-xs font-medium text-gray-600 transition hover:bg-gray-50"
                            >
                                Keluar
                            </button>

                        </form>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="text-sm font-medium text-gray-600 transition hover:text-jalur-green"
                        >
                            Masuk
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="rounded-lg bg-jalur-green px-5 py-2.5 text-sm font-medium text-white transition hover:bg-jalur-greenSoft"
                        >
                            Daftar
                        </a>

                    @endauth

                </nav>


                {{-- MOBILE BUTTON --}}
                <button
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-lg text-jalur-green transition hover:bg-gray-100 md:hidden"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    aria-label="Menu"
                >

                    <svg
                        x-show="!mobileMenuOpen"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>


                    <svg
                        x-show="mobileMenuOpen"
                        x-cloak
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18 18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>

        </div>


        {{--
        |--------------------------------------------------------------------------
        | MOBILE MENU
        |--------------------------------------------------------------------------
        --}}

        <div
            x-show="mobileMenuOpen"
            x-cloak
            x-transition
            class="border-t border-gray-100 bg-white px-5 py-5 md:hidden"
        >

            <div
                class="mx-auto max-w-md space-y-3"
            >

                <a
                    href="#features"
                    @click="mobileMenuOpen = false"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Fitur
                </a>


                @auth

                    @if (auth()->user()->role === 'admin')

                        <a
                            href="/admin"
                            class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Portal Admin
                        </a>

                    @else

                        <a
                            href="{{ route('pendaki.dashboard') }}"
                            class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Dashboard
                        </a>

                    @endif


                    <div
                        class="flex items-center gap-3 border-y border-gray-100 py-4"
                    >

                        <div
                            class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-full bg-gray-100"
                        >

                            @if (auth()->user()->avatar)

                                <img
                                    src="{{ auth()->user()->avatar }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="h-full w-full object-cover"
                                >

                            @else

                                <svg
                                    class="h-4 w-4 text-gray-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.5 20.25a7.5 7.5 0 0115 0"
                                    />
                                </svg>

                            @endif

                        </div>

                        <div>

                            <p
                                class="text-sm font-medium text-gray-800"
                            >
                                {{ auth()->user()->name }}
                            </p>

                            <p
                                class="text-xs text-gray-400"
                            >
                                {{ ucfirst(auth()->user()->role ?? 'user') }}
                            </p>

                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-lg border border-gray-300 py-2.5 text-sm font-medium text-gray-700"
                        >
                            Keluar
                        </button>

                    </form>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="block w-full rounded-lg border border-gray-300 py-2.5 text-center text-sm font-medium text-gray-700"
                    >
                        Masuk
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="block w-full rounded-lg bg-jalur-green py-2.5 text-center text-sm font-medium text-white"
                    >
                        Daftar
                    </a>

                @endauth

            </div>

        </div>

    </header>


    {{--
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    --}}

    <section
        class="relative min-h-[620px] overflow-hidden sm:min-h-[680px] lg:min-h-[720px]"
    >

        {{-- IMAGE --}}
        <div class="absolute inset-0">

            <img
                src="https://images.unsplash.com/photo-1595732194638-81889370ddb0?q=85&w=2000&auto=format&fit=crop"
                alt="Gunung di Bali"
                class="h-full w-full object-cover"
            >

            <div
                class="absolute inset-0 bg-black/45"
            ></div>

            <div
                class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-jalur-cream to-transparent"
            ></div>

        </div>


        <div
            class="relative z-10 mx-auto flex min-h-[620px] max-w-7xl items-center px-5 py-16 sm:min-h-[680px] sm:px-8 lg:min-h-[720px] lg:px-10"
        >

            <div class="max-w-2xl">

                <div
                    class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-black/10 px-3 py-1.5 text-xs font-medium text-white/90 backdrop-blur-sm"
                >

                    <span
                        class="h-1.5 w-1.5 rounded-full bg-jalur-orange"
                    ></span>

                    Sistem Informasi Pendakian Gunung Bali

                </div>


                <h1
                    class="max-w-xl text-4xl font-semibold leading-[1.12] tracking-tight text-white sm:text-5xl lg:text-6xl"
                >
                    Pendakian lebih mudah,
                    aman, dan terpantau.
                </h1>


                <p
                    class="mt-5 max-w-xl text-sm leading-7 text-white/80 sm:text-base"
                >
                    Akses informasi jalur pendakian, pantau perjalanan
                    secara langsung, dan gunakan fitur keselamatan ketika
                    mendaki gunung di Bali.
                </p>


                <div
                    class="mt-8 flex flex-col gap-3 sm:flex-row"
                >

                    @guest

                        <a
                            href="{{ route('register') }}"
                            class="inline-flex h-12 items-center justify-center rounded-lg bg-jalur-orange px-6 text-sm font-semibold text-white transition hover:bg-[#d96035]"
                        >
                            Mulai sekarang
                        </a>

                    @else

                        @if (auth()->user()->role === 'admin')

                            <a
                                href="/admin"
                                class="inline-flex h-12 items-center justify-center rounded-lg bg-jalur-orange px-6 text-sm font-semibold text-white transition hover:bg-[#d96035]"
                            >
                                Buka Portal Admin
                            </a>

                        @else

                            <a
                                href="{{ route('pendaki.dashboard') }}"
                                class="inline-flex h-12 items-center justify-center rounded-lg bg-jalur-orange px-6 text-sm font-semibold text-white transition hover:bg-[#d96035]"
                            >
                                Buka Dashboard
                            </a>

                        @endif

                    @endguest


                    <a
                        href="#features"
                        class="inline-flex h-12 items-center justify-center rounded-lg border border-white/30 bg-white/10 px-6 text-sm font-medium text-white backdrop-blur-sm transition hover:bg-white/15"
                    >
                        Lihat fitur
                    </a>

                </div>

            </div>

        </div>

    </section>


    {{--
    |--------------------------------------------------------------------------
    | STATS
    |--------------------------------------------------------------------------
    --}}

    <section class="bg-jalur-cream pb-16">

        <div
            class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10"
        >

            <div
                class="grid grid-cols-2 border-y border-black/10 md:grid-cols-4"
            >

                <div
                    class="border-b border-r border-black/10 px-4 py-7 sm:px-6 md:border-b-0"
                >
                    <p
                        class="text-2xl font-semibold tracking-tight text-jalur-green sm:text-3xl"
                    >
                        3
                    </p>

                    <p
                        class="mt-1 text-xs text-gray-500"
                    >
                        Gunung utama
                    </p>
                </div>


                <div
                    class="border-b border-black/10 px-4 py-7 sm:px-6 md:border-b-0 md:border-r"
                >
                    <p
                        class="text-2xl font-semibold tracking-tight text-jalur-green sm:text-3xl"
                    >
                        10+
                    </p>

                    <p
                        class="mt-1 text-xs text-gray-500"
                    >
                        Checkpoint
                    </p>
                </div>


                <div
                    class="border-r border-black/10 px-4 py-7 sm:px-6"
                >
                    <p
                        class="text-2xl font-semibold tracking-tight text-jalur-green sm:text-3xl"
                    >
                        24/7
                    </p>

                    <p
                        class="mt-1 text-xs text-gray-500"
                    >
                        Pemantauan SOS
                    </p>
                </div>


                <div
                    class="px-4 py-7 sm:px-6"
                >
                    <p
                        class="text-2xl font-semibold tracking-tight text-jalur-green sm:text-3xl"
                    >
                        PWA
                    </p>

                    <p
                        class="mt-1 text-xs text-gray-500"
                    >
                        Mendukung offline
                    </p>
                </div>

            </div>

        </div>

    </section>


    {{--
    |--------------------------------------------------------------------------
    | FEATURES
    |--------------------------------------------------------------------------
    --}}

    <section
        id="features"
        class="bg-jalur-cream py-14 sm:py-20"
    >

        <div
            class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10"
        >

            <div
                class="mb-10 max-w-2xl"
            >

                <p
                    class="text-sm font-medium text-jalur-orange"
                >
                    Fitur utama
                </p>

                <h2
                    class="mt-2 text-3xl font-semibold tracking-tight text-jalur-green sm:text-4xl"
                >
                    Informasi dan keselamatan
                    dalam satu sistem.
                </h2>

                <p
                    class="mt-4 text-sm leading-6 text-gray-500 sm:text-base"
                >
                    Fitur utama dirancang untuk membantu pendaki
                    mendapatkan informasi dan melakukan pemantauan perjalanan.
                </p>

            </div>


            <div
                class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
            >


                {{-- CARD --}}
                <article
                    class="rounded-xl border border-black/5 bg-white p-6 transition hover:border-black/10"
                >

                    <div
                        class="mb-5 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-jalur-orange"
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 20 3.553 17.276A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13 6-3m-6 3V7m6 10 4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4M15 4 9 7"
                            />
                        </svg>

                    </div>

                    <h3
                        class="text-base font-semibold text-jalur-green"
                    >
                        Peta Jalur Digital
                    </h3>

                    <p
                        class="mt-2 text-sm leading-6 text-gray-500"
                    >
                        Informasi jalur pendakian, checkpoint, shelter,
                        dan titik penting selama perjalanan.
                    </p>

                </article>


                <article
                    class="rounded-xl border border-black/5 bg-white p-6 transition hover:border-black/10"
                >

                    <div
                        class="mb-5 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-jalur-orange"
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 18.75a6.75 6.75 0 100-13.5 6.75 6.75 0 000 13.5Z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v3.75l2.25 1.5"
                            />
                        </svg>

                    </div>

                    <h3
                        class="text-base font-semibold text-jalur-green"
                    >
                        Live Tracking
                    </h3>

                    <p
                        class="mt-2 text-sm leading-6 text-gray-500"
                    >
                        Posisi pendaki dapat dipantau secara berkala
                        selama proses pendakian berlangsung.
                    </p>

                </article>


                <article
                    class="rounded-xl border border-black/5 bg-white p-6 transition hover:border-black/10"
                >

                    <div
                        class="mb-5 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-jalur-orange"
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M18.364 5.636a9 9 0 010 12.728M15.536 8.464a5 5 0 010 7.072M8.464 15.536a5 5 0 010-7.072M5.636 18.364a9 9 0 010-12.728"
                            />
                        </svg>

                    </div>

                    <h3
                        class="text-base font-semibold text-jalur-green"
                    >
                        Sinkronisasi Offline
                    </h3>

                    <p
                        class="mt-2 text-sm leading-6 text-gray-500"
                    >
                        Data perjalanan dapat disimpan sementara ketika
                        koneksi internet tidak tersedia.
                    </p>

                </article>


                <article
                    class="rounded-xl border border-black/5 bg-white p-6 transition hover:border-black/10"
                >

                    <div
                        class="mb-5 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-jalur-orange"
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                            />
                        </svg>

                    </div>

                    <h3
                        class="text-base font-semibold text-jalur-green"
                    >
                        Tombol SOS
                    </h3>

                    <p
                        class="mt-2 text-sm leading-6 text-gray-500"
                    >
                        Pendaki dapat mengirim informasi keadaan darurat
                        beserta posisi terakhir yang tersedia.
                    </p>

                </article>


                <article
                    class="rounded-xl border border-black/5 bg-white p-6 transition hover:border-black/10"
                >

                    <div
                        class="mb-5 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-jalur-orange"
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72M18 18.72a9.094 9.094 0 01-6 0m6 0v-.75a6 6 0 00-6-6m0 6.75v-.75a6 6 0 016-6m-6 6.75a9.094 9.094 0 01-6 0m6 0v-.75a6 6 0 00-6-6m0 6.75a9.094 9.094 0 01-3.741-.479 3 3 0 014.682-2.72"
                            />
                        </svg>

                    </div>

                    <h3
                        class="text-base font-semibold text-jalur-green"
                    >
                        Hak Akses Pengguna
                    </h3>

                    <p
                        class="mt-2 text-sm leading-6 text-gray-500"
                    >
                        Sistem menyediakan hak akses yang berbeda
                        bagi admin, pemandu, dan pendaki.
                    </p>

                </article>


                <article
                    class="rounded-xl border border-black/5 bg-white p-6 transition hover:border-black/10"
                >

                    <div
                        class="mb-5 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-jalur-orange"
                    >

                        <svg
                            class="h-5 w-5"
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
                        class="text-base font-semibold text-jalur-green"
                    >
                        Informasi Gunung Bali
                    </h3>

                    <p
                        class="mt-2 text-sm leading-6 text-gray-500"
                    >
                        Informasi Gunung Agung, Batur, Batukaru,
                        serta jalur pendakian yang tersedia.
                    </p>

                </article>

            </div>

        </div>

    </section>


    {{--
    |--------------------------------------------------------------------------
    | CTA
    |--------------------------------------------------------------------------
    --}}

    <section
        class="bg-jalur-cream py-12 sm:py-16"
    >

        <div
            class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10"
        >

            <div
                class="overflow-hidden rounded-2xl bg-jalur-green px-6 py-10 sm:px-10 sm:py-12 lg:flex lg:items-center lg:justify-between"
            >

                <div class="max-w-xl">

                    <h2
                        class="text-2xl font-semibold tracking-tight text-white sm:text-3xl"
                    >
                        Siap memulai perjalanan?
                    </h2>

                    <p
                        class="mt-3 text-sm leading-6 text-white/70"
                    >
                        Buat akun pendaki untuk mengakses informasi jalur,
                        tracking perjalanan, dan fitur keselamatan.
                    </p>

                </div>


                <div class="mt-7 lg:mt-0">

                    @guest

                        <a
                            href="{{ route('register') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg bg-jalur-orange px-6 text-sm font-semibold text-white transition hover:bg-[#d96035]"
                        >
                            Daftar sekarang
                        </a>

                    @else

                        @if (auth()->user()->role === 'admin')

                            <a
                                href="/admin"
                                class="inline-flex h-11 items-center justify-center rounded-lg bg-jalur-orange px-6 text-sm font-semibold text-white"
                            >
                                Buka Admin
                            </a>

                        @else

                            <a
                                href="{{ route('pendaki.dashboard') }}"
                                class="inline-flex h-11 items-center justify-center rounded-lg bg-jalur-orange px-6 text-sm font-semibold text-white"
                            >
                                Dashboard
                            </a>

                        @endif

                    @endguest

                </div>

            </div>

        </div>

    </section>


    {{--
    |--------------------------------------------------------------------------
    | FOOTER
    |--------------------------------------------------------------------------
    --}}

    <footer
        class="border-t border-black/5 bg-jalur-cream"
    >

        <div
            class="mx-auto flex max-w-7xl flex-col gap-4 px-5 py-7 sm:flex-row sm:items-center sm:justify-between sm:px-8 lg:px-10"
        >

            <div
                class="flex items-center gap-2"
            >

                <span
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-jalur-green text-white"
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
                            d="M3 20h18L15 6l-4 7-2-3-6 10Z"
                        />
                    </svg>

                </span>

                <span
                    class="text-sm font-semibold text-jalur-green"
                >
                    Jalur Bali
                </span>

            </div>


            <p
                class="text-xs text-gray-400"
            >
                © {{ date('Y') }} Sistem Informasi Pendakian Gunung Bali
            </p>

        </div>

    </footer>

</body>

</html>