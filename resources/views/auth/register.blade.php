<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Akun - Jalur Bali</title>

    {{-- Tailwind --}}
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

            {{-- Background Image --}}
            <img
                src="https://images.unsplash.com/photo-1593332956867-9d99a3bb9109?q=85&w=1600&auto=format&fit=crop"
                alt="Pendakian gunung di Bali"
                class="absolute inset-0 h-full w-full object-cover"
            >

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/35"></div>

            {{-- Brand --}}
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
                        Jalur Bali
                    </span>

                </a>

            </div>


            {{-- Description --}}
            <div class="absolute bottom-0 left-0 right-0 z-10 p-10 xl:p-12">

                <div class="max-w-lg">

                    <p class="mb-3 text-sm font-medium text-white/80">
                        Portal Pendaki
                    </p>

                    <h1 class="text-4xl font-semibold leading-tight text-white xl:text-5xl">
                        Mulai perjalanan
                        <br>
                        pendakian Anda.
                    </h1>

                    <p class="mt-5 max-w-md text-sm leading-6 text-white/80">
                        Daftar untuk mengakses informasi jalur, SIMAKSI,
                        live tracking, dan fitur pendukung keselamatan pendakian.
                    </p>

                </div>

            </div>

        </section>


        {{--
        |--------------------------------------------------------------------------
        | RIGHT SIDE
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
                            Jalur Bali
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

                <div class="w-full max-w-[430px]">

                    {{--
                    |--------------------------------------------------------------------------
                    | DESKTOP BRAND
                    |--------------------------------------------------------------------------
                    --}}

                    <div class="mb-8 hidden lg:block">

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
                                Jalur Bali
                            </span>

                        </a>

                    </div>


                    {{--
                    |--------------------------------------------------------------------------
                    | TITLE
                    |--------------------------------------------------------------------------
                    --}}

                    <div class="mb-6">

                        <h2
                            class="text-2xl font-semibold tracking-tight text-gray-900 sm:text-[28px]"
                        >
                            Buat akun pendaki
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Lengkapi data berikut untuk membuat akun Jalur Bali.
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

                                <p class="text-sm font-medium text-red-700">
                                    Pendaftaran belum berhasil.
                                </p>

                                <p class="mt-1 text-xs text-red-600">
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


                    {{--
                    |--------------------------------------------------------------------------
                    | DIVIDER
                    |--------------------------------------------------------------------------
                    --}}

                    <div class="my-5 flex items-center gap-4">

                        <div class="h-px flex-1 bg-gray-200"></div>

                        <span class="whitespace-nowrap text-xs text-gray-400">
                            atau daftar dengan email
                        </span>

                        <div class="h-px flex-1 bg-gray-200"></div>

                    </div>


                    {{--
                    |--------------------------------------------------------------------------
                    | FORM REGISTER
                    |--------------------------------------------------------------------------
                    --}}

                    <form
                        method="POST"
                        action="{{ route('pendaki.register') }}"
                        id="registerForm"
                        class="space-y-4"
                    >

                        @csrf


                        {{--
                        |--------------------------------------------------------------------------
                        | NAME
                        |--------------------------------------------------------------------------
                        --}}

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

                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{--
                        |--------------------------------------------------------------------------
                        | EMAIL
                        |--------------------------------------------------------------------------
                        --}}

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

                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{--
                        |--------------------------------------------------------------------------
                        | PASSWORD
                        |--------------------------------------------------------------------------
                        --}}

                        <div>

                            <label
                                for="password"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Kata sandi
                            </label>

                            <div class="relative">

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

                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{--
                        |--------------------------------------------------------------------------
                        | PASSWORD CONFIRMATION
                        |--------------------------------------------------------------------------
                        --}}

                        <div>

                            <label
                                for="password_confirmation"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Konfirmasi kata sandi
                            </label>

                            <div class="relative">

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
                            >
                            </p>

                        </div>


                        {{--
                        |--------------------------------------------------------------------------
                        | SUBMIT
                        |--------------------------------------------------------------------------
                        --}}

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
                                >
                                </circle>

                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"
                                >
                                </path>

                            </svg>

                            <span id="registerButtonText">
                                Buat akun
                            </span>

                        </button>

                    </form>


                    {{--
                    |--------------------------------------------------------------------------
                    | LOGIN LINK
                    |--------------------------------------------------------------------------
                    --}}

                    <p class="mt-6 text-center text-sm text-gray-500">

                        Sudah punya akun?

                        <a
                            href="{{ route('login') }}"
                            class="font-semibold text-jalur-orange transition hover:underline"
                        >
                            Masuk
                        </a>

                    </p>


                    {{--
                    |--------------------------------------------------------------------------
                    | MOBILE BACK HOME
                    |--------------------------------------------------------------------------
                    --}}

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


            {{--
            |--------------------------------------------------------------------------
            | FOOTER
            |--------------------------------------------------------------------------
            --}}

            <footer class="hidden px-12 pb-8 lg:block xl:px-20">

                <div class="mx-auto max-w-[430px]">

                    <p class="text-xs text-gray-400">
                        © {{ date('Y') }} Jalur Bali
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

            document
                .querySelectorAll('[data-password-toggle]')
                .forEach(function (button) {

                    button.addEventListener('click', function () {

                        const inputId =
                            button.dataset.passwordToggle;

                        const eyeOpenId =
                            button.dataset.eyeOpen;

                        const eyeClosedId =
                            button.dataset.eyeClosed;

                        const input =
                            document.getElementById(inputId);

                        const eyeOpen =
                            document.getElementById(eyeOpenId);

                        const eyeClosed =
                            document.getElementById(eyeClosedId);


                        if (
                            !input ||
                            !eyeOpen ||
                            !eyeClosed
                        ) {
                            return;
                        }


                        const hidden =
                            input.type === 'password';


                        input.type =
                            hidden
                                ? 'text'
                                : 'password';


                        eyeOpen.classList.toggle(
                            'hidden',
                            hidden
                        );


                        eyeClosed.classList.toggle(
                            'hidden',
                            !hidden
                        );

                    });

                });


            /*
            |--------------------------------------------------------------------------
            | PASSWORD MATCH
            |--------------------------------------------------------------------------
            */

            const password =
                document.getElementById('password');

            const passwordConfirmation =
                document.getElementById(
                    'password_confirmation'
                );

            const matchMessage =
                document.getElementById(
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
                    passwordConfirmation.value.length === 0
                ) {

                    matchMessage.classList.add('hidden');

                    passwordConfirmation.classList.remove(
                        'border-red-400',
                        'border-emerald-500'
                    );

                    passwordConfirmation.classList.add(
                        'border-gray-300'
                    );

                    return;
                }


                matchMessage.classList.remove('hidden');


                if (
                    password.value ===
                    passwordConfirmation.value
                ) {

                    matchMessage.textContent =
                        'Kata sandi sudah sesuai.';

                    matchMessage.className =
                        'mt-1.5 text-xs text-emerald-600';

                    passwordConfirmation.classList.remove(
                        'border-gray-300',
                        'border-red-400'
                    );

                    passwordConfirmation.classList.add(
                        'border-emerald-500'
                    );

                } else {

                    matchMessage.textContent =
                        'Konfirmasi kata sandi belum sesuai.';

                    matchMessage.className =
                        'mt-1.5 text-xs text-red-600';

                    passwordConfirmation.classList.remove(
                        'border-gray-300',
                        'border-emerald-500'
                    );

                    passwordConfirmation.classList.add(
                        'border-red-400'
                    );

                }

            }


            if (
                password &&
                passwordConfirmation
            ) {

                password.addEventListener(
                    'input',
                    checkPasswordMatch
                );

                passwordConfirmation.addEventListener(
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
                document.getElementById(
                    'registerForm'
                );

            const registerButton =
                document.getElementById(
                    'registerButton'
                );

            const registerSpinner =
                document.getElementById(
                    'registerSpinner'
                );

            const registerButtonText =
                document.getElementById(
                    'registerButtonText'
                );


            if (
                registerForm &&
                registerButton &&
                registerSpinner &&
                registerButtonText
            ) {

                registerForm.addEventListener(
                    'submit',
                    function (event) {

                        /*
                         * Jangan submit kalau konfirmasi
                         * password berbeda.
                         */
                        if (
                            password &&
                            passwordConfirmation &&
                            password.value !==
                            passwordConfirmation.value
                        ) {

                            event.preventDefault();

                            passwordConfirmation.focus();

                            checkPasswordMatch();

                            return;

                        }


                        registerButton.disabled = true;

                        registerSpinner.classList.remove(
                            'hidden'
                        );

                        registerButtonText.textContent =
                            'Membuat akun...';

                    }
                );

            }

        });
    </script>

</body>

</html>