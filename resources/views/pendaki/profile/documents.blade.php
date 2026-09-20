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

    <title>
        Dokumen Identitas - BaliHiking
    </title>


    <link
        rel="manifest"
        href="{{ asset('manifest.webmanifest') }}"
    >


    <link
        rel="icon"
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
                        Dokumen Identitas
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

            <span
                class="
                    mt-1
                    h-2
                    w-2
                    rounded-full
                    bg-brand-orange
                "
            ></span>


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
                    Status dokumen terakhir masih bisa
                    dilihat, tetapi upload dan penghapusan
                    dokumen membutuhkan internet.
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



        @if($errors->any())

            <div
                class="
                    rounded-2xl
                    border
                    border-red-200
                    bg-red-50
                    p-4
                "
            >

                <p
                    class="
                        text-xs
                        font-black
                        text-red-700
                    "
                >
                    Dokumen belum berhasil disimpan
                </p>


                <ul
                    class="
                        mt-2
                        space-y-1
                        text-[10px]
                        text-red-600
                    "
                >

                    @foreach($errors->all() as $error)

                        <li>
                            • {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif



        {{-- ===================================================== --}}
        {{-- INFO --}}
        {{-- ===================================================== --}}

        <section
            class="
                rounded-3xl
                bg-brand-dark
                p-6
                text-white
            "
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
                Identitas Pendaki
            </span>


            <h2
                class="
                    mt-2
                    text-xl
                    font-black
                "
            >
                Lengkapi Dokumen Anda
            </h2>


            <p
                class="
                    mt-2
                    text-xs
                    leading-relaxed
                    text-white/60
                "
            >
                Dokumen identitas digunakan untuk membantu
                proses administrasi dan verifikasi pendaki.
                File disimpan secara private dan hanya dapat
                dibuka oleh akun Anda serta petugas yang berwenang.
            </p>

        </section>



        {{-- ===================================================== --}}
        {{-- DOCUMENT STATUS --}}
        {{-- ===================================================== --}}

        @foreach([
            'ktp' => 'KTP',
            'sim' => 'SIM',
        ] as $type => $label)

            @php

                $document =
                    $documents->get(
                        $type
                    );

            @endphp


            <section
                class="
                    overflow-hidden
                    rounded-3xl
                    border
                    border-brand-dark/10
                    bg-white
                    shadow-sm
                "
            >

                <div
                    class="
                        flex
                        items-start
                        gap-4
                        p-5
                    "
                >

                    <div
                        class="
                            flex
                            h-12
                            w-12
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-brand-dark/5
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
                                d="M5 7.5A2.5 2.5 0 017.5 5h9A2.5 2.5 0 0119 7.5v9a2.5 2.5 0 01-2.5 2.5h-9A2.5 2.5 0 015 16.5v-9Z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 9h4M8 12h2M14.5 11a1.5 1.5 0 100-3 1.5 1.5 0 000 3ZM12.5 15a2 2 0 014 0"
                            />

                        </svg>

                    </div>



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

                            <div>

                                <h3
                                    class="
                                        text-sm
                                        font-black
                                    "
                                >
                                    {{ $label }}
                                </h3>


                                <p
                                    class="
                                        mt-0.5
                                        text-[10px]
                                        text-brand-dark/45
                                    "
                                >

                                    @if($document)

                                        {{ $document->original_name }}

                                    @else

                                        Belum diunggah

                                    @endif

                                </p>

                            </div>



                            @if($document)

                                @if($document->status === 'verified')

                                    <span
                                        class="
                                            rounded-full
                                            bg-emerald-100
                                            px-2.5
                                            py-1
                                            text-[9px]
                                            font-black
                                            text-emerald-700
                                        "
                                    >
                                        Terverifikasi
                                    </span>

                                @elseif($document->status === 'rejected')

                                    <span
                                        class="
                                            rounded-full
                                            bg-red-100
                                            px-2.5
                                            py-1
                                            text-[9px]
                                            font-black
                                            text-red-700
                                        "
                                    >
                                        Ditolak
                                    </span>

                                @else

                                    <span
                                        class="
                                            rounded-full
                                            bg-amber-100
                                            px-2.5
                                            py-1
                                            text-[9px]
                                            font-black
                                            text-amber-700
                                        "
                                    >
                                        Pending
                                    </span>

                                @endif

                            @endif

                        </div>



                        @if(
                            $document &&
                            $document->document_number
                        )

                            <p
                                class="
                                    mt-3
                                    text-[10px]
                                    font-semibold
                                    text-brand-dark/55
                                "
                            >
                                Nomor:
                                {{ $document->document_number }}
                            </p>

                        @endif



                        @if(
                            $document &&
                            $document->verification_note
                        )

                            <div
                                class="
                                    mt-3
                                    rounded-xl
                                    bg-red-50
                                    p-3
                                    text-[10px]
                                    leading-relaxed
                                    text-red-700
                                "
                            >
                                {{ $document->verification_note }}
                            </div>

                        @endif

                    </div>

                </div>



                @if($document)

                    <div
                        class="
                            grid
                            grid-cols-2
                            gap-2
                            border-t
                            border-brand-dark/10
                            p-3
                        "
                    >

                        <a
                            href="{{
                                route(
                                    'pendaki.profil.documents.view',
                                    $document
                                )
                            }}"
                            target="_blank"
                            class="
                                flex
                                h-10
                                items-center
                                justify-center
                                rounded-xl
                                border
                                border-brand-dark/10
                                text-[10px]
                                font-bold
                            "
                        >
                            Lihat Dokumen
                        </a>


                        <form
                            action="{{
                                route(
                                    'pendaki.profil.documents.destroy',
                                    $document
                                )
                            }}"
                            method="POST"
                        >

                            @csrf
                            @method('DELETE')


                            <button
                                type="submit"
                                data-online-only
                                onclick="return confirm('Hapus dokumen {{ $label }}?')"
                                class="
                                    flex
                                    h-10
                                    w-full
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-red-50
                                    text-[10px]
                                    font-bold
                                    text-red-600
                                    disabled:opacity-40
                                "
                            >
                                Hapus
                            </button>

                        </form>

                    </div>

                @endif

            </section>

        @endforeach



        {{-- ===================================================== --}}
        {{-- UPLOAD FORM --}}
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
                class="mb-5"
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
                    Upload
                </span>


                <h2
                    class="
                        mt-1
                        text-lg
                        font-black
                    "
                >
                    Upload / Ganti Dokumen
                </h2>


                <p
                    class="
                        mt-1
                        text-xs
                        text-brand-dark/50
                    "
                >
                    JPG, PNG atau PDF. Maksimal 5 MB.
                </p>

            </div>



            <form
                method="POST"
                action="{{ route('pendaki.profil.documents.store') }}"
                enctype="multipart/form-data"
                class="space-y-4"
            >

                @csrf



                {{-- TYPE --}}

                <div>

                    <label
                        class="
                            mb-1.5
                            block
                            text-xs
                            font-bold
                        "
                    >
                        Jenis Dokumen
                    </label>


                    <select
                        name="document_type"
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
                            focus:border-brand-orange
                            focus:ring-1
                            focus:ring-brand-orange
                        "
                    >

                        <option value="ktp">
                            KTP
                        </option>


                        <option value="sim">
                            SIM
                        </option>

                    </select>

                </div>



                {{-- NUMBER --}}

                <div>

                    <label
                        class="
                            mb-1.5
                            block
                            text-xs
                            font-bold
                        "
                    >
                        Nomor Identitas
                    </label>


                    <input
                        type="text"
                        name="document_number"
                        value="{{ old('document_number') }}"
                        placeholder="Masukkan nomor identitas"
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
                            placeholder:text-brand-dark/30
                            focus:border-brand-orange
                            focus:ring-1
                            focus:ring-brand-orange
                        "
                    >

                </div>



                {{-- FILE --}}

                <div>

                    <label
                        class="
                            mb-1.5
                            block
                            text-xs
                            font-bold
                        "
                    >
                        File Dokumen
                    </label>


                    <input
                        type="file"
                        name="document_file"
                        required
                        accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                        class="
                            block
                            w-full
                            rounded-xl
                            border
                            border-brand-dark/15
                            bg-brand-cream
                            p-3
                            text-xs

                            file:mr-3
                            file:rounded-lg
                            file:border-0
                            file:bg-brand-dark
                            file:px-3
                            file:py-2
                            file:text-[10px]
                            file:font-bold
                            file:text-white
                        "
                    >

                </div>



                <button
                    type="submit"
                    data-online-only
                    class="
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
                        disabled:opacity-40
                    "
                >
                    Simpan Dokumen
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
                        element
                    ) {

                        element.disabled =
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