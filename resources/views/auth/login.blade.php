<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Masuk - BaliHiking</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

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
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-jalur-background text-jalur-text antialiased">

    <main class="min-h-screen lg:grid lg:grid-cols-2">

        {{--
        |--------------------------------------------------------------------------
        | LEFT SIDE - DESKTOP
        |--------------------------------------------------------------------------
        --}}

        <section class="relative hidden min-h-screen overflow-hidden lg:block">

            {{-- Background --}}
            <img
                src="https://images.unsplash.com/photo-1593332956867-9d99a3bb9109?q=85&w=1600&auto=format&fit=crop"
                alt="Gunung di Bali"
                class="absolute inset-0 h-full w-full object-cover"
            >

            {{-- Simple overlay --}}
            <div class="absolute inset-0 bg-black/30"></div>

            {{-- Header --}}
            <div class="absolute left-0 right-0 top-0 z-10 p-10 xl:p-12">

                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-white"
                >
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15 backdrop-blur-sm"
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

                    <span class="text-lg font-semibold">
                        BaliHiking
                    </span>
                </a>

            </div>

            {{-- Bottom content --}}
            <div class="absolute bottom-0 left-0 right-0 z-10 p-10 xl:p-12">

                <div class="max-w-lg">

                    <p class="mb-3 text-sm font-medium text-white/80">
                        Jelajahi jalur pendakian Bali
                    </p>

                    <h1 class="text-4xl font-semibold leading-tight text-white xl:text-5xl">
                        Informasi pendakian
                        <br>
                        dalam satu tempat.
                    </h1>

                    <p class="mt-5 max-w-md text-sm leading-6 text-white/80">
                        Lihat informasi jalur, pantau perjalanan, dan akses fitur
                        pendakian dengan lebih mudah.
                    </p>

                </div>

            </div>

        </section>


        {{--
        |--------------------------------------------------------------------------
        | RIGHT SIDE - LOGIN
        |--------------------------------------------------------------------------
        --}}

        <section class="flex min-h-screen flex-col bg-white lg:bg-jalur-background">

            {{--
            |--------------------------------------------------------------------------
            | MOBILE HEADER
            |--------------------------------------------------------------------------
            --}}

            <header class="border-b border-gray-100 bg-white px-5 py-4 lg:hidden">

                <div class="mx-auto flex max-w-md items-center justify-between">

                    <a
                        href="{{ route('home') }}"
                        class="flex items-center gap-2"
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

                        <span class="font-semibold text-jalur-green">
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

                <div class="w-full max-w-[420px]">

                    {{--
                    |--------------------------------------------------------------------------
                    | DESKTOP BRAND
                    |--------------------------------------------------------------------------
                    --}}

                    <div class="mb-10 hidden lg:block">

                        <a
                            href="{{ route('home') }}"
                            class="inline-flex items-center gap-2"
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

                            <span class="font-semibold text-jalur-green">
                                BaliHiking
                            </span>
                        </a>

                    </div>


                    {{--
                    |--------------------------------------------------------------------------
                    | TITLE
                    |--------------------------------------------------------------------------
                    --}}

                    <div class="mb-7">

                        <h2 class="text-2xl font-semibold tracking-tight text-gray-900 sm:text-[28px]">
                            Masuk ke akun Anda
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Gunakan akun yang sudah terdaftar untuk melanjutkan ke
                            portal pendaki.
                        </p>

                    </div>


                    {{--
                    |--------------------------------------------------------------------------
                    | FLASH MESSAGE
                    |--------------------------------------------------------------------------
                    --}}

                    @if (session('error'))

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

                            <div class="text-sm text-red-700">
                                {{ session('error') }}
                            </div>

                        </div>

                    @endif


                    @if (session('success'))

                        <div
                            class="mb-5 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3"
                        >

                            <svg
                                class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m4.5 12.75 6 6 9-13.5"
                                />
                            </svg>

                            <div class="text-sm text-emerald-700">
                                {{ session('success') }}
                            </div>

                        </div>

                    @endif


                    {{--
                    |--------------------------------------------------------------------------
                    | GOOGLE LOGIN
                    |--------------------------------------------------------------------------
                    --}}

                    <a
                        href="{{ route('pendaki.google.redirect') }}"
                        class="flex h-12 w-full items-center justify-center gap-3 rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 transition hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-jalur-green/20"
                    >

                        {{-- Official-like Google G --}}
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
                            Masuk dengan Google
                        </span>

                    </a>


                    {{--
                    |--------------------------------------------------------------------------
                    | DIVIDER
                    |--------------------------------------------------------------------------
                    --}}

                    <div class="my-6 flex items-center gap-4">

                        <div class="h-px flex-1 bg-gray-200"></div>

                        <span class="text-xs text-gray-400">
                            atau gunakan email
                        </span>

                        <div class="h-px flex-1 bg-gray-200"></div>

                    </div>


                    {{--
                    |--------------------------------------------------------------------------
                    | LOGIN FORM
                    |--------------------------------------------------------------------------
                    --}}

                    <form
                        method="POST"
                        action="{{ route('pendaki.login') }}"
                        class="space-y-5"
                    >

                        @csrf


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
                                autofocus
                                autocomplete="email"
                                placeholder="nama@email.com"
                                class="
                                    block h-12 w-full rounded-lg border bg-white px-3.5 text-sm
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

                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- PASSWORD --}}
                        <div>

                            <div class="mb-2 flex items-center justify-between">

                                <label
                                    for="password"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Kata sandi
                                </label>

                                @if (Route::has('password.request'))

                                    <a
                                        href="{{ route('password.request') }}"
                                        class="text-xs font-medium text-jalur-orange transition hover:underline"
                                    >
                                        Lupa kata sandi?
                                    </a>

                                @endif

                            </div>


                            <div class="relative">

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan kata sandi"
                                    class="
                                        block h-12 w-full rounded-lg border bg-white
                                        px-3.5 pr-11 text-sm text-gray-900
                                        outline-none transition
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
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-400 transition hover:text-gray-600"
                                    aria-label="Tampilkan kata sandi"
                                >

                                    {{-- Eye --}}
                                    <svg
                                        id="eyeOpen"
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


                                    {{-- Eye Slash --}}
                                    <svg
                                        id="eyeClosed"
                                        class="hidden h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.98 8.223A10.477 10.477 0 002.036 12.322a1.012 1.012 0 000 .639C3.423 17.134 7.36 20.145 12 20.145c1.11 0 2.178-.172 3.18-.491M6.228 6.228A10.451 10.451 0 0112 4.855c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639a10.45 10.45 0 01-2.293 4.033M6.228 6.228 3 3m3.228 3.228 3.65 3.65m6.244 6.244L21 21m-4.878-4.878-3.65-3.65m0 0a3 3 0 10-4.243-4.243"
                                        />
                                    </svg>

                                </button>

                            </div>

                            @error('password')

                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- REMEMBER --}}
                        <div class="flex items-center">

                            <label class="flex cursor-pointer items-center gap-2.5">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    class="h-4 w-4 rounded border-gray-300 text-jalur-green focus:ring-jalur-green"
                                >

                                <span class="text-sm text-gray-600">
                                    Ingat saya
                                </span>

                            </label>

                        </div>


                        {{-- LOGIN BUTTON --}}
                        <button
                            type="submit"
                            id="loginButton"
                            class="flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-jalur-green px-4 text-sm font-semibold text-white transition hover:bg-jalur-greenLight focus:outline-none focus:ring-2 focus:ring-jalur-green/30 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70"
                        >

                            <svg
                                id="loginSpinner"
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
                                >
                                </circle>

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"
                                >
                                </path>
                            </svg>

                            <span id="loginButtonText">
                                Masuk
                            </span>

                        </button>

                    </form>


                    {{--
                    |--------------------------------------------------------------------------
                    | REGISTER
                    |--------------------------------------------------------------------------
                    --}}

                    <p class="mt-7 text-center text-sm text-gray-500">

                        Belum punya akun?

                        <a
                            href="{{ route('register') }}"
                            class="font-semibold text-jalur-orange transition hover:underline"
                        >
                            Daftar sekarang
                        </a>

                    </p>


                    {{--
                    |--------------------------------------------------------------------------
                    | BACK HOME MOBILE
                    |--------------------------------------------------------------------------
                    --}}

                    <div class="mt-8 border-t border-gray-100 pt-6 text-center lg:hidden">

                        <a
                            href="{{ route('home') }}"
                            class="text-xs font-medium text-gray-400 transition hover:text-gray-600"
                        >
                            ← Kembali ke beranda
                        </a>

                    </div>

                </div>

            </div>


            {{--
            |--------------------------------------------------------------------------
            | FOOTER DESKTOP
            |--------------------------------------------------------------------------
            --}}

            <footer class="hidden px-12 pb-8 lg:block xl:px-20">

                <div class="mx-auto max-w-[420px]">

                    <p class="text-xs text-gray-400">
                        © {{ date('Y') }} BaliHiking
                    </p>

                </div>

            </footer>

        </section>

    </main>


    {{--
    |--------------------------------------------------------------------------
    | JAVASCRIPT
    |--------------------------------------------------------------------------
    --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | SHOW / HIDE PASSWORD
            |--------------------------------------------------------------------------
            */

            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (
                passwordInput &&
                togglePassword &&
                eyeOpen &&
                eyeClosed
            ) {

                togglePassword.addEventListener('click', function () {

                    const isPassword =
                        passwordInput.type === 'password';

                    passwordInput.type =
                        isPassword ? 'text' : 'password';

                    eyeOpen.classList.toggle(
                        'hidden',
                        isPassword
                    );

                    eyeClosed.classList.toggle(
                        'hidden',
                        !isPassword
                    );

                    togglePassword.setAttribute(
                        'aria-label',
                        isPassword
                            ? 'Sembunyikan kata sandi'
                            : 'Tampilkan kata sandi'
                    );

                });

            }


            /*
            |--------------------------------------------------------------------------
            | LOGIN LOADING
            |--------------------------------------------------------------------------
            */

            const loginForm =
                document.querySelector(
                    'form[action="{{ route('pendaki.login') }}"]'
                );

            const loginButton =
                document.getElementById('loginButton');

            const loginSpinner =
                document.getElementById('loginSpinner');

            const loginButtonText =
                document.getElementById('loginButtonText');


            if (
                loginForm &&
                loginButton &&
                loginSpinner &&
                loginButtonText
            ) {

                loginForm.addEventListener('submit', function () {

                    loginButton.disabled = true;

                    loginSpinner.classList.remove('hidden');

                    loginButtonText.textContent =
                        'Memproses...';

                });

            }

        });
    </script>

</body>

</html>