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
        name="application-name"
        content="BaliHiking"
    >

    <meta
        name="apple-mobile-web-app-title"
        content="BaliHiking"
    >

    <meta
        name="apple-mobile-web-app-capable"
        content="yes"
    >

    <title>
        Pengaturan Akun - BaliHiking
    </title>


    <link
        rel="manifest"
        href="{{ asset('manifest.webmanifest') }}"
    >

    <link
        rel="icon"
        href="{{ asset('icons/icon-192.png') }}"
    >

    <link
        rel="apple-touch-icon"
        href="{{ asset('icons/icon-192.png') }}"
    >


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

                    }

                }

            }

        };

    </script>


    <style>

        body {

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background:
                #fbfbfa;

        }


        #offlineBanner {

            display:
                none;

        }


        #offlineBanner.show {

            display:
                flex;

        }

    </style>

</head>


<body
    class="
        min-h-screen
        bg-brand-cream
        pb-10
        text-brand-dark
        antialiased
    "
>


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
                max-w-md
                items-center
                justify-between
                px-4
                sm:max-w-xl
            "
        >

            <a
                href="{{ route('pendaki.profil') }}"
                class="
                    -ml-2
                    flex
                    h-10
                    w-10
                    items-center
                    justify-center
                    rounded-xl
                    hover:bg-brand-dark/5
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
                    class="
                        h-8
                        w-8
                        rounded-lg
                    "
                    alt="BaliHiking"
                >


                <div>

                    <h1
                        class="
                            text-sm
                            font-black
                        "
                    >
                        Pengaturan Akun
                    </h1>


                    <p
                        class="
                            text-[9px]
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
            max-w-md
            space-y-5
            px-4
            py-6
            sm:max-w-xl
        "
    >


        {{-- OFFLINE --}}

        <div
            id="offlineBanner"
            class="
                items-start
                gap-3
                rounded-2xl
                border
                border-orange-200
                bg-orange-50
                p-4
            "
        >

            <div
                class="
                    mt-1
                    h-2
                    w-2
                    shrink-0
                    rounded-full
                    bg-brand-orange
                "
            ></div>


            <div>

                <p
                    class="
                        text-xs
                        font-black
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
                        text-orange-700
                    "
                >
                    Informasi akun masih dapat dilihat,
                    tetapi perubahan nama, email, dan
                    password membutuhkan internet.
                </p>

            </div>

        </div>



        {{-- SUCCESS --}}

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



        @if(session('password_success'))

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
                {{ session('password_success') }}
            </div>

        @endif



        {{-- ===================================================== --}}
        {{-- PROFILE --}}
        {{-- ===================================================== --}}

        <section
            class="
                rounded-3xl
                border
                border-brand-dark/10
                bg-white
                p-6
                shadow-sm
            "
        >

            <div
                class="mb-6"
            >

                <span
                    class="
                        text-[10px]
                        font-black
                        uppercase
                        tracking-widest
                        text-brand-orange
                    "
                >
                    Data Akun
                </span>


                <h2
                    class="
                        mt-1
                        text-lg
                        font-black
                    "
                >
                    Informasi Profil
                </h2>


                <p
                    class="
                        mt-1
                        text-xs
                        text-brand-dark/50
                    "
                >
                    Perbarui nama dan email akun BaliHiking Anda.
                </p>

            </div>



            <form
                method="POST"
                action="{{ route('pendaki.profil.account.update') }}"
                id="accountForm"
                class="space-y-4"
            >

                @csrf
                @method('PUT')


                {{-- NAME --}}

                <div>

                    <label
                        for="name"
                        class="
                            mb-1.5
                            block
                            text-xs
                            font-bold
                        "
                    >
                        Nama Lengkap
                    </label>


                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="
                            h-12
                            w-full
                            rounded-xl
                            border
                            border-brand-dark/15
                            bg-brand-cream
                            px-3.5
                            text-sm
                            outline-none
                            transition
                            focus:border-brand-orange
                            focus:ring-1
                            focus:ring-brand-orange
                        "
                    >


                    @error('name')

                        <p
                            class="
                                mt-1.5
                                text-[10px]
                                text-red-600
                            "
                        >
                            {{ $message }}
                        </p>

                    @enderror

                </div>



                {{-- EMAIL --}}

                <div>

                    <label
                        for="email"
                        class="
                            mb-1.5
                            block
                            text-xs
                            font-bold
                        "
                    >
                        Email
                    </label>


                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="
                            h-12
                            w-full
                            rounded-xl
                            border
                            border-brand-dark/15
                            bg-brand-cream
                            px-3.5
                            text-sm
                            outline-none
                            transition
                            focus:border-brand-orange
                            focus:ring-1
                            focus:ring-brand-orange
                        "
                    >


                    @error('email')

                        <p
                            class="
                                mt-1.5
                                text-[10px]
                                text-red-600
                            "
                        >
                            {{ $message }}
                        </p>

                    @enderror

                </div>



                <button
                    type="submit"
                    data-online-only
                    class="
                        mt-2
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
                        transition
                        active:scale-[0.98]
                        disabled:cursor-not-allowed
                        disabled:opacity-50
                    "
                >
                    Simpan Perubahan
                </button>

            </form>

        </section>



        {{-- ===================================================== --}}
        {{-- PASSWORD --}}
        {{-- ===================================================== --}}

        <section
            class="
                rounded-3xl
                border
                border-brand-dark/10
                bg-white
                p-6
                shadow-sm
            "
        >

            <div
                class="mb-6"
            >

                <span
                    class="
                        text-[10px]
                        font-black
                        uppercase
                        tracking-widest
                        text-brand-orange
                    "
                >
                    Keamanan
                </span>


                <h2
                    class="
                        mt-1
                        text-lg
                        font-black
                    "
                >
                    Ganti Password
                </h2>


                <p
                    class="
                        mt-1
                        text-xs
                        text-brand-dark/50
                    "
                >
                    Gunakan password baru minimal 8 karakter.
                </p>

            </div>



            <form
                method="POST"
                action="{{ route('pendaki.profil.password.update') }}"
                class="space-y-4"
            >

                @csrf
                @method('PUT')



                <div>

                    <label
                        class="
                            mb-1.5
                            block
                            text-xs
                            font-bold
                        "
                    >
                        Password Saat Ini
                    </label>


                    <input
                        type="password"
                        name="current_password"
                        required
                        autocomplete="current-password"
                        class="
                            h-12
                            w-full
                            rounded-xl
                            border
                            border-brand-dark/15
                            bg-brand-cream
                            px-3.5
                            text-sm
                            outline-none
                            focus:border-brand-orange
                            focus:ring-1
                            focus:ring-brand-orange
                        "
                    >


                    @error('current_password')

                        <p
                            class="
                                mt-1.5
                                text-[10px]
                                text-red-600
                            "
                        >
                            {{ $message }}
                        </p>

                    @enderror

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
                        Password Baru
                    </label>


                    <input
                        type="password"
                        name="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        class="
                            h-12
                            w-full
                            rounded-xl
                            border
                            border-brand-dark/15
                            bg-brand-cream
                            px-3.5
                            text-sm
                            outline-none
                            focus:border-brand-orange
                            focus:ring-1
                            focus:ring-brand-orange
                        "
                    >


                    @error('password')

                        <p
                            class="
                                mt-1.5
                                text-[10px]
                                text-red-600
                            "
                        >
                            {{ $message }}
                        </p>

                    @enderror

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
                        Konfirmasi Password Baru
                    </label>


                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        class="
                            h-12
                            w-full
                            rounded-xl
                            border
                            border-brand-dark/15
                            bg-brand-cream
                            px-3.5
                            text-sm
                            outline-none
                            focus:border-brand-orange
                            focus:ring-1
                            focus:ring-brand-orange
                        "
                    >

                </div>



                <button
                    type="submit"
                    data-online-only
                    class="
                        mt-2
                        flex
                        h-12
                        w-full
                        items-center
                        justify-center
                        rounded-xl
                        bg-brand-dark
                        text-xs
                        font-bold
                        text-white
                        transition
                        active:scale-[0.98]
                        disabled:cursor-not-allowed
                        disabled:opacity-50
                    "
                >
                    Perbarui Password
                </button>

            </form>

        </section>


    </main>



    {{-- ========================================================= --}}
    {{-- OFFLINE --}}
    {{-- ========================================================= --}}

    <script>

        function updateConnectionState() {

            const online =
                navigator.onLine;


            document
                .getElementById(
                    'offlineBanner'
                )
                .classList
                .toggle(
                    'show',
                    !online
                );


            document
                .querySelectorAll(
                    '[data-online-only]'
                )
                .forEach(
                    function (
                        button
                    ) {

                        button.disabled =
                            !online;

                    }
                );

        }


        updateConnectionState();


        window.addEventListener(
            'online',
            updateConnectionState
        );


        window.addEventListener(
            'offline',
            updateConnectionState
        );


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

    </script>


</body>
</html>