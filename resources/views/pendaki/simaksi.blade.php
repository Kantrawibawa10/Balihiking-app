<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pendaftaran SIMAKSI - Jalur Bali</title>
    
    <!-- Tailwind CSS CDN -->
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

    <!-- Google Fonts -->
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

    <!-- Header -->
    <header class="bg-brand-cream/90 backdrop-blur-md border-b border-brand-dark/10 sticky top-0 z-40">
        <div class="max-w-md mx-auto px-4 h-16 flex items-center justify-between sm:max-w-xl">
            <a href="{{ route('pendaki.dashboard') }}" class="p-2 -ml-2 text-brand-dark hover:text-brand-orange transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="font-black text-brand-dark text-base tracking-tight">Registrasi SIMAKSI</h1>
            <div class="w-6"></div>
        </div>
    </header>

    <main class="max-w-md mx-auto px-4 pt-6 space-y-5 sm:max-w-xl">
        
        <!-- Session Alert Success -->
        @if(session('success'))
            <div class="p-4 bg-brand-dark text-white rounded-2xl text-xs font-semibold flex items-center gap-2.5 shadow-md">
                <span class="w-2 h-2 rounded-full bg-brand-orange"></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Form SIMAKSI -->
        <form action="{{ route('pendaki.simaksi.store') }}" method="POST" class="bg-white p-6 rounded-3xl border border-brand-dark/10 shadow-sm space-y-4">
            @csrf

            <!-- Subtitle Badge & Title -->
            <div class="space-y-1 mb-2">
                <div class="inline-block border-b-2 border-brand-orange pb-0.5">
                    <span class="text-[10px] font-black tracking-widest text-brand-orange uppercase">IZIN PENDAKIAN</span>
                </div>
                <h2 class="text-xl font-black text-brand-dark tracking-tight">
                    Form Permohonan
                </h2>
                <p class="text-xs text-brand-dark/60 font-medium">
                    Isi data perjalanan Anda secara akurat untuk keselamatan selama mendaki.
                </p>
            </div>

            <!-- Select Gunung -->
            <div>
                <label class="block text-xs font-bold text-brand-dark mb-1.5">Pilih Gunung</label>
                <select name="gunung" class="w-full bg-brand-cream border border-brand-dark/20 text-brand-dark rounded-xl px-3.5 py-3 text-xs font-bold focus:ring-1 focus:ring-brand-orange focus:border-brand-orange focus:outline-none transition">
                    <option value="Gunung Agung">Gunung Agung (3.031 mdpl)</option>
                    <option value="Gunung Batur">Gunung Batur (1.717 mdpl)</option>
                    <option value="Gunung Abang">Gunung Abang (2.152 mdpl)</option>
                </select>
            </div>

            <!-- Dates Grid -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-brand-dark mb-1.5">Tanggal Naik</label>
                    <input type="date" name="tanggal_naik" required class="w-full bg-brand-cream border border-brand-dark/20 text-brand-dark rounded-xl px-3 py-2.5 text-xs font-semibold focus:ring-1 focus:ring-brand-orange focus:border-brand-orange focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-brand-dark mb-1.5">Tanggal Turun</label>
                    <input type="date" name="tanggal_turun" required class="w-full bg-brand-cream border border-brand-dark/20 text-brand-dark rounded-xl px-3 py-2.5 text-xs font-semibold focus:ring-1 focus:ring-brand-orange focus:border-brand-orange focus:outline-none transition">
                </div>
            </div>

            <!-- Jumlah Anggota -->
            <div>
                <label class="block text-xs font-bold text-brand-dark mb-1.5">Jumlah Anggota Rombongan</label>
                <input type="number" name="jumlah_anggota" min="1" value="1" required class="w-full bg-brand-cream border border-brand-dark/20 text-brand-dark rounded-xl px-3.5 py-2.5 text-xs font-bold focus:ring-1 focus:ring-brand-orange focus:border-brand-orange focus:outline-none transition">
            </div>

            <!-- Kontak Darurat -->
            <div>
                <label class="block text-xs font-bold text-brand-dark mb-1.5">Nomor Kontak Darurat</label>
                <input type="text" name="nomor_darurat" placeholder="08xxxxxxxxxx" required class="w-full bg-brand-cream border border-brand-dark/20 text-brand-dark placeholder-brand-dark/30 rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-1 focus:ring-brand-orange focus:border-brand-orange focus:outline-none transition">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-brand-orange hover:bg-brand-orange/90 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-brand-orange/20 text-xs sm:text-sm transition active:scale-95 mt-2">
                Kirim Permohonan SIMAKSI &rarr;
            </button>
        </form>
    </main>

    <!-- Bottom Nav -->
    @include('pendaki.components.bottom-nav', ['active' => 'simaksi'])

</body>
</html>