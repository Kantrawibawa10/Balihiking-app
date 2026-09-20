@php

    /*
    |--------------------------------------------------------------------------
    | BaliHiking Bottom Navigation
    |--------------------------------------------------------------------------
    */

    $currentActive =
        $active ?? '';


    /*
    |--------------------------------------------------------------------------
    | Active State
    |--------------------------------------------------------------------------
    */

    $isDashboard =
        $currentActive === 'dashboard'
        ||
        request()->routeIs(
            'pendaki.dashboard',
            'pendaki.dashboard.*'
        );


    $isSimaksi =
        $currentActive === 'simaksi'
        ||
        request()->routeIs(
            'pendaki.simaksi',
            'pendaki.simaksi.*'
        );


    $isLiveTrack =
        in_array(
            $currentActive,
            [
                'live-track',
                'livetrack',
                'tracking',
            ],
            true
        )
        ||
        request()->routeIs(
            'pendaki.live-track',
            'pendaki.live-track.*'
        );


    /*
    |--------------------------------------------------------------------------
    | Semua halaman profil dianggap menu Profil aktif:
    |
    | - Profil
    | - Pengaturan Akun
    | - Ganti Password
    | - Dokumen Identitas
    |--------------------------------------------------------------------------
    */

    $isProfil =
        in_array(
            $currentActive,
            [
                'profil',
                'profile',
                'account',
                'documents',
                'document',
            ],
            true
        )
        ||
        request()->routeIs(
            'pendaki.profil',
            'pendaki.profil.*',
            'pendaki.profile',
            'pendaki.profile.*'
        );


    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */

    $dashboardUrl =
        Route::has('pendaki.dashboard')
            ? route('pendaki.dashboard')
            : url('/pendaki/dashboard');


    $simaksiUrl =
        Route::has('pendaki.simaksi')
            ? route('pendaki.simaksi')
            : url('/pendaki/simaksi');


    $liveTrackUrl =
        Route::has('pendaki.live-track')
            ? route('pendaki.live-track')
            : url('/pendaki/live-track');


    $profilUrl =
        Route::has('pendaki.profil')
            ? route('pendaki.profil')
            : (
                Route::has('pendaki.profile')
                    ? route('pendaki.profile')
                    : url('/pendaki/profil')
            );

@endphp



{{-- ========================================================= --}}
{{-- BALI HIKING BOTTOM NAVIGATION --}}
{{-- ========================================================= --}}

<style>

    /*
    |--------------------------------------------------------------------------
    | Bottom Navigation
    |--------------------------------------------------------------------------
    |
    | Dibuat dengan CSS mandiri agar navigation tetap rapi walaupun
    | Tailwind belum selesai dimuat atau aplikasi sedang offline.
    |
    */

    .bh-bottom-navigation {

        position:
            fixed;

        left:
            0;

        right:
            0;

        bottom:
            0;

        z-index:
            9990;

        padding:
            7px
            10px
            calc(
                7px +
                env(safe-area-inset-bottom)
            );

        border-top:
            1px solid
            rgba(
                26,
                56,
                43,
                .09
            );

        background:
            rgba(
                255,
                255,
                255,
                .96
            );

        box-shadow:
            0 -8px 30px
            rgba(
                26,
                56,
                43,
                .06
            );

        backdrop-filter:
            blur(18px);

        -webkit-backdrop-filter:
            blur(18px);

    }


    /*
    |--------------------------------------------------------------------------
    | Container
    |--------------------------------------------------------------------------
    */

    .bh-bottom-navigation__container {

        display:
            grid;

        grid-template-columns:
            repeat(
                4,
                minmax(
                    0,
                    1fr
                )
            );

        align-items:
            center;

        width:
            100%;

        max-width:
            470px;

        margin:
            0 auto;

        gap:
            3px;

    }


    /*
    |--------------------------------------------------------------------------
    | Navigation Item
    |--------------------------------------------------------------------------
    */

    .bh-bottom-navigation__item {

        position:
            relative;

        display:
            flex;

        min-width:
            0;

        min-height:
            54px;

        align-items:
            center;

        justify-content:
            center;

        flex-direction:
            column;

        gap:
            3px;

        padding:
            4px 2px;

        border-radius:
            14px;

        color:
            #94a3b8;

        text-decoration:
            none;

        -webkit-tap-highlight-color:
            transparent;

        transition:
            color .2s ease,
            background .2s ease,
            transform .15s ease;

    }


    .bh-bottom-navigation__item:hover {

        color:
            #f06535;

    }


    .bh-bottom-navigation__item:active {

        transform:
            scale(.96);

    }


    /*
    |--------------------------------------------------------------------------
    | Active
    |--------------------------------------------------------------------------
    */

    .bh-bottom-navigation__item.is-active {

        color:
            #f06535;

    }


    /*
    |--------------------------------------------------------------------------
    | Icon Wrapper
    |--------------------------------------------------------------------------
    */

    .bh-bottom-navigation__icon {

        position:
            relative;

        display:
            flex;

        width:
            34px;

        height:
            28px;

        align-items:
            center;

        justify-content:
            center;

        border-radius:
            10px;

        transition:
            background .2s ease,
            transform .2s ease;

    }


    .bh-bottom-navigation__item.is-active
    .bh-bottom-navigation__icon {

        background:
            rgba(
                240,
                101,
                53,
                .10
            );

    }


    .bh-bottom-navigation__icon svg {

        width:
            22px;

        height:
            22px;

    }


    /*
    |--------------------------------------------------------------------------
    | Active Dot
    |--------------------------------------------------------------------------
    */

    .bh-bottom-navigation__active-dot {

        position:
            absolute;

        top:
            -3px;

        left:
            50%;

        width:
            4px;

        height:
            4px;

        border-radius:
            999px;

        transform:
            translateX(
                -50%
            );

        background:
            #f06535;

        opacity:
            0;

        transition:
            opacity .2s ease;

    }


    .bh-bottom-navigation__item.is-active
    .bh-bottom-navigation__active-dot {

        opacity:
            1;

    }


    /*
    |--------------------------------------------------------------------------
    | Label
    |--------------------------------------------------------------------------
    */

    .bh-bottom-navigation__label {

        display:
            block;

        max-width:
            100%;

        overflow:
            hidden;

        color:
            inherit;

        font-family:
            -apple-system,
            BlinkMacSystemFont,
            "Segoe UI",
            Roboto,
            Arial,
            sans-serif;

        font-size:
            9px;

        font-weight:
            650;

        line-height:
            1.2;

        letter-spacing:
            -.01em;

        text-overflow:
            ellipsis;

        white-space:
            nowrap;

    }


    .bh-bottom-navigation__item.is-active
    .bh-bottom-navigation__label {

        font-weight:
            800;

    }


    /*
    |--------------------------------------------------------------------------
    | Offline Status Dot
    |--------------------------------------------------------------------------
    */

    .bh-bottom-navigation__offline-dot {

        position:
            absolute;

        top:
            4px;

        right:
            calc(
                50%
                -
                20px
            );

        display:
            none;

        width:
            6px;

        height:
            6px;

        border:
            1.5px solid
            white;

        border-radius:
            999px;

        background:
            #f06535;

    }


    body.bh-is-offline
    .bh-bottom-navigation__offline-dot {

        display:
            block;

    }


    /*
    |--------------------------------------------------------------------------
    | Offline Toast
    |--------------------------------------------------------------------------
    */

    #bhBottomNavToast {

        position:
            fixed;

        left:
            50%;

        bottom:
            calc(
                84px +
                env(safe-area-inset-bottom)
            );

        z-index:
            99999;

        width:
            calc(
                100%
                -
                32px
            );

        max-width:
            390px;

        padding:
            11px
            14px;

        border-radius:
            13px;

        transform:
            translate(
                -50%,
                20px
            );

        opacity:
            0;

        visibility:
            hidden;

        color:
            #ffffff;

        background:
            #1a382b;

        box-shadow:
            0 12px 32px
            rgba(
                0,
                0,
                0,
                .18
            );

        font-family:
            -apple-system,
            BlinkMacSystemFont,
            "Segoe UI",
            Roboto,
            Arial,
            sans-serif;

        font-size:
            10px;

        font-weight:
            600;

        line-height:
            1.5;

        text-align:
            center;

        pointer-events:
            none;

        transition:
            opacity .25s ease,
            visibility .25s ease,
            transform .25s ease;

    }


    #bhBottomNavToast.show {

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


    /*
    |--------------------------------------------------------------------------
    | Larger Screens
    |--------------------------------------------------------------------------
    */

    @media (
        min-width:
            640px
    ) {

        .bh-bottom-navigation {

            padding-left:
                20px;

            padding-right:
                20px;

        }


        .bh-bottom-navigation__container {

            max-width:
                520px;

        }


        .bh-bottom-navigation__item {

            min-height:
                58px;

        }


        .bh-bottom-navigation__label {

            font-size:
                10px;

        }

    }

</style>



{{-- ========================================================= --}}
{{-- OFFLINE TOAST --}}
{{-- ========================================================= --}}

<div
    id="bhBottomNavToast"
    role="status"
    aria-live="polite"
>
    Halaman ini belum tersedia offline.
</div>



{{-- ========================================================= --}}
{{-- NAVIGATION --}}
{{-- ========================================================= --}}

<nav
    id="portal-bottom-navigation"
    class="bh-bottom-navigation"
    aria-label="Navigasi utama BaliHiking"
>

    <div
        class="bh-bottom-navigation__container"
    >


        {{-- ===================================================== --}}
        {{-- BERANDA --}}
        {{-- ===================================================== --}}

        <a
            href="{{ $dashboardUrl }}"
            data-bh-navigation
            class="
                bh-bottom-navigation__item
                {{ $isDashboard ? 'is-active' : '' }}
            "
            @if($isDashboard)
                aria-current="page"
            @endif
        >

            <span
                class="bh-bottom-navigation__offline-dot"
            ></span>


            <span
                class="bh-bottom-navigation__icon"
            >

                <span
                    class="bh-bottom-navigation__active-dot"
                ></span>


                <svg
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="{{ $isDashboard ? '2.4' : '2' }}"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 10.75 12 3l9 7.75"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5.25 9.75v9A2.25 2.25 0 007.5 21h9a2.25 2.25 0 002.25-2.25v-9"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.75 21v-6.25h4.5V21"
                    />

                </svg>

            </span>


            <span
                class="bh-bottom-navigation__label"
            >
                Beranda
            </span>

        </a>



        {{-- ===================================================== --}}
        {{-- SIMAKSI --}}
        {{-- ===================================================== --}}

        <a
            href="{{ $simaksiUrl }}"
            data-bh-navigation
            class="
                bh-bottom-navigation__item
                {{ $isSimaksi ? 'is-active' : '' }}
            "
            @if($isSimaksi)
                aria-current="page"
            @endif
        >

            <span
                class="bh-bottom-navigation__offline-dot"
            ></span>


            <span
                class="bh-bottom-navigation__icon"
            >

                <span
                    class="bh-bottom-navigation__active-dot"
                ></span>


                <svg
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="{{ $isSimaksi ? '2.4' : '2' }}"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 5.25H6.75A1.75 1.75 0 005 7v12a1.75 1.75 0 001.75 1.75h10.5A1.75 1.75 0 0019 19V7a1.75 1.75 0 00-1.75-1.75H15"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 5.5V4.75A1.75 1.75 0 0110.75 3h2.5A1.75 1.75 0 0115 4.75v.75A1.5 1.5 0 0113.5 7h-3A1.5 1.5 0 019 5.5Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8.5 11h7M8.5 15h5"
                    />

                </svg>

            </span>


            <span
                class="bh-bottom-navigation__label"
            >
                SIMAKSI
            </span>

        </a>



        {{-- ===================================================== --}}
        {{-- LIVE TRACK --}}
        {{-- ===================================================== --}}

        <a
            href="{{ $liveTrackUrl }}"
            data-bh-navigation
            class="
                bh-bottom-navigation__item
                {{ $isLiveTrack ? 'is-active' : '' }}
            "
            @if($isLiveTrack)
                aria-current="page"
            @endif
        >

            <span
                class="bh-bottom-navigation__offline-dot"
            ></span>


            <span
                class="bh-bottom-navigation__icon"
            >

                <span
                    class="bh-bottom-navigation__active-dot"
                ></span>


                <svg
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="{{ $isLiveTrack ? '2.4' : '2' }}"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 18.75 3.75 21V5.25L9 3l6 2.25L20.25 3v15.75L15 21l-6-2.25Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 3v15.75M15 5.25V21"
                    />

                    <circle
                        cx="12"
                        cy="11"
                        r="2"
                    />

                </svg>

            </span>


            <span
                class="bh-bottom-navigation__label"
            >
                Peta Live
            </span>

        </a>



        {{-- ===================================================== --}}
        {{-- PROFIL --}}
        {{-- ===================================================== --}}

        <a
            href="{{ $profilUrl }}"
            data-bh-navigation
            class="
                bh-bottom-navigation__item
                {{ $isProfil ? 'is-active' : '' }}
            "
            @if($isProfil)
                aria-current="page"
            @endif
        >

            <span
                class="bh-bottom-navigation__offline-dot"
            ></span>


            <span
                class="bh-bottom-navigation__icon"
            >

                <span
                    class="bh-bottom-navigation__active-dot"
                ></span>


                <svg
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="{{ $isProfil ? '2.4' : '2' }}"
                >

                    <circle
                        cx="12"
                        cy="8"
                        r="3.25"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5.25 20.25a6.75 6.75 0 0113.5 0"
                    />

                </svg>

            </span>


            <span
                class="bh-bottom-navigation__label"
            >
                Profil
            </span>

        </a>


    </div>

</nav>



{{-- ========================================================= --}}
{{-- OFFLINE NAVIGATION SUPPORT --}}
{{-- ========================================================= --}}

<script>

    /*
    |--------------------------------------------------------------------------
    | BaliHiking Bottom Navigation
    |--------------------------------------------------------------------------
    |
    | - Tidak mengganggu navigasi ketika online.
    | - Ketika offline, halaman hanya dibuka kalau sudah tersedia di Cache.
    | - Jika belum pernah dibuka, tampilkan pesan sederhana.
    |
    */


    (function () {

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate initialization
        |--------------------------------------------------------------------------
        */

        if (
            window.__baliHikingBottomNavigationLoaded
        ) {

            return;

        }


        window.__baliHikingBottomNavigationLoaded =
            true;


        let bottomNavigationToastTimer =
            null;



        /*
        |--------------------------------------------------------------------------
        | CONNECTION STATE
        |--------------------------------------------------------------------------
        */

        function updateBottomNavigationConnection() {

            document.body
                .classList
                .toggle(
                    'bh-is-offline',
                    !navigator.onLine
                );

        }



        /*
        |--------------------------------------------------------------------------
        | TOAST
        |--------------------------------------------------------------------------
        */

        function showBottomNavigationToast(
            message
        ) {

            const toast =
                document.getElementById(
                    'bhBottomNavToast'
                );


            if (
                !toast
            ) {

                return;

            }


            clearTimeout(
                bottomNavigationToastTimer
            );


            toast.textContent =
                message;


            toast
                .classList
                .add(
                    'show'
                );


            bottomNavigationToastTimer =
                setTimeout(
                    function () {

                        toast
                            .classList
                            .remove(
                                'show'
                            );

                    },
                    3300
                );

        }



        /*
        |--------------------------------------------------------------------------
        | CHECK CACHE
        |--------------------------------------------------------------------------
        */

        async function baliHikingPageAvailableOffline(
            href
        ) {

            /*
            |--------------------------------------------------------------------------
            | Browser tanpa Cache Storage.
            |--------------------------------------------------------------------------
            */

            if (
                !('caches' in window)
            ) {

                return false;

            }


            try {

                /*
                |--------------------------------------------------------------------------
                | Absolute URL
                |--------------------------------------------------------------------------
                */

                let cachedResponse =
                    await caches.match(
                        href
                    );


                if (
                    cachedResponse
                ) {

                    return true;

                }



                /*
                |--------------------------------------------------------------------------
                | Path + Query
                |--------------------------------------------------------------------------
                */

                const url =
                    new URL(
                        href,
                        window.location.origin
                    );


                cachedResponse =
                    await caches.match(
                        url.pathname
                        +
                        url.search
                    );


                if (
                    cachedResponse
                ) {

                    return true;

                }



                /*
                |--------------------------------------------------------------------------
                | Path tanpa query.
                |--------------------------------------------------------------------------
                */

                cachedResponse =
                    await caches.match(
                        url.pathname
                    );


                if (
                    cachedResponse
                ) {

                    return true;

                }



                /*
                |--------------------------------------------------------------------------
                | Cari seluruh cache.
                |--------------------------------------------------------------------------
                */

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


                    let response =
                        await cache.match(
                            href,
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
                            url.pathname,
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

                console.warn(
                    '[BaliHiking BottomNav] Cache check gagal:',
                    error
                );


                return false;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | NAVIGATION
        |--------------------------------------------------------------------------
        */

        function setupBottomNavigationLinks() {

            document
                .querySelectorAll(
                    '[data-bh-navigation]'
                )
                .forEach(
                    function (
                        link
                    ) {

                        if (
                            link.dataset
                                .bhNavigationReady ===
                            '1'
                        ) {

                            return;

                        }


                        link.dataset
                            .bhNavigationReady =
                                '1';


                        link
                            .addEventListener(
                                'click',
                                async function (
                                    event
                                ) {

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Online
                                    |--------------------------------------------------------------------------
                                    */

                                    if (
                                        navigator.onLine
                                    ) {

                                        return;

                                    }



                                    /*
                                    |--------------------------------------------------------------------------
                                    | Current URL
                                    |--------------------------------------------------------------------------
                                    */

                                    const current =
                                        new URL(
                                            window.location.href
                                        );


                                    const target =
                                        new URL(
                                            link.href,
                                            window.location.origin
                                        );


                                    if (
                                        current.pathname ===
                                            target.pathname
                                        &&
                                        current.search ===
                                            target.search
                                    ) {

                                        event.preventDefault();

                                        return;

                                    }



                                    /*
                                    |--------------------------------------------------------------------------
                                    | Offline
                                    |--------------------------------------------------------------------------
                                    */

                                    event.preventDefault();


                                    const available =
                                        await baliHikingPageAvailableOffline(
                                            link.href
                                        );


                                    if (
                                        available
                                    ) {

                                        window.location.href =
                                            link.href;


                                        return;

                                    }


                                    showBottomNavigationToast(
                                        'Halaman ini belum tersedia offline. Buka minimal satu kali saat internet aktif agar BaliHiking dapat menyimpannya.'
                                    );

                                }
                            );

                    }
                );

        }



        /*
        |--------------------------------------------------------------------------
        | START
        |--------------------------------------------------------------------------
        */

        function startBottomNavigation() {

            updateBottomNavigationConnection();

            setupBottomNavigationLinks();

        }


        if (
            document.readyState ===
                'loading'
        ) {

            document
                .addEventListener(
                    'DOMContentLoaded',
                    startBottomNavigation
                );


        } else {


            startBottomNavigation();

        }



        /*
        |--------------------------------------------------------------------------
        | NETWORK EVENTS
        |--------------------------------------------------------------------------
        */

        window
            .addEventListener(
                'online',
                function () {

                    updateBottomNavigationConnection();

                }
            );


        window
            .addEventListener(
                'offline',
                function () {

                    updateBottomNavigationConnection();

                    showBottomNavigationToast(
                        'Mode offline aktif. BaliHiking akan menggunakan halaman yang tersimpan di perangkat.'
                    );

                }
            );

    })();

</script>