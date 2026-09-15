<div>
    <x-filament-panels::page>
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        <style>
            /* Animasi Denyut Beacon Merah untuk Emergency SOS */
            @keyframes pulse-sos {
                0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.8); }
                70% { transform: scale(1.15); box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
                100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
            }
            .sos-active-marker {
                border-radius: 50%;
                animation: pulse-sos 1.2s infinite;
            }

            /* Custom Styling Leaflet Popup */
            .leaflet-popup-content-wrapper {
                border-radius: 1rem !important;
                padding: 0 !important;
                overflow: hidden;
                box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2) !important;
            }
            .leaflet-popup-content {
                margin: 0 !important;
            }
        </style>

        <div class="space-y-4"
             x-data="{
                 map: null,
                 markers: {},
                 isLoading: false,
                 stats: { total: 0, sos: 0, normal: 0 },

                 async fetchAndRender() {
                     this.isLoading = true;
                     try {
                         const response = await fetch('/api/live-tracking-data');
                         if (response.ok) {
                             const hikers = await response.json();
                             this.updateStats(hikers);
                             this.updateMarkers(hikers);
                         }
                     } catch (error) {
                         console.error('Gagal mengambil data koordinat:', error);
                     } finally {
                         this.isLoading = false;
                     }
                 },

                 updateStats(hikers) {
                     if (!Array.isArray(hikers)) return;
                     this.stats.total = hikers.length;
                     this.stats.sos = hikers.filter(h => h.status === 'sos').length;
                     this.stats.normal = hikers.length - this.stats.sos;
                 },

                 async initMap() {
                     if (this.map) return;

                     if (!window.L) {
                         await new Promise((resolve) => {
                             const script = document.createElement('script');
                             script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                             script.onload = resolve;
                             document.head.appendChild(script);
                         });
                     }

                     // Inisialisasi Peta (Gunung Agung / Bali)
                     this.map = L.map(this.$refs.mapContainer, {
                         zoomControl: false
                     }).setView([-8.3405, 115.5082], 12);

                     L.control.zoom({ position: 'bottomright' }).addTo(this.map);

                     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                         maxZoom: 19,
                         attribution: '© OpenStreetMap'
                     }).addTo(this.map);

                     setTimeout(() => { this.map.invalidateSize(); }, 300);

                     await this.fetchAndRender();

                     // Realtime Polling otomatis setiap 5 detik
                     setInterval(() => { this.fetchAndRender(); }, 5000);
                 },

                 createMarkerIcon(hiker) {
                     const isSos = hiker.status === 'sos';
                     const bgColor = isSos ? 'bg-red-600' : 'bg-emerald-600';
                     const ringStyle = isSos ? 'sos-active-marker ring-4 ring-red-400' : 'ring-2 ring-white';
                     const iconSymbol = isSos ? '🚨' : '🥾';

                     const html = `
                         <div class='relative flex items-center justify-center w-10 h-10 ${bgColor} ${ringStyle} text-white rounded-full shadow-xl transition-all'>
                             <span class='text-sm'>${iconSymbol}</span>
                             <div class='absolute -bottom-1 w-2.5 h-2.5 ${bgColor} rotate-45'></div>
                         </div>
                     `;

                     return L.divIcon({
                         html: html,
                         className: 'custom-hiker-pin',
                         iconSize: [40, 40],
                         iconAnchor: [20, 40],
                         popupAnchor: [0, -38]
                     });
                 },

                 createPopupContent(hiker) {
                     const isSos = hiker.status === 'sos';
                     
                     return `
                         <div class='w-68 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans'>
                             <!-- Banner Status -->
                             <div class='p-3 ${isSos ? 'bg-red-600 text-white' : 'bg-emerald-600 text-white'} flex items-center justify-between'>
                                 <div class='flex items-center gap-2'>
                                     <span class='text-base'>${isSos ? '⚠️' : '👤'}</span>
                                     <span class='font-bold text-sm truncate'>${hiker.user_name}</span>
                                 </div>
                                 <span class='text-[10px] uppercase tracking-wider font-extrabold px-2 py-0.5 rounded ${isSos ? 'bg-black/30 animate-pulse' : 'bg-white/20'}'>
                                     ${isSos ? 'DARURAT SOS' : 'MONITORING'}
                                 </span>
                             </div>

                             <!-- Detail Informasi -->
                             <div class='p-3.5 space-y-2 text-xs'>
                                 <div class='flex justify-between items-center pb-1 border-b border-gray-100 dark:border-gray-800'>
                                     <span class='text-gray-500 dark:text-gray-400'>📍 Jalur Pendakian</span>
                                     <span class='font-semibold text-gray-900 dark:text-white'>${hiker.trail_name}</span>
                                 </div>
                                 <div class='flex justify-between items-center pb-1 border-b border-gray-100 dark:border-gray-800'>
                                     <span class='text-gray-500 dark:text-gray-400'>🏔️ Ketinggian</span>
                                     <span class='font-bold text-emerald-600 dark:text-emerald-400'>${hiker.altitude} mdpl</span>
                                 </div>
                                 <div class='flex justify-between items-center pb-1 border-b border-gray-100 dark:border-gray-800'>
                                     <span class='text-gray-500 dark:text-gray-400'>🔋 Sisa Baterai</span>
                                     <span class='font-semibold ${parseInt(hiker.battery) < 20 ? 'text-red-500' : 'text-gray-900 dark:text-white'}'>${hiker.battery}%</span>
                                 </div>
                                 <div class='flex justify-between items-center pt-0.5 text-[10px] text-gray-400'>
                                     <span>Update Terakhir:</span>
                                     <span>${hiker.updated_ago}</span>
                                 </div>
                             </div>
                         </div>
                     `;
                 },

                 updateMarkers(hikers) {
                     if (!Array.isArray(hikers)) return;

                     hikers.forEach(hiker => {
                         const markerKey = `user_${hiker.id}`;
                         const icon = this.createMarkerIcon(hiker);
                         const popupContent = this.createPopupContent(hiker);

                         if (this.markers[markerKey]) {
                             this.markers[markerKey].setLatLng([hiker.lat, hiker.lng]);
                             this.markers[markerKey].setIcon(icon);
                             this.markers[markerKey].getPopup().setContent(popupContent);
                         } else {
                             const newMarker = L.marker([hiker.lat, hiker.lng], { icon: icon }).addTo(this.map);
                             newMarker.bindPopup(popupContent);
                             this.markers[markerKey] = newMarker;
                         }
                     });
                 }
             }"
             x-init="setTimeout(() => initMap(), 100)">

            <!-- Status Ringkasan & Control Bar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-800 gap-4">
                
                <!-- Info Header -->
                <div class="flex items-center gap-3">
                    <div class="relative p-2.5 bg-emerald-100 rounded-xl dark:bg-emerald-950">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.652a3.75 3.75 0 0 1 0-5.304m5.304 0a3.75 3.75 0 0 1 0 5.304m-7.425 2.122a6.75 6.75 0 0 1 0-9.546m9.546 0a6.75 6.75 0 0 1 0 9.546M5.106 18.894c-3.808-3.807-3.808-9.98 0-13.788m13.788 0c3.808 3.807 3.808 9.98 0 13.788M12 12h.008v.008H12V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base leading-snug">Radar Live Tracking Pendaki</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Monitoring real-time pergerakan & darurat pendaki di jalur gunung.</p>
                    </div>
                </div>

                <!-- Stats Ringkas & Tombol Action -->
                <div class="flex items-center gap-3">
                    <!-- Badge Pendaki Aktif -->
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 rounded-xl border border-emerald-200 dark:border-emerald-800 text-xs font-semibold">
                        <span>🥾 Active:</span>
                        <span x-text="stats.normal" class="font-bold">0</span>
                    </div>

                    <!-- Badge SOS Emergency -->
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-700 dark:bg-red-950/50 dark:text-red-300 rounded-xl border border-red-200 dark:border-red-800 text-xs font-semibold"
                         :class="{ 'animate-bounce': stats.sos > 0 }">
                        <span>🚨 SOS:</span>
                        <span x-text="stats.sos" class="font-bold text-red-600 dark:text-red-400">0</span>
                    </div>

                    <!-- Tombol Refresh Kontras Tinggi -->
                    <button type="button" 
                            @click="fetchAndRender()"
                            :disabled="isLoading"
                            style="background-color: #059669 !important; color: #ffffff !important; display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 12px; font-weight: 600; font-size: 13px; border: none; cursor: pointer;"
                            class="hover:opacity-90 active:scale-95 transition-all disabled:opacity-50">
                        <svg style="width: 16px; height: 16px; stroke: #ffffff; fill: none;" :class="{ 'animate-spin': isLoading }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span x-show="!isLoading" style="color: #ffffff !important;">Refresh</span>
                        <span x-show="isLoading" x-cloak style="color: #ffffff !important;">Memuat...</span>
                    </button>
                </div>
            </div>

            <!-- Peta Leaflet Container -->
            <div x-ref="mapContainer" 
                 style="height: 620px; width: 100%; min-height: 620px;" 
                 class="rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-md bg-gray-100 dark:bg-gray-900 z-0">
            </div>
        </div>
    </x-filament-panels::page>
</div>