<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover"
    >

    <title>Daftar Akun - BaliHiking</title>

    <meta
        name="description"
        content="BaliHiking - Sistem Informasi Pendakian Gunung Bali"
    >

    <meta
        name="theme-color"
        content="#173D32"
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
        sizes="192x192"
        href="{{ asset('icons/icon-192.png') }}"
    >


    {{-- ========================================================= --}}
    {{-- TAILWIND --}}
    {{-- ========================================================= --}}

    <script src="https://cdn.tailwindcss.com"></script>


    {{-- ========================================================= --}}
    {{-- GOOGLE FONT --}}
    {{-- ========================================================= --}}

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
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    {{-- ========================================================= --}}
    {{-- TAILWIND CONFIG --}}
    {{-- ========================================================= --}}

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        jalur: {
                            green: '#173D32',
                            greenLight: '#235345',
                            orange: '#E96B3C',
                            background: '#F6F7F5',
                            text: '#17211D',
                            muted: '#6B756F',
                        }
                    },

                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        };
    </script>


    {{-- ========================================================= --}}
    {{-- GLOBAL STYLE --}}
    {{-- ========================================================= --}}

    <style>
        html {
            background: #F6F7F5;
        }

        body {
            font-family:
                'Inter',
                -apple-system,
                BlinkMacSystemFont,
                'Segoe UI',
                sans-serif;
        }


        /*
        |--------------------------------------------------------------------------
        | PWA INSTALL BACKDROP
        |--------------------------------------------------------------------------
        */

        #pwa-install-backdrop {
            position: fixed;
            inset: 0;
            z-index: 99990;

            background:
                rgba(0, 0, 0, .38);

            backdrop-filter:
                blur(3px);

            -webkit-backdrop-filter:
                blur(3px);

            opacity: 0;

            visibility:
                hidden;

            transition:
                opacity .25s ease,
                visibility .25s ease;
        }

        #pwa-install-backdrop.show {
            opacity: 1;
            visibility: visible;
        }


        /*
        |--------------------------------------------------------------------------
        | PWA INSTALL POPUP
        |--------------------------------------------------------------------------
        */

        #pwa-install-popup {
            position: fixed;

            left: 50%;

            bottom:
                calc(
                    24px +
                    env(safe-area-inset-bottom)
                );

            z-index: 99999;

            width:
                calc(100% - 32px);

            max-width: 430px;

            transform:
                translate(
                    -50%,
                    140%
                );

            opacity: 0;

            visibility:
                hidden;

            overflow: hidden;

            background:
                #ffffff;

            border:
                1px solid
                rgba(23, 61, 50, .10);

            border-radius:
                22px;

            box-shadow:
                0 28px 70px
                rgba(0, 0, 0, .22);

            transition:
                transform .32s
                    cubic-bezier(.2, .8, .2, 1),
                opacity .25s ease,
                visibility .25s ease;
        }

        #pwa-install-popup.show {
            transform:
                translate(
                    -50%,
                    0
                );

            opacity: 1;

            visibility:
                visible;
        }


        /*
        |--------------------------------------------------------------------------
        | PWA HEADER
        |--------------------------------------------------------------------------
        */

        .pwa-install-header {
            display: flex;

            align-items:
                flex-start;

            gap: 14px;

            padding:
                20px
                20px
                16px;
        }

        .pwa-install-icon {
            width: 58px;
            height: 58px;

            flex:
                0 0 58px;

            overflow: hidden;

            border-radius:
                15px;

            background:
                #173D32;

            box-shadow:
                0 5px 16px
                rgba(23, 61, 50, .18);
        }

        .pwa-install-icon img {
            display: block;

            width: 100%;
            height: 100%;

            object-fit:
                cover;
        }

        .pwa-install-content {
            min-width: 0;
            flex: 1;
        }

        .pwa-install-title {
            margin:
                2px
                0
                5px;

            color:
                #173D32;

            font-size:
                16px;

            line-height:
                1.35;

            font-weight:
                700;
        }

        .pwa-install-description {
            color:
                #68736D;

            font-size:
                12px;

            line-height:
                1.6;
        }

        .pwa-install-close {
            width: 34px;
            height: 34px;

            flex:
                0 0 34px;

            display: flex;

            align-items:
                center;

            justify-content:
                center;

            padding: 0;

            border: 0;

            border-radius:
                10px;

            color:
                #68736D;

            background:
                #F4F5F4;

            cursor: pointer;

            transition:
                background .2s ease;
        }

        .pwa-install-close:hover {
            background:
                #EDEFEA;
        }

        .pwa-install-close svg {
            width: 17px;
            height: 17px;
        }


        /*
        |--------------------------------------------------------------------------
        | PWA FEATURES
        |--------------------------------------------------------------------------
        */

        .pwa-install-features {
            display: grid;

            grid-template-columns:
                repeat(
                    3,
                    1fr
                );

            gap: 8px;

            padding:
                0
                20px
                16px;
        }

        .pwa-install-feature {
            padding:
                11px
                8px;

            text-align:
                center;

            border-radius:
                12px;

            background:
                #F7F8F6;
        }

        .pwa-install-feature svg {
            width: 18px;
            height: 18px;

            margin:
                0
                auto
                6px;

            color:
                #E96B3C;
        }

        .pwa-install-feature span {
            display: block;

            color:
                #56615B;

            font-size:
                9px;

            line-height:
                1.3;

            font-weight:
                600;
        }


        /*
        |--------------------------------------------------------------------------
        | IOS INFO
        |--------------------------------------------------------------------------
        */

        #pwa-ios-info {
            display: none;

            margin:
                0
                20px
                18px;

            padding:
                12px
                14px;

            border-radius:
                12px;

            color:
                #59645F;

            background:
                #F7F8F6;

            font-size:
                11px;

            line-height:
                1.6;
        }

        #pwa-ios-info.show {
            display: block;
        }


        /*
        |--------------------------------------------------------------------------
        | INSTALL STATUS
        |--------------------------------------------------------------------------
        */

        #pwa-install-status {
            display: none;

            margin:
                0
                20px
                16px;

            padding:
                10px
                12px;

            border-radius:
                10px;

            font-size:
                11px;

            line-height:
                1.5;

            color:
                #6B756F;

            background:
                #F7F8F6;
        }

        #pwa-install-status.show {
            display: block;
        }


        /*
        |--------------------------------------------------------------------------
        | ACTIONS
        |--------------------------------------------------------------------------
        */

        .pwa-install-actions {
            display: flex;

            gap: 9px;

            padding:
                14px
                20px
                20px;

            border-top:
                1px solid
                #EFF1EF;
        }

        .pwa-install-later {
            height: 44px;

            flex:
                0 0 auto;

            padding:
                0 18px;

            border:
                1px solid
                #D9DEDB;

            border-radius:
                11px;

            color:
                #68736D;

            background:
                white;

            font-size:
                12px;

            font-weight:
                600;

            cursor: pointer;
        }

        .pwa-install-button {
            height: 44px;

            flex: 1;

            display: flex;

            align-items:
                center;

            justify-content:
                center;

            gap: 8px;

            padding:
                0 18px;

            border: 0;

            border-radius:
                11px;

            color:
                white;

            background:
                #173D32;

            font-size:
                12px;

            font-weight:
                700;

            cursor: pointer;

            transition:
                background .2s ease,
                transform .15s ease;
        }

        .pwa-install-button:hover {
            background:
                #235345;
        }

        .pwa-install-button:active {
            transform:
                scale(.98);
        }

        .pwa-install-button:disabled {
            opacity: .65;

            cursor:
                not-allowed;
        }

        .pwa-install-button svg {
            width: 17px;
            height: 17px;
        }


        /*
        |--------------------------------------------------------------------------
        | DESKTOP POPUP
        |--------------------------------------------------------------------------
        */

        @media (
            min-width: 768px
        ) {

            #pwa-install-popup {
                left: auto;

                right: 28px;

                bottom: 28px;

                width: 390px;

                transform:
                    translateY(
                        130%
                    );
            }

            #pwa-install-popup.show {
                transform:
                    translateY(
                        0
                    );
            }

            #pwa-install-backdrop {
                display: none;
            }

        }

    </style>

</head>


<body
    class="bg-jalur-background text-jalur-text antialiased"
>


    <main
        class="min-h-screen lg:grid lg:grid-cols-2"
    >


        {{--
        |--------------------------------------------------------------------------
        | LEFT SIDE - DESKTOP
        |--------------------------------------------------------------------------
        --}}

        <section
            class="relative hidden min-h-screen overflow-hidden lg:block"
        >


            {{-- BACKGROUND --}}

            <img
                src="https://images.unsplash.com/photo-1593332956867-9d99a3bb9109?q=85&w=1600&auto=format&fit=crop"
                alt="Pendakian gunung di Bali"
                class="absolute inset-0 h-full w-full object-cover"
            >


            {{-- OVERLAY --}}

            <div
                class="absolute inset-0 bg-black/35"
            ></div>


            <div
                class="absolute inset-x-0 bottom-0 h-[45%] bg-gradient-to-t from-black/60 to-transparent"
            ></div>


            {{-- BRAND --}}

            <div
                class="absolute left-0 right-0 top-0 z-10 p-10 xl:p-12"
            >

                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-3 text-white"
                >

                    <span
                        class="h-11 w-11 overflow-hidden rounded-xl border border-white/20 bg-white/10 shadow-sm backdrop-blur-sm"
                    >

                        <img
                            src="{{ asset('icons/icon-192.png') }}"
                            alt="BaliHiking"
                            class="h-full w-full object-cover"
                        >

                    </span>


                    <span
                        class="text-lg font-semibold"
                    >
                        BaliHiking
                    </span>

                </a>

            </div>



            {{-- DESCRIPTION --}}

            <div
                class="absolute bottom-0 left-0 right-0 z-10 p-10 xl:p-12"
            >

                <div
                    class="max-w-lg"
                >

                    <p
                        class="mb-3 text-sm font-medium text-white/80"
                    >
                        Portal Pendaki BaliHiking
                    </p>


                    <h1
                        class="text-4xl font-semibold leading-tight text-white xl:text-5xl"
                    >
                        Mulai perjalanan
                        <br>
                        pendakian Anda.
                    </h1>


                    <p
                        class="mt-5 max-w-md text-sm leading-6 text-white/80"
                    >
                        Daftar untuk mengakses informasi jalur, SIMAKSI,
                        live tracking, dan fitur pendukung keselamatan
                        pendakian bersama BaliHiking.
                    </p>

                </div>

            </div>

        </section>



        {{--
        |--------------------------------------------------------------------------
        | RIGHT SIDE
        |--------------------------------------------------------------------------
        --}}

        <section
            class="flex min-h-screen flex-col bg-white lg:bg-jalur-background"
        >


            {{--
            |--------------------------------------------------------------------------
            | MOBILE HEADER
            |--------------------------------------------------------------------------
            --}}

            <header
                class="border-b border-gray-100 bg-white px-5 py-4 lg:hidden"
            >

                <div
                    class="mx-auto flex max-w-md items-center justify-between"
                >

                    <a
                        href="{{ route('home') }}"
                        class="flex items-center gap-2.5"
                    >

                        <span
                            class="h-9 w-9 overflow-hidden rounded-lg bg-jalur-green"
                        >

                            <img
                                src="{{ asset('icons/icon-192.png') }}"
                                alt="BaliHiking"
                                class="h-full w-full object-cover"
                            >

                        </span>


                        <span
                            class="font-semibold text-jalur-green"
                        >
                            BaliHiking
                        </span>

                    </a>


                    <a
                        href="{{ route('home') }}"
                        class="text-sm font-medium text-gray-500 transition hover:text-jalur-green"
                    >
                        Beranda
                    </a>

                </div>

            </header>



            {{--
            |--------------------------------------------------------------------------
            | FORM WRAPPER
            |--------------------------------------------------------------------------
            --}}

            <div
                class="flex flex-1 items-center justify-center px-5 py-8 sm:px-8 lg:px-12 xl:px-20"
            >

                <div
                    class="w-full max-w-[430px]"
                >


                    {{-- DESKTOP BRAND --}}

                    <div
                        class="mb-8 hidden lg:block"
                    >

                        <a
                            href="{{ route('home') }}"
                            class="inline-flex items-center gap-2.5"
                        >

                            <span
                                class="h-10 w-10 overflow-hidden rounded-lg bg-jalur-green"
                            >

                                <img
                                    src="{{ asset('icons/icon-192.png') }}"
                                    alt="BaliHiking"
                                    class="h-full w-full object-cover"
                                >

                            </span>


                            <span
                                class="font-semibold text-jalur-green"
                            >
                                BaliHiking
                            </span>

                        </a>

                    </div>



                    {{--
                    |--------------------------------------------------------------------------
                    | TITLE
                    |--------------------------------------------------------------------------
                    --}}

                    <div
                        class="mb-6"
                    >

                        <h2
                            class="text-2xl font-semibold tracking-tight text-gray-900 sm:text-[28px]"
                        >
                            Buat akun pendaki
                        </h2>


                        <p
                            class="mt-2 text-sm leading-6 text-gray-500"
                        >
                            Lengkapi data berikut untuk membuat akun BaliHiking.
                        </p>

                    </div>



                    {{--
                    |--------------------------------------------------------------------------
                    | GLOBAL ERROR
                    |--------------------------------------------------------------------------
                    --}}

                    @if ($errors->any())

                        <div
                            class="mb-5 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3"
                        >

                            <svg
                                class="mt-0.5 h-5 w-5 shrink-0 text-red-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0Zm-9 3.75h.008v.008H12v-.008Z"
                                />

                            </svg>


                            <div>

                                <p
                                    class="text-sm font-medium text-red-700"
                                >
                                    Pendaftaran belum berhasil.
                                </p>


                                <p
                                    class="mt-1 text-xs text-red-600"
                                >
                                    Silakan periksa kembali data yang Anda masukkan.
                                </p>

                            </div>

                        </div>

                    @endif



                    {{--
                    |--------------------------------------------------------------------------
                    | GOOGLE REGISTER
                    |--------------------------------------------------------------------------
                    --}}

                    <a
                        href="{{ route('pendaki.google.redirect') }}"
                        class="flex h-12 w-full items-center justify-center gap-3 rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 transition hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-jalur-green/20"
                    >

                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                        >

                            <path
                                fill="#4285F4"
                                d="M21.6 12.23c0-.71-.06-1.4-.18-2.07H12v3.92h5.38a4.6 4.6 0 0 1-2 3.02v2.54h3.24c1.9-1.75 2.98-4.33 2.98-7.41Z"
                            />

                            <path
                                fill="#34A853"
                                d="M12 22c2.7 0 4.97-.9 6.63-2.36l-3.24-2.54c-.9.6-2.05.96-3.39.96-2.6 0-4.8-1.75-5.59-4.11H3.06v2.62A10 10 0 0 0 12 22Z"
                            />

                            <path
                                fill="#FBBC05"
                                d="M6.41 13.95A6 6 0 0 1 6.1 12c0-.68.12-1.34.31-1.95V7.43H3.06A10 10 0 0 0 2 12c0 1.61.39 3.14 1.06 4.57l3.35-2.62Z"
                            />

                            <path
                                fill="#EA4335"
                                d="M12 5.94c1.47 0 2.79.51 3.83 1.5l2.87-2.88A9.65 9.65 0 0 0 12 2a10 10 0 0 0-8.94 5.43l3.35 2.62C7.2 7.69 9.4 5.94 12 5.94Z"
                            />

                        </svg>


                        <span>
                            Daftar dengan Google
                        </span>

                    </a>



                    {{-- DIVIDER --}}

                    <div
                        class="my-5 flex items-center gap-4"
                    >

                        <div
                            class="h-px flex-1 bg-gray-200"
                        ></div>

                        <span
                            class="whitespace-nowrap text-xs text-gray-400"
                        >
                            atau daftar dengan email
                        </span>

                        <div
                            class="h-px flex-1 bg-gray-200"
                        ></div>

                    </div>



                    {{--
                    |--------------------------------------------------------------------------
                    | REGISTER FORM
                    |--------------------------------------------------------------------------
                    --}}

                    <form
                        method="POST"
                        action="{{ route('pendaki.register') }}"
                        id="registerForm"
                        class="space-y-4"
                    >

                        @csrf



                        {{-- NAME --}}

                        <div>

                            <label
                                for="name"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Nama lengkap
                            </label>


                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Contoh: Kadek Agus"
                                class="
                                    block h-12 w-full rounded-lg border
                                    bg-white px-3.5 text-sm
                                    text-gray-900 outline-none transition
                                    placeholder:text-gray-400
                                    focus:border-jalur-green
                                    focus:ring-2 focus:ring-jalur-green/10

                                    @error('name')
                                        border-red-400
                                    @else
                                        border-gray-300
                                    @enderror
                                "
                            >


                            @error('name')

                                <p
                                    class="mt-1.5 text-xs text-red-600"
                                >
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>



                        {{-- EMAIL --}}

                        <div>

                            <label
                                for="email"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Email
                            </label>


                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="nama@email.com"
                                class="
                                    block h-12 w-full rounded-lg border
                                    bg-white px-3.5 text-sm
                                    text-gray-900 outline-none transition
                                    placeholder:text-gray-400
                                    focus:border-jalur-green
                                    focus:ring-2 focus:ring-jalur-green/10

                                    @error('email')
                                        border-red-400
                                    @else
                                        border-gray-300
                                    @enderror
                                "
                            >


                            @error('email')

                                <p
                                    class="mt-1.5 text-xs text-red-600"
                                >
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>



                        {{-- PASSWORD --}}

                        <div>

                            <label
                                for="password"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Kata sandi
                            </label>


                            <div
                                class="relative"
                            >

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Minimal 8 karakter"
                                    class="
                                        block h-12 w-full rounded-lg border
                                        bg-white px-3.5 pr-11 text-sm
                                        text-gray-900 outline-none transition
                                        placeholder:text-gray-400
                                        focus:border-jalur-green
                                        focus:ring-2 focus:ring-jalur-green/10

                                        @error('password')
                                            border-red-400
                                        @else
                                            border-gray-300
                                        @enderror
                                    "
                                >


                                <button
                                    type="button"
                                    data-password-toggle="password"
                                    data-eye-open="passwordEyeOpen"
                                    data-eye-closed="passwordEyeClosed"
                                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-400 transition hover:text-gray-600"
                                    aria-label="Tampilkan kata sandi"
                                >

                                    <svg
                                        id="passwordEyeOpen"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"
                                        />

                                    </svg>


                                    <svg
                                        id="passwordEyeClosed"
                                        class="hidden h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.98 8.223A10.477 10.477 0 002.036 12.322a1.012 1.012 0 000 .639C3.423 17.134 7.36 20.145 12 20.145c1.11 0 2.178-.172 3.18-.491M6.228 6.228A10.451 10.451 0 0112 4.855c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639a10.45 10.45 0 01-2.293 4.033M3 3l18 18"
                                        />

                                    </svg>

                                </button>

                            </div>


                            <div
                                id="passwordRequirements"
                                class="mt-2 text-xs text-gray-400"
                            >
                                Gunakan minimal 8 karakter.
                            </div>


                            @error('password')

                                <p
                                    class="mt-1.5 text-xs text-red-600"
                                >
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>



                        {{-- PASSWORD CONFIRMATION --}}

                        <div>

                            <label
                                for="password_confirmation"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Konfirmasi kata sandi
                            </label>


                            <div
                                class="relative"
                            >

                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Ulangi kata sandi"
                                    class="
                                        block h-12 w-full rounded-lg border
                                        border-gray-300 bg-white
                                        px-3.5 pr-11 text-sm
                                        text-gray-900 outline-none transition
                                        placeholder:text-gray-400
                                        focus:border-jalur-green
                                        focus:ring-2 focus:ring-jalur-green/10
                                    "
                                >


                                <button
                                    type="button"
                                    data-password-toggle="password_confirmation"
                                    data-eye-open="confirmationEyeOpen"
                                    data-eye-closed="confirmationEyeClosed"
                                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-400 transition hover:text-gray-600"
                                    aria-label="Tampilkan konfirmasi kata sandi"
                                >

                                    <svg
                                        id="confirmationEyeOpen"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z"
                                        />

                                    </svg>


                                    <svg
                                        id="confirmationEyeClosed"
                                        class="hidden h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.98 8.223A10.477 10.477 0 002.036 12.322a1.012 1.012 0 000 .639C3.423 17.134 7.36 20.145 12 20.145c1.11 0 2.178-.172 3.18-.491M6.228 6.228A10.451 10.451 0 0112 4.855c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639a10.45 10.45 0 01-2.293 4.033M3 3l18 18"
                                        />

                                    </svg>

                                </button>

                            </div>


                            <p
                                id="passwordMatchMessage"
                                class="mt-1.5 hidden text-xs"
                            ></p>

                        </div>



                        {{-- SUBMIT --}}

                        <button
                            type="submit"
                            id="registerButton"
                            class="mt-2 flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-jalur-green px-4 text-sm font-semibold text-white transition hover:bg-jalur-greenLight focus:outline-none focus:ring-2 focus:ring-jalur-green/30 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70"
                        >

                            <svg
                                id="registerSpinner"
                                class="hidden h-4 w-4 animate-spin"
                                viewBox="0 0 24 24"
                                fill="none"
                            >

                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>


                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"
                                ></path>

                            </svg>


                            <span
                                id="registerButtonText"
                            >
                                Buat akun
                            </span>

                        </button>

                    </form>



                    {{-- LOGIN LINK --}}

                    <p
                        class="mt-6 text-center text-sm text-gray-500"
                    >

                        Sudah punya akun?

                        <a
                            href="{{ route('login') }}"
                            class="font-semibold text-jalur-orange transition hover:underline"
                        >
                            Masuk
                        </a>

                    </p>



                    {{-- MOBILE BACK HOME --}}

                    <div
                        class="mt-7 border-t border-gray-100 pt-6 text-center lg:hidden"
                    >

                        <a
                            href="{{ route('home') }}"
                            class="text-xs font-medium text-gray-400 transition hover:text-gray-600"
                        >
                            ← Kembali ke beranda
                        </a>

                    </div>


                </div>

            </div>



            {{-- FOOTER --}}

            <footer
                class="hidden px-12 pb-8 lg:block xl:px-20"
            >

                <div
                    class="mx-auto max-w-[430px]"
                >

                    <p
                        class="text-xs text-gray-400"
                    >
                        © {{ date('Y') }} BaliHiking
                    </p>

                </div>

            </footer>


        </section>

    </main>



    {{-- ========================================================= --}}
    {{-- PWA INSTALL BACKDROP --}}
    {{-- ========================================================= --}}

    <div
        id="pwa-install-backdrop"
    ></div>



    {{-- ========================================================= --}}
    {{-- PWA INSTALL POPUP --}}
    {{-- ========================================================= --}}

    <div
        id="pwa-install-popup"
        role="dialog"
        aria-modal="true"
        aria-labelledby="pwa-install-title"
    >


        <div
            class="pwa-install-header"
        >


            {{-- PWA ICON --}}

            <div
                class="pwa-install-icon"
            >

                <img
                    src="{{ asset('icons/icon-192.png') }}"
                    alt="BaliHiking"
                >

            </div>



            <div
                class="pwa-install-content"
            >

                <h3
                    id="pwa-install-title"
                    class="pwa-install-title"
                >
                    Install BaliHiking
                </h3>


                <p
                    id="pwa-install-description"
                    class="pwa-install-description"
                >
                    Pasang BaliHiking agar lebih cepat diakses
                    dan tetap dapat dibuka saat koneksi internet
                    tidak tersedia.
                </p>

            </div>



            <button
                type="button"
                id="pwa-install-close"
                class="pwa-install-close"
                aria-label="Tutup"
            >

                <svg
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



        {{-- FEATURES --}}

        <div
            class="pwa-install-features"
        >


            <div
                class="pwa-install-feature"
            >

                <svg
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6v6l4 2"
                    />

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    />

                </svg>


                <span>
                    Akses lebih cepat
                </span>

            </div>



            <div
                class="pwa-install-feature"
            >

                <svg
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


                <span>
                    Bisa offline
                </span>

            </div>



            <div
                class="pwa-install-feature"
            >

                <svg
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3v12m0 0 4-4m-4 4-4-4M5 21h14"
                    />

                </svg>


                <span>
                    Seperti aplikasi
                </span>

            </div>


        </div>



        {{-- IOS INFO --}}

        <div
            id="pwa-ios-info"
        >

            Untuk memasang <strong>BaliHiking</strong>
            di iPhone/iPad, buka menggunakan Safari,
            tekan tombol <strong>Share</strong>,
            kemudian pilih
            <strong>Add to Home Screen</strong>.

        </div>



        {{-- INSTALL STATUS --}}

        <div
            id="pwa-install-status"
        ></div>



        {{-- ACTIONS --}}

        <div
            class="pwa-install-actions"
        >

            <button
                type="button"
                id="pwa-install-later"
                class="pwa-install-later"
            >
                Nanti
            </button>


            <button
                type="button"
                id="pwa-install-confirm"
                class="pwa-install-button"
            >

                <svg
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3v12m0 0 4-4m-4 4-4-4M5 21h14"
                    />

                </svg>


                <span
                    id="pwa-install-confirm-text"
                >
                    Install BaliHiking
                </span>

            </button>

        </div>


    </div>



    {{-- ========================================================= --}}
    {{-- PWA SCRIPT --}}
    {{-- ========================================================= --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | VARIABLES
        |--------------------------------------------------------------------------
        */

        let deferredPwaPrompt = null;

        let pwaPopupHasBeenShown = false;


        const pwaInstallPopup =
            document.getElementById(
                'pwa-install-popup'
            );


        const pwaInstallBackdrop =
            document.getElementById(
                'pwa-install-backdrop'
            );


        const pwaInstallClose =
            document.getElementById(
                'pwa-install-close'
            );


        const pwaInstallLater =
            document.getElementById(
                'pwa-install-later'
            );


        const pwaInstallConfirm =
            document.getElementById(
                'pwa-install-confirm'
            );


        const pwaInstallConfirmText =
            document.getElementById(
                'pwa-install-confirm-text'
            );


        const pwaIosInfo =
            document.getElementById(
                'pwa-ios-info'
            );


        const pwaInstallStatus =
            document.getElementById(
                'pwa-install-status'
            );



        /*
        |--------------------------------------------------------------------------
        | PLATFORM
        |--------------------------------------------------------------------------
        */

        function pwaIsIOS() {

            return (
                /iPad|iPhone|iPod/i.test(
                    navigator.userAgent
                ) ||

                (
                    navigator.platform ===
                        'MacIntel' &&

                    navigator.maxTouchPoints >
                        1
                )
            );

        }


        function pwaIsStandalone() {

            return (
                window.matchMedia(
                    '(display-mode: standalone)'
                ).matches ||

                window.navigator
                    .standalone === true
            );

        }



        /*
        |--------------------------------------------------------------------------
        | INSTALL STATUS
        |--------------------------------------------------------------------------
        */

        function setPwaStatus(
            message
        ) {

            if (!pwaInstallStatus) {
                return;
            }


            if (!message) {

                pwaInstallStatus
                    .classList
                    .remove(
                        'show'
                    );

                pwaInstallStatus
                    .textContent =
                        '';

                return;

            }


            pwaInstallStatus
                .textContent =
                    message;


            pwaInstallStatus
                .classList
                .add(
                    'show'
                );

        }



        /*
        |--------------------------------------------------------------------------
        | SHOW POPUP
        |--------------------------------------------------------------------------
        */

        function showPwaInstallPopup(
            force = false
        ) {

            if (
                !pwaInstallPopup ||
                pwaIsStandalone()
            ) {

                return;

            }


            if (
                pwaPopupHasBeenShown &&
                !force
            ) {

                return;

            }


            pwaPopupHasBeenShown =
                true;


            pwaInstallPopup
                .classList
                .add(
                    'show'
                );


            if (pwaInstallBackdrop) {

                pwaInstallBackdrop
                    .classList
                    .add(
                        'show'
                    );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | HIDE POPUP
        |--------------------------------------------------------------------------
        */

        function hidePwaInstallPopup() {

            if (pwaInstallPopup) {

                pwaInstallPopup
                    .classList
                    .remove(
                        'show'
                    );

            }


            if (pwaInstallBackdrop) {

                pwaInstallBackdrop
                    .classList
                    .remove(
                        'show'
                    );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | REGISTER SERVICE WORKER
        |--------------------------------------------------------------------------
        */

        if (
            'serviceWorker' in navigator
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
                                        scope: '/'
                                    }
                                );


                        console.log(
                            '[BaliHiking PWA] Service Worker aktif:',
                            registration.scope
                        );


                        try {

                            await registration
                                .update();

                        } catch (
                            updateError
                        ) {

                            console.warn(
                                '[BaliHiking PWA] Update check gagal:',
                                updateError
                            );

                        }


                    } catch (error) {

                        console.error(
                            '[BaliHiking PWA] Service Worker gagal:',
                            error
                        );

                    }

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | CHROME / EDGE INSTALL EVENT
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'beforeinstallprompt',
            function (event) {

                event.preventDefault();


                deferredPwaPrompt =
                    event;


                setPwaStatus(
                    ''
                );


                if (
                    pwaInstallConfirmText
                ) {

                    pwaInstallConfirmText
                        .textContent =
                            'Install BaliHiking';

                }


                /*
                |--------------------------------------------------------------------------
                | Jika browser sudah menyatakan aplikasi installable,
                | munculkan popup segera.
                |--------------------------------------------------------------------------
                */

                setTimeout(
                    function () {

                        showPwaInstallPopup(
                            true
                        );

                    },
                    500
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | AUTO SHOW CUSTOM POPUP
        |--------------------------------------------------------------------------
        |
        | Popup custom tetap dimunculkan walaupun event browser belum keluar.
        | Jadi pengguna tetap melihat informasi instalasi BaliHiking.
        |
        */

        window.addEventListener(
            'load',
            function () {

                if (
                    pwaIsStandalone()
                ) {

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | IOS
                |--------------------------------------------------------------------------
                */

                if (
                    pwaIsIOS()
                ) {

                    if (
                        pwaIosInfo
                    ) {

                        pwaIosInfo
                            .classList
                            .add(
                                'show'
                            );

                    }


                    if (
                        pwaInstallConfirmText
                    ) {

                        pwaInstallConfirmText
                            .textContent =
                                'Cara Install';

                    }


                    setTimeout(
                        function () {

                            showPwaInstallPopup();

                        },
                        1200
                    );


                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | CHROME / EDGE / ANDROID / DESKTOP
                |--------------------------------------------------------------------------
                */

                setTimeout(
                    function () {

                        if (
                            !deferredPwaPrompt
                        ) {

                            setPwaStatus(
                                'BaliHiking sedang memeriksa kesiapan instalasi browser. Jika tombol install browser belum tersedia, pastikan manifest, icon, dan service worker sudah aktif.'
                            );

                        }


                        showPwaInstallPopup();

                    },
                    1500
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | INSTALL BUTTON
        |--------------------------------------------------------------------------
        */

        if (
            pwaInstallConfirm
        ) {

            pwaInstallConfirm
                .addEventListener(
                    'click',
                    async function () {


                        /*
                        |--------------------------------------------------------------------------
                        | IOS
                        |--------------------------------------------------------------------------
                        */

                        if (
                            pwaIsIOS()
                        ) {

                            if (
                                pwaIosInfo
                            ) {

                                pwaIosInfo
                                    .classList
                                    .add(
                                        'show'
                                    );

                            }


                            setPwaStatus(
                                'Safari iPhone/iPad tidak menampilkan dialog install otomatis. Gunakan Share → Add to Home Screen.'
                            );


                            return;

                        }



                        /*
                        |--------------------------------------------------------------------------
                        | PROMPT BELUM TERSEDIA
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !deferredPwaPrompt
                        ) {

                            setPwaStatus(
                                'Browser belum mengaktifkan tombol instalasi. Periksa Application → Manifest dan Application → Service Workers di DevTools.'
                            );


                            console.warn(
                                '[BaliHiking PWA] beforeinstallprompt belum tersedia.'
                            );


                            return;

                        }



                        /*
                        |--------------------------------------------------------------------------
                        | INSTALL
                        |--------------------------------------------------------------------------
                        */

                        try {

                            pwaInstallConfirm
                                .disabled =
                                    true;


                            pwaInstallConfirmText
                                .textContent =
                                    'Membuka installer...';


                            await deferredPwaPrompt
                                .prompt();


                            const choice =
                                await deferredPwaPrompt
                                    .userChoice;


                            console.log(
                                '[BaliHiking PWA] Hasil install:',
                                choice.outcome
                            );


                            if (
                                choice.outcome ===
                                    'accepted'
                            ) {

                                setPwaStatus(
                                    'BaliHiking sedang dipasang...'
                                );


                                hidePwaInstallPopup();

                            } else {

                                setPwaStatus(
                                    'Instalasi dibatalkan.'
                                );

                            }


                        } catch (
                            error
                        ) {

                            console.error(
                                '[BaliHiking PWA] Install gagal:',
                                error
                            );


                            setPwaStatus(
                                'Instalasi belum berhasil. Silakan coba kembali.'
                            );

                        }


                        deferredPwaPrompt =
                            null;


                        pwaInstallConfirm
                            .disabled =
                                false;


                        pwaInstallConfirmText
                            .textContent =
                                'Install BaliHiking';

                    }
                );

        }



        /*
        |--------------------------------------------------------------------------
        | CLOSE
        |--------------------------------------------------------------------------
        */

        if (
            pwaInstallClose
        ) {

            pwaInstallClose
                .addEventListener(
                    'click',
                    function () {

                        hidePwaInstallPopup();

                    }
                );

        }



        /*
        |--------------------------------------------------------------------------
        | LATER
        |--------------------------------------------------------------------------
        */

        if (
            pwaInstallLater
        ) {

            pwaInstallLater
                .addEventListener(
                    'click',
                    function () {

                        hidePwaInstallPopup();

                    }
                );

        }



        /*
        |--------------------------------------------------------------------------
        | BACKDROP
        |--------------------------------------------------------------------------
        */

        if (
            pwaInstallBackdrop
        ) {

            pwaInstallBackdrop
                .addEventListener(
                    'click',
                    function () {

                        hidePwaInstallPopup();

                    }
                );

        }



        /*
        |--------------------------------------------------------------------------
        | INSTALLED
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'appinstalled',
            function () {

                console.log(
                    '[BaliHiking PWA] BaliHiking berhasil di-install.'
                );


                deferredPwaPrompt =
                    null;


                hidePwaInstallPopup();

            }
        );



        /*
        |--------------------------------------------------------------------------
        | DEBUG
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'load',
            function () {

                console.log(
                    '[BaliHiking PWA DEBUG]',
                    {
                        online:
                            navigator.onLine,

                        standalone:
                            pwaIsStandalone(),

                        ios:
                            pwaIsIOS(),

                        serviceWorker:
                            'serviceWorker'
                                in navigator,

                        secureContext:
                            window.isSecureContext
                    }
                );

            }
        );

    </script>



    {{-- ========================================================= --}}
    {{-- REGISTER PAGE JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {


                /*
                |--------------------------------------------------------------------------
                | SHOW / HIDE PASSWORD
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll(
                        '[data-password-toggle]'
                    )
                    .forEach(
                        function (
                            button
                        ) {

                            button
                                .addEventListener(
                                    'click',
                                    function () {

                                        const inputId =
                                            button.dataset
                                                .passwordToggle;


                                        const eyeOpenId =
                                            button.dataset
                                                .eyeOpen;


                                        const eyeClosedId =
                                            button.dataset
                                                .eyeClosed;


                                        const input =
                                            document
                                                .getElementById(
                                                    inputId
                                                );


                                        const eyeOpen =
                                            document
                                                .getElementById(
                                                    eyeOpenId
                                                );


                                        const eyeClosed =
                                            document
                                                .getElementById(
                                                    eyeClosedId
                                                );


                                        if (
                                            !input ||
                                            !eyeOpen ||
                                            !eyeClosed
                                        ) {

                                            return;

                                        }


                                        const isHidden =
                                            input.type ===
                                                'password';


                                        input.type =
                                            isHidden
                                                ? 'text'
                                                : 'password';


                                        eyeOpen
                                            .classList
                                            .toggle(
                                                'hidden',
                                                isHidden
                                            );


                                        eyeClosed
                                            .classList
                                            .toggle(
                                                'hidden',
                                                !isHidden
                                            );

                                    }
                                );

                        }
                    );



                /*
                |--------------------------------------------------------------------------
                | PASSWORD MATCH
                |--------------------------------------------------------------------------
                */

                const password =
                    document
                        .getElementById(
                            'password'
                        );


                const passwordConfirmation =
                    document
                        .getElementById(
                            'password_confirmation'
                        );


                const matchMessage =
                    document
                        .getElementById(
                            'passwordMatchMessage'
                        );


                function checkPasswordMatch() {

                    if (
                        !password ||
                        !passwordConfirmation ||
                        !matchMessage
                    ) {

                        return;

                    }


                    if (
                        passwordConfirmation
                            .value
                            .length === 0
                    ) {

                        matchMessage
                            .classList
                            .add(
                                'hidden'
                            );


                        passwordConfirmation
                            .classList
                            .remove(
                                'border-red-400',
                                'border-emerald-500'
                            );


                        passwordConfirmation
                            .classList
                            .add(
                                'border-gray-300'
                            );


                        return;

                    }


                    matchMessage
                        .classList
                        .remove(
                            'hidden'
                        );


                    if (
                        password.value ===
                        passwordConfirmation
                            .value
                    ) {

                        matchMessage
                            .textContent =
                                'Kata sandi sudah sesuai.';


                        matchMessage
                            .className =
                                'mt-1.5 text-xs text-emerald-600';


                        passwordConfirmation
                            .classList
                            .remove(
                                'border-gray-300',
                                'border-red-400'
                            );


                        passwordConfirmation
                            .classList
                            .add(
                                'border-emerald-500'
                            );


                    } else {


                        matchMessage
                            .textContent =
                                'Konfirmasi kata sandi belum sesuai.';


                        matchMessage
                            .className =
                                'mt-1.5 text-xs text-red-600';


                        passwordConfirmation
                            .classList
                            .remove(
                                'border-gray-300',
                                'border-emerald-500'
                            );


                        passwordConfirmation
                            .classList
                            .add(
                                'border-red-400'
                            );

                    }

                }


                if (
                    password &&
                    passwordConfirmation
                ) {

                    password
                        .addEventListener(
                            'input',
                            checkPasswordMatch
                        );


                    passwordConfirmation
                        .addEventListener(
                            'input',
                            checkPasswordMatch
                        );

                }



                /*
                |--------------------------------------------------------------------------
                | SUBMIT LOADING
                |--------------------------------------------------------------------------
                */

                const registerForm =
                    document
                        .getElementById(
                            'registerForm'
                        );


                const registerButton =
                    document
                        .getElementById(
                            'registerButton'
                        );


                const registerSpinner =
                    document
                        .getElementById(
                            'registerSpinner'
                        );


                const registerButtonText =
                    document
                        .getElementById(
                            'registerButtonText'
                        );


                if (
                    registerForm &&
                    registerButton &&
                    registerSpinner &&
                    registerButtonText
                ) {

                    registerForm
                        .addEventListener(
                            'submit',
                            function (
                                event
                            ) {


                                /*
                                |--------------------------------------------------------------------------
                                | Jangan submit kalau password berbeda.
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    password &&
                                    passwordConfirmation &&
                                    password.value !==
                                        passwordConfirmation
                                            .value
                                ) {

                                    event
                                        .preventDefault();


                                    passwordConfirmation
                                        .focus();


                                    checkPasswordMatch();


                                    return;

                                }


                                registerButton
                                    .disabled =
                                        true;


                                registerSpinner
                                    .classList
                                    .remove(
                                        'hidden'
                                    );


                                registerButtonText
                                    .textContent =
                                        'Membuat akun...';

                            }
                        );

                }

            }
        );

    </script>


</body>

</html>