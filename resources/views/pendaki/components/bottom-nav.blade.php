<!-- BOTTOM NAVIGATION -->
<nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200/80 px-6 py-2.5 z-40">
    <div class="max-w-md mx-auto flex items-center justify-around">

        <!-- Beranda -->
        @php
            $isDashboard = ($active ?? '') === 'dashboard' || request()->routeIs('pendaki.dashboard*');
        @endphp
        <a href="{{ route('pendaki.dashboard') }}"
            class="flex flex-col items-center gap-1 {{ $isDashboard ? 'text-[#f06535] font-black' : 'text-slate-400 hover:text-[#f06535] font-semibold' }} transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="{{ $isDashboard ? '2.5' : '2' }}" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] tracking-tight">Beranda</span>
        </a>

        <!-- Simaksi -->
        @php
            $isSimaksi = ($active ?? '') === 'simaksi' || request()->routeIs('pendaki.simaksi*');
        @endphp
        <a href="{{ Route::has('pendaki.simaksi') ? route('pendaki.simaksi') : url('/pendaki/simaksi') }}"
            class="flex flex-col items-center gap-1 {{ $isSimaksi ? 'text-[#f06535] font-black' : 'text-slate-400 hover:text-[#f06535] font-semibold' }} transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="{{ $isSimaksi ? '2.5' : '2' }}" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 022 2h2a2 2 0 022-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span class="text-[10px] tracking-tight">Simaksi</span>
        </a>

        <!-- Active Live Map -->
        @php
            $isLiveTrack = ($active ?? '') === 'live-track' || request()->routeIs('pendaki.live-track*');
        @endphp
        <a href="{{ route('pendaki.live-track') }}"
            class="flex flex-col items-center gap-1 {{ $isLiveTrack ? 'text-[#f06535] font-black' : 'text-slate-400 hover:text-[#f06535] font-semibold' }} transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="{{ $isLiveTrack ? '2.5' : '2' }}" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
            </svg>
            <span class="text-[10px] tracking-tight">Peta Live</span>
        </a>

        <!-- Profil -->
        @php
            $isProfil = in_array(($active ?? ''), ['profil', 'profile']) || request()->routeIs('pendaki.profil*', 'pendaki.profile*');
        @endphp
        <a href="{{ Route::has('pendaki.profil') ? route('pendaki.profil') : (Route::has('pendaki.profile') ? route('pendaki.profile') : url('/pendaki/profil')) }}"
            class="flex flex-col items-center gap-1 {{ $isProfil ? 'text-[#f06535] font-black' : 'text-slate-400 hover:text-[#f06535] font-semibold' }} transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="{{ $isProfil ? '2.5' : '2' }}" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-[10px] tracking-tight">Profil</span>
        </a>

    </div>
</nav>