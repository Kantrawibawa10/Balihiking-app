<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Profil Pendaki - Jalur Bali</title>

    <!-- Tailwind CSS CDN dengan Konfigurasi Tema Jalur Bali -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#1a382b',   /* Hijau Gelap khas Jalur Bali */
                            orange: '#f06535', /* Oranye Terracotta */
                            cream: '#fbfbfa',  /* Latar Krem Soft */
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Fonts Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fbfbfa;
        }
    </style>
</head>

<body class="bg-brand-cream text-brand-dark antialiased pb-28">

    <!-- TOP HEADER -->
    <header class="bg-brand-cream/90 backdrop-blur-md border-b border-brand-dark/10 sticky top-0 z-40">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between sm:max-w-xl">
            <a href="{{ route('pendaki.dashboard') }}"
                class="p-2 -ml-2 rounded-xl text-brand-dark hover:text-brand-orange transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="font-black text-brand-dark text-base tracking-tight">Profil Akun</h1>
            <div class="w-6"></div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-md mx-auto px-4 pt-6 space-y-5 sm:max-w-xl">
        
        <!-- User Info Card -->
        <div class="bg-white p-5 rounded-3xl border border-brand-dark/10 shadow-sm flex items-center gap-4">
            <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f06535&color=fff' }}"
                class="w-16 h-16 rounded-2xl object-cover border-2 border-brand-orange/30 shadow-sm">
            <div class="flex-1 min-w-0">
                <h2 class="text-base font-black text-brand-dark truncate leading-snug">{{ $user->name }}</h2>
                <p class="text-xs font-semibold text-brand-dark/50 truncate mt-0.5">{{ $user->email }}</p>
                <span class="inline-flex items-center gap-1.5 mt-2 text-[10px] font-extrabold px-3 py-1 bg-brand-orange/10 text-brand-orange rounded-full border border-brand-orange/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-orange"></span>
                    Pendaki Aktif
                </span>
            </div>
        </div>

        <!-- Menu / Settings Options -->
        <div class="bg-white rounded-3xl border border-brand-dark/10 shadow-sm overflow-hidden divide-y divide-brand-dark/5 text-xs font-bold text-brand-dark">
            
            <a href="#" class="flex items-center justify-between p-4 hover:bg-brand-cream/50 transition">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-brand-dark/5 text-brand-dark">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span>Pengaturan Akun</span>
                </div>
                <svg class="w-4 h-4 text-brand-dark/30" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <a href="#" class="flex items-center justify-between p-4 hover:bg-brand-cream/50 transition">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-brand-dark/5 text-brand-dark">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4" />
                        </svg>
                    </div>
                    <span>Dokumen Identitas (KTP/SIM)</span>
                </div>
                <svg class="w-4 h-4 text-brand-dark/30" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <!-- Tombol Keluar / Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-between p-4 text-rose-600 hover:bg-rose-50 transition text-left font-black">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-rose-100/60 text-rose-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span>Keluar dari Akun</span>
                    </div>
                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </form>

        </div>
    </main>

    <!-- BOTTOM NAVIGATION -->
    @include('pendaki.components.bottom-nav', ['active' => 'profil'])

</body>
</html>