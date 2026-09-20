<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="theme-color" content="#1a382b">

    <meta name="mobile-web-app-capable" content="yes">

    <meta name="apple-mobile-web-app-capable" content="yes">

    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <meta name="apple-mobile-web-app-title" content="BaliHiking">

    <meta name="application-name" content="BaliHiking">


    <title>
        Profil Pendaki - BaliHiking
    </title>


    {{-- ========================================================= --}}
    {{-- PWA --}}
    {{-- ========================================================= --}}

    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192.png') }}">

    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icons/icon-512.png') }}">

    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">


    {{-- ========================================================= --}}
    {{-- TAILWIND LOCAL --}}
    {{-- ========================================================= --}}

    <script src="{{ asset('vendor/tailwindcss.js') }}"></script>


    {{-- ========================================================= --}}
    {{-- TAILWIND CONFIG --}}
    {{-- ========================================================= --}}

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


        button {

            font:
                inherit;

        }


        /*
        |--------------------------------------------------------------------------
        | PROFILE PHOTO
        |--------------------------------------------------------------------------
        */

        .profile-avatar {

            position:
                relative;

            width:
                64px;

            height:
                64px;

            flex:
                0 0 64px;

            overflow:
                hidden;

            border:
                2px solid rgba(240, 101, 53, .28);

            border-radius:
                16px;

            background:
                #1a382b;

            box-shadow:
                0 4px 14px rgba(0, 0, 0, .08);

        }


        .profile-avatar-fallback {

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

            color:
                white;

            background:
                #1a382b;

            font-size:
                20px;

            font-weight:
                900;

            letter-spacing:
                -.03em;

            text-transform:
                uppercase;

        }


        .profile-avatar-image {

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
                calc(88px + env(safe-area-inset-bottom));

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
                0 10px 28px rgba(0, 0, 0, .16);

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
        | OFFLINE BADGE
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
        | CACHE INFO
        |--------------------------------------------------------------------------
        */

        #cacheInformation {

            display:
                none;

        }


        #cacheInformation.show {

            display:
                flex;

        }


        /*
        |--------------------------------------------------------------------------
        | TOAST
        |--------------------------------------------------------------------------
        */

        #appToast {

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
                13px 15px;

            border-radius:
                14px;

            transform:
                translate(-50%,
                    -28px);

            opacity:
                0;

            visibility:
                hidden;

            color:
                white;

            background:
                #1a382b;

            box-shadow:
                0 14px 34px rgba(0, 0, 0, .18);

            transition:
                all .25s ease;

        }


        #appToast.show {

            transform:
                translate(-50%,
                    0);

            opacity:
                1;

            visibility:
                visible;

        }


        #appToast.warning {

            background:
                #f06535;

        }


        #appToast.error {

            background:
                #dc2626;

        }


        /*
        |--------------------------------------------------------------------------
        | MENU OFFLINE
        |--------------------------------------------------------------------------
        */

        body.is-offline .online-only {

            position:
                relative;

        }


        body.is-offline .online-only::after {

            content:
                "online";

            margin-left:
                auto;

            padding:
                2px 5px;

            border-radius:
                999px;

            color:
                #f06535;

            background:
                rgba(240, 101, 53, .10);

            font-size:
                7px;

            font-weight:
                900;

            text-transform:
                uppercase;

            letter-spacing:
                .03em;

        }


        /*
        |--------------------------------------------------------------------------
        | SAFE AREA
        |--------------------------------------------------------------------------
        */

        @supports (padding-bottom: env(safe-area-inset-bottom)) {

            body {

                padding-left:
                    env(safe-area-inset-left);

                padding-right:
                    env(safe-area-inset-right);

            }

        }
    </style>

</head>


<body id="appBody" class="
        bg-brand-cream
        pb-28
        text-brand-dark
        antialiased
    ">


    @php

        $initials = collect(preg_split('/\s+/', trim($user->name)))
            ->filter()
            ->take(2)
            ->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');

    @endphp



    {{-- ========================================================= --}}
    {{-- TOAST --}}
    {{-- ========================================================= --}}

    <div id="appToast" role="status" aria-live="polite">

        <div class="
                flex
                items-start
                gap-3
            ">

            <span
                class="
                    mt-1
                    h-2
                    w-2
                    shrink-0
                    rounded-full
                    bg-white
                "></span>


            <div class="min-w-0">

                <p id="appToastTitle"
                    class="
                        text-xs
                        font-extrabold
                    ">
                    BaliHiking
                </p>


                <p id="appToastMessage"
                    class="
                        mt-0.5
                        text-[10px]
                        leading-relaxed
                        text-white/80
                    ">
                </p>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- NETWORK STATUS --}}
    {{-- ========================================================= --}}

    <div id="networkStatus">

        <span id="networkStatusDot"></span>


        <span id="networkStatusText">
            Mode Offline
        </span>

    </div>



    {{-- ========================================================= --}}
    {{-- TOP HEADER --}}
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
        ">

        <div
            class="
                mx-auto
                flex
                h-16
                max-w-md
                items-center
                justify-between
                px-4
                sm:max-w-xl
            ">


            {{-- BACK --}}

            <a href="{{ route('pendaki.dashboard') }}" data-offline-aware
                class="
                    -ml-2
                    flex
                    h-10
                    w-10
                    items-center
                    justify-center
                    rounded-xl
                    text-brand-dark
                    transition
                    hover:bg-brand-dark/5
                    hover:text-brand-orange
                ">

                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />

                </svg>

            </a>



            {{-- TITLE --}}

            <div
                class="
                    flex
                    items-center
                    gap-2.5
                ">

                <span
                    class="
                        h-8
                        w-8
                        overflow-hidden
                        rounded-lg
                    ">

                    <img src="{{ asset('icons/icon-192.png') }}" alt="BaliHiking"
                        class="
                            h-full
                            w-full
                            object-cover
                        ">

                </span>


                <div>

                    <h1
                        class="
                            text-sm
                            font-black
                            tracking-tight
                            text-brand-dark
                        ">
                        Profil Akun
                    </h1>


                    <div
                        class="
                            mt-0.5
                            flex
                            items-center
                            gap-2
                        ">

                        <span
                            class="
                                text-[8px]
                                font-semibold
                                text-brand-dark/40
                            ">
                            BaliHiking
                        </span>


                        <span id="offlineBadge"
                            class="
                                items-center
                                gap-1
                                text-[8px]
                                font-extrabold
                                uppercase
                                tracking-wide
                                text-brand-orange
                            ">

                            <span
                                class="
                                    h-1.5
                                    w-1.5
                                    rounded-full
                                    bg-brand-orange
                                "></span>

                            Offline

                        </span>

                    </div>

                </div>

            </div>


            <div class="w-10"></div>

        </div>

    </header>



    {{-- ========================================================= --}}
    {{-- MAIN CONTENT --}}
    {{-- ========================================================= --}}

    <main
        class="
            mx-auto
            max-w-md
            space-y-5
            px-4
            pt-6
            sm:max-w-xl
        ">


        {{-- ===================================================== --}}
        {{-- OFFLINE INFORMATION --}}
        {{-- ===================================================== --}}

        <section id="offlineInformation"
            class="
                rounded-2xl
                border
                border-orange-200
                bg-orange-50
                p-4
            ">

            <div
                class="
                    flex
                    items-start
                    gap-3
                ">

                <svg class="
                        mt-0.5
                        h-5
                        w-5
                        shrink-0
                        text-brand-orange
                    "
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0ZM12 15.75h.008v.008H12v-.008Z" />

                </svg>


                <div>

                    <p
                        class="
                            text-xs
                            font-extrabold
                            text-brand-orange
                        ">
                        Profil Offline
                    </p>


                    <p
                        class="
                            mt-1
                            text-[10px]
                            leading-relaxed
                            text-orange-800/70
                        ">
                        Informasi profil yang ditampilkan
                        merupakan data terakhir yang tersimpan
                        di perangkat. Perubahan akun dan logout
                        membutuhkan koneksi internet.
                    </p>


                    <div id="cacheInformation"
                        class="
                            mt-2
                            items-center
                            gap-1
                            text-[9px]
                            font-semibold
                            text-orange-700/70
                        ">

                        <span>
                            Tersimpan:
                        </span>


                        <span id="cacheTimestamp">
                            -
                        </span>

                    </div>

                </div>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- USER INFO --}}
        {{-- ===================================================== --}}

        <section
            class="
                flex
                items-center
                gap-4
                rounded-3xl
                border
                border-brand-dark/10
                bg-white
                p-5
                shadow-sm
            ">


            {{-- AVATAR --}}

            <div class="profile-avatar">

                <div class="profile-avatar-fallback">
                    {{ $initials ?: 'BH' }}
                </div>


                @if ($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="profile-avatar-image"
                        loading="eager" onerror="this.style.display='none'">
                @endif

            </div>



            {{-- INFO --}}

            <div class="
                    min-w-0
                    flex-1
                ">

                <h2
                    class="
                        truncate
                        text-base
                        font-black
                        leading-snug
                        text-brand-dark
                    ">
                    {{ $user->name }}
                </h2>


                <p
                    class="
                        mt-0.5
                        truncate
                        text-xs
                        font-semibold
                        text-brand-dark/50
                    ">
                    {{ $user->email }}
                </p>


                <span
                    class="
                        mt-2
                        inline-flex
                        items-center
                        gap-1.5
                        rounded-full
                        border
                        border-brand-orange/20
                        bg-brand-orange/10
                        px-3
                        py-1
                        text-[10px]
                        font-extrabold
                        text-brand-orange
                    ">

                    <span
                        class="
                            h-1.5
                            w-1.5
                            rounded-full
                            bg-brand-orange
                        "></span>

                    Pendaki Aktif

                </span>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- ACCOUNT INFORMATION --}}
        {{-- ===================================================== --}}

        <section
            class="
                overflow-hidden
                rounded-3xl
                border
                border-brand-dark/10
                bg-white
                shadow-sm
            ">

            <div
                class="
                    border-b
                    border-brand-dark/5
                    px-5
                    py-4
                ">

                <p
                    class="
                        text-[10px]
                        font-black
                        uppercase
                        tracking-wider
                        text-brand-orange
                    ">
                    Informasi Akun
                </p>

            </div>



            <div class="
                    divide-y
                    divide-brand-dark/5
                ">


                {{-- NAME --}}

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-4
                        px-5
                        py-4
                    ">

                    <div>

                        <p
                            class="
                                text-[10px]
                                font-semibold
                                text-brand-dark/40
                            ">
                            Nama
                        </p>


                        <p
                            class="
                                mt-1
                                text-xs
                                font-bold
                                text-brand-dark
                            ">
                            {{ $user->name }}
                        </p>

                    </div>


                    <svg class="
                            h-4
                            w-4
                            shrink-0
                            text-brand-dark/25
                        "
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.5 20.25a7.5 7.5 0 0115 0" />

                    </svg>

                </div>



                {{-- EMAIL --}}

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-4
                        px-5
                        py-4
                    ">

                    <div class="min-w-0">

                        <p
                            class="
                                text-[10px]
                                font-semibold
                                text-brand-dark/40
                            ">
                            Email
                        </p>


                        <p
                            class="
                                mt-1
                                truncate
                                text-xs
                                font-bold
                                text-brand-dark
                            ">
                            {{ $user->email }}
                        </p>

                    </div>


                    <svg class="
                            h-4
                            w-4
                            shrink-0
                            text-brand-dark/25
                        "
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5A2.25 2.25 0 0119.5 19.5h-15a2.25 2.25 0 01-2.25-2.25V6.75M3.75 6l7.233 5.027a1.75 1.75 0 002.034 0L20.25 6" />

                    </svg>

                </div>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- SETTINGS --}}
        {{-- ===================================================== --}}

        <section
            class="
                overflow-hidden
                rounded-3xl
                border
                border-brand-dark/10
                bg-white
                text-xs
                font-bold
                text-brand-dark
                shadow-sm
            ">


            {{-- ================================================= --}}
            {{-- ACCOUNT SETTINGS --}}
            {{-- ================================================= --}}

            <a href="{{ route('pendaki.profil.account.edit') }}"
                class="
                online-only
                flex
                items-center
                justify-between
                border-b
                border-brand-dark/5
                p-4
                transition
                hover:bg-brand-cream/50
            ">

                <div
                    class="
                        flex
                        items-center
                        gap-3
                    ">

                    <div
                        class="
                            rounded-xl
                            bg-brand-dark/5
                            p-2
                            text-brand-dark
                        ">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                        </svg>

                    </div>


                    <div>

                        <span>
                            Pengaturan Akun
                        </span>


                        <p
                            class="
                                mt-0.5
                                text-[9px]
                                font-medium
                                text-brand-dark/40
                            ">
                            Nama, email dan kata sandi
                        </p>

                    </div>

                </div>


                <svg class="
                        h-4
                        w-4
                        shrink-0
                        text-brand-dark/30
                    "
                    fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />

                </svg>

            </a>



            {{-- ================================================= --}}
            {{-- ID DOCUMENT --}}
            {{-- ================================================= --}}

            <a href="{{ route('pendaki.profil.documents.index') }}"
                class="
                    online-only
                    flex
                    items-center
                    justify-between
                    border-b
                    border-brand-dark/5
                    p-4
                    transition
                    hover:bg-brand-cream/50
                ">

                <div
                    class="
                        flex
                        items-center
                        gap-3
                    ">

                    <div
                        class="
                            rounded-xl
                            bg-brand-dark/5
                            p-2
                            text-brand-dark
                        ">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4" />

                        </svg>

                    </div>


                    <div>

                        <span>
                            Dokumen Identitas
                        </span>


                        <p
                            class="
                                mt-0.5
                                text-[9px]
                                font-medium
                                text-brand-dark/40
                            ">
                            KTP / SIM pendaki
                        </p>

                    </div>

                </div>


                <svg class="
                        h-4
                        w-4
                        shrink-0
                        text-brand-dark/30
                    "
                    fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />

                </svg>

            </a>



            {{-- ================================================= --}}
            {{-- PWA --}}
            {{-- ================================================= --}}

            <div
                class="
                    flex
                    items-center
                    justify-between
                    p-4
                ">

                <div
                    class="
                        flex
                        items-center
                        gap-3
                    ">

                    <div
                        class="
                            rounded-xl
                            bg-brand-orange/10
                            p-2
                            text-brand-orange
                        ">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v12m0 0 4-4m-4 4-4-4M5 21h14" />

                        </svg>

                    </div>


                    <div>

                        <span>
                            BaliHiking PWA
                        </span>


                        <p id="pwaStatusText"
                            class="
                                mt-0.5
                                text-[9px]
                                font-medium
                                text-brand-dark/40
                            ">
                            Memeriksa aplikasi...
                        </p>

                    </div>

                </div>


                <span id="pwaStatusBadge"
                    class="
                        rounded-full
                        bg-brand-dark/5
                        px-2.5
                        py-1
                        text-[8px]
                        font-extrabold
                        uppercase
                        text-brand-dark/50
                    ">
                    PWA
                </span>

            </div>

        </section>



        {{-- ===================================================== --}}
        {{-- LOGOUT --}}
        {{-- ===================================================== --}}

        <section
            class="
                overflow-hidden
                rounded-3xl
                border
                border-rose-100
                bg-white
                shadow-sm
            ">

            <form id="logoutForm" method="POST" action="{{ route('logout') }}">

                @csrf


                <button type="submit" id="logoutButton"
                    class="
                        flex
                        w-full
                        items-center
                        justify-between
                        p-4
                        text-left
                        font-black
                        text-rose-600
                        transition
                        hover:bg-rose-50
                    ">

                    <div
                        class="
                            flex
                            items-center
                            gap-3
                        ">

                        <div
                            class="
                                rounded-xl
                                bg-rose-100/60
                                p-2
                                text-rose-600
                            ">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />

                            </svg>

                        </div>


                        <div>

                            <span class="text-xs">
                                Keluar dari Akun
                            </span>


                            <p
                                class="
                                    mt-0.5
                                    text-[9px]
                                    font-medium
                                    text-rose-400
                                ">
                                Mengakhiri sesi BaliHiking
                            </p>

                        </div>

                    </div>


                    <svg class="
                            h-4
                            w-4
                            text-rose-400
                        "
                        fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />

                    </svg>

                </button>

            </form>

        </section>



        {{-- ===================================================== --}}
        {{-- APP INFORMATION --}}
        {{-- ===================================================== --}}

        <section class="
                pb-2
                text-center
            ">

            <div
                class="
                    mx-auto
                    h-12
                    w-12
                    overflow-hidden
                    rounded-xl
                ">

                <img src="{{ asset('icons/icon-192.png') }}" alt="BaliHiking"
                    class="
                        h-full
                        w-full
                        object-cover
                    ">

            </div>


            <p
                class="
                    mt-3
                    text-xs
                    font-extrabold
                    text-brand-dark
                ">
                BaliHiking
            </p>


            <p
                class="
                    mt-1
                    text-[9px]
                    text-brand-dark/35
                ">
                Sistem Informasi Pendakian Gunung Bali
            </p>

        </section>

    </main>



    {{-- ========================================================= --}}
    {{-- BOTTOM NAVIGATION --}}
    {{-- ========================================================= --}}

    @include('pendaki.components.bottom-nav', [
        'active' => 'profil',
    ])



    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>
        /*
                |--------------------------------------------------------------------------
                | CONFIG
                |--------------------------------------------------------------------------
                */

        const PROFILE_CACHE =
            'balihiking-profile-v2';


        const PROFILE_CACHE_TIME_KEY =
            'balihiking_profile_cache_time';


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


        const cacheInformation =
            document.getElementById(
                'cacheInformation'
            );


        const cacheTimestamp =
            document.getElementById(
                'cacheTimestamp'
            );


        const logoutForm =
            document.getElementById(
                'logoutForm'
            );


        const logoutButton =
            document.getElementById(
                'logoutButton'
            );


        const pwaStatusText =
            document.getElementById(
                'pwaStatusText'
            );


        const pwaStatusBadge =
            document.getElementById(
                'pwaStatusBadge'
            );



        /*
        |--------------------------------------------------------------------------
        | START
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'DOMContentLoaded',
            async function() {

                updateNetworkUI();

                showCacheTimestamp();

                setupOfflineLinks();

                setupComingSoon();

                updatePwaStatus();


                if (
                    navigator.onLine
                ) {

                    await cacheCurrentProfile();

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | SERVICE WORKER
        |--------------------------------------------------------------------------
        */

        if (
            'serviceWorker' in navigator
        ) {

            window.addEventListener(
                'load',
                async function() {

                    try {

                        const registration =
                            await navigator
                            .serviceWorker
                            .register(
                                '/sw.js', {
                                    scope: '/'
                                }
                            );


                        console.log(
                            '[BaliHiking Profil] Service Worker:',
                            registration.scope
                        );


                        registration
                            .update()
                            .catch(
                                function() {}
                            );


                    } catch (
                        error
                    ) {

                        console.error(
                            '[BaliHiking Profil] Service Worker gagal:',
                            error
                        );

                    }

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | NETWORK EVENT
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'online',
            async function() {

                updateNetworkUI();


                showToast(
                    'Internet kembali aktif',
                    'Profil BaliHiking dapat diperbarui kembali.',
                    'success'
                );


                await cacheCurrentProfile();

            }
        );


        window.addEventListener(
            'offline',
            function() {

                updateNetworkUI();


                showToast(
                    'Mode Offline',
                    'Profil terakhir yang tersimpan tetap dapat dilihat.',
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
                    function() {

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
                    'Offline · menggunakan profil tersimpan';


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
        | CACHE PROFILE
        |--------------------------------------------------------------------------
        */

        async function cacheCurrentProfile() {

            if (
                !navigator.onLine ||
                !('caches' in window)
            ) {

                return;

            }


            try {

                const cache =
                    await caches.open(
                        PROFILE_CACHE
                    );


                const response =
                    await fetch(
                        window.location.href, {
                            credentials: 'same-origin',

                            cache: 'no-store',

                            headers: {

                                'X-BaliHiking-Cache': 'profile'

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


                    try {

                        localStorage.setItem(
                            PROFILE_CACHE_TIME_KEY,
                            String(
                                now
                            )
                        );

                    } catch (
                        error
                    ) {}


                    showCacheTimestamp();

                }


                /*
                |--------------------------------------------------------------------------
                | LOCAL ASSETS
                |--------------------------------------------------------------------------
                */

                const assets = [

                    '/manifest.webmanifest',

                    '/vendor/tailwindcss.js',

                    '/icons/icon-192.png',

                    '/icons/icon-512.png',

                    '/icons/icon-maskable-192.png',

                    '/icons/icon-maskable-512.png'

                ];


                for (
                    const url of assets
                ) {

                    try {

                        const assetResponse =
                            await fetch(
                                url
                            );


                        if (
                            assetResponse.ok
                        ) {

                            await cache.put(
                                url,
                                assetResponse.clone()
                            );

                        }

                    } catch (
                        error
                    ) {}

                }


                /*
                |--------------------------------------------------------------------------
                | PROFILE PHOTO
                |--------------------------------------------------------------------------
                */

                const profileImage =
                    document.querySelector(
                        '.profile-avatar-image'
                    );


                if (
                    profileImage &&
                    profileImage.src
                ) {

                    try {

                        const imageResponse =
                            await fetch(
                                profileImage.src, {
                                    mode: 'cors'
                                }
                            );


                        if (
                            imageResponse.ok
                        ) {

                            await cache.put(
                                profileImage.src,
                                imageResponse.clone()
                            );

                        }

                    } catch (
                        error
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | Tidak masalah.
                        | Fallback inisial akan digunakan.
                        |--------------------------------------------------------------------------
                        */

                    }

                }


            } catch (
                error
            ) {

                console.warn(
                    '[BaliHiking Profil] Cache gagal:',
                    error
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | CACHE TIME
        |--------------------------------------------------------------------------
        */

        function showCacheTimestamp() {

            let saved =
                null;


            try {

                saved =
                    localStorage.getItem(
                        PROFILE_CACHE_TIME_KEY
                    );

            } catch (
                error
            ) {}


            if (
                !saved
            ) {

                cacheInformation
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
                    'id-ID', {
                        dateStyle: 'medium',

                        timeStyle: 'short'
                    }
                );


            cacheInformation
                .classList
                .add(
                    'show'
                );

        }



        /*
        |--------------------------------------------------------------------------
        | OFFLINE LINKS
        |--------------------------------------------------------------------------
        */

        function setupOfflineLinks() {

            document
                .querySelectorAll(
                    '[data-offline-aware]'
                )
                .forEach(
                    function(
                        link
                    ) {

                        link
                            .addEventListener(
                                'click',
                                async function(
                                    event
                                ) {

                                    if (
                                        navigator.onLine
                                    ) {

                                        return;

                                    }


                                    event.preventDefault();


                                    const cached =
                                        await isPageCached(
                                            link.href
                                        );


                                    if (
                                        cached
                                    ) {

                                        window.location.href =
                                            link.href;


                                        return;

                                    }


                                    showToast(
                                        'Halaman belum tersimpan',
                                        'Buka halaman tersebut saat online minimal satu kali agar dapat digunakan offline.',
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
                        parsed.pathname +
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
        | COMING SOON
        |--------------------------------------------------------------------------
        |
        | Kedua menu pada kode asli masih href="#".
        | Jadi saya tidak mengarang route Laravel yang belum diberikan.
        |--------------------------------------------------------------------------
        */

        function setupComingSoon() {

            document
                .querySelectorAll(
                    '[data-coming-soon]'
                )
                .forEach(
                    function(
                        item
                    ) {

                        item
                            .addEventListener(
                                'click',
                                function(
                                    event
                                ) {

                                    event
                                        .preventDefault();


                                    const feature =
                                        item.dataset
                                        .featureName ||
                                        'Fitur';


                                    if (
                                        !navigator.onLine
                                    ) {

                                        showToast(
                                            'Membutuhkan internet',
                                            `${feature} tidak dapat diubah ketika BaliHiking sedang offline.`,
                                            'warning'
                                        );


                                        return;

                                    }


                                    showToast(
                                        feature,
                                        'Route untuk fitur ini belum dikonfigurasi pada kode yang diberikan.',
                                        'success'
                                    );

                                }
                            );

                    }
                );

        }



        /*
        |--------------------------------------------------------------------------
        | LOGOUT
        |--------------------------------------------------------------------------
        */

        logoutForm
            .addEventListener(
                'submit',
                function(
                    event
                ) {

                    if (
                        !navigator.onLine
                    ) {

                        event.preventDefault();


                        showToast(
                            'Tidak dapat logout saat offline',
                            'Logout membutuhkan koneksi ke server. Profil BaliHiking tetap aman tersimpan di perangkat.',
                            'warning'
                        );


                        return;

                    }


                    logoutButton
                        .disabled =
                        true;


                    logoutButton
                        .classList
                        .add(
                            'opacity-60',
                            'cursor-not-allowed'
                        );

                }
            );



        /*
        |--------------------------------------------------------------------------
        | PWA STATUS
        |--------------------------------------------------------------------------
        */

        function updatePwaStatus() {

            const standalone =
                window.matchMedia(
                    '(display-mode: standalone)'
                ).matches ||
                window.navigator
                .standalone === true;


            if (
                standalone
            ) {

                pwaStatusText
                    .innerText =
                    'BaliHiking terpasang di perangkat';


                pwaStatusBadge
                    .innerText =
                    'Terpasang';


                pwaStatusBadge
                    .className =
                    'rounded-full bg-emerald-100 px-2.5 py-1 text-[8px] font-extrabold uppercase text-emerald-700';


                return;

            }


            if (
                'serviceWorker' in navigator
            ) {

                pwaStatusText
                    .innerText =
                    'Mode PWA dan offline tersedia';


                pwaStatusBadge
                    .innerText =
                    'Aktif';


                return;

            }


            pwaStatusText
                .innerText =
                'Browser tidak mendukung PWA';


            pwaStatusBadge
                .innerText =
                'Tidak Aktif';

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
                    'appToast'
                );


            const titleElement =
                document.getElementById(
                    'appToastTitle'
                );


            const messageElement =
                document.getElementById(
                    'appToastMessage'
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
                function() {

                    toast
                        .classList
                        .add(
                            'show'
                        );

                }
            );


            toastTimer =
                setTimeout(
                    function() {

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
