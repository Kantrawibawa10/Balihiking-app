<!-- Load CSS Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="space-y-6">
    <!-- Header Card Utama dengan Inline Style (Aman dari CSS Filament) -->
    <div style="background-color: #0f172a !important; color: #ffffff !important; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3);">
        
        <!-- Baris Atas: Info Gunung & Nama Jalur -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
            <div>
                <span style="color: #34d399 !important; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                    ⛰️ {{ $record->mountain?->name ?? 'Tanpa Gunung' }} ({{ $record->mountain?->location ?? '-' }})
                </span>
                <h2 style="color: #ffffff !important; font-size: 1.5rem; font-weight: 800; margin-top: 0.25rem;">
                    {{ $record->name ?? 'Nama Jalur Tidak Ditemukan' }}
                </h2>
            </div>
            
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                @if($record->status)
                    <span style="background-color: rgba(16, 185, 129, 0.2); color: #6ee7b7 !important; border: 1px solid rgba(16, 185, 129, 0.4); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: capitalize;">
                        Status: {{ $record->status }}
                    </span>
                @endif

                @if($record->difficulty)
                    <span style="background-color: rgba(245, 158, 11, 0.2); color: #fcd34d !important; border: 1px solid rgba(245, 158, 11, 0.4); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: capitalize;">
                        Kesulitan: {{ $record->difficulty }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Metric Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid #334155; text-align: center;">
            <div>
                <p style="color: #94a3b8 !important; font-size: 0.75rem; margin-bottom: 0.25rem;">Jarak Total</p>
                <p style="color: #34d399 !important; font-size: 1.25rem; font-weight: 800; margin: 0;">{{ $record->distance_km ?? 0 }} km</p>
            </div>
            <div>
                <p style="color: #94a3b8 !important; font-size: 0.75rem; margin-bottom: 0.25rem;">Est. Waktu</p>
                <p style="color: #fbbf24 !important; font-size: 1.25rem; font-weight: 800; margin: 0;">{{ $record->estimated_time_hours ?? 0 }} Jam</p>
            </div>
            <div>
                <p style="color: #94a3b8 !important; font-size: 0.75rem; margin-bottom: 0.25rem;">Elevasi Maks</p>
                <p style="color: #22d3ee !important; font-size: 1.25rem; font-weight: 800; margin: 0;">{{ $record->max_elevation ?? 0 }} mdpl</p>
            </div>
            <div>
                <p style="color: #94a3b8 !important; font-size: 0.75rem; margin-bottom: 0.25rem;">Total Checkpoint</p>
                <p style="color: #c084fc !important; font-size: 1.25rem; font-weight: 800; margin: 0;">{{ $record->checkpoints?->count() ?? 0 }} Pos</p>
            </div>
        </div>
    </div>

    <!-- Peta Navigasi Interaktif -->
    <div class="space-y-2"
         x-data="{
             async loadLeaflet() {
                 if (window.L) return window.L;
                 return new Promise((resolve, reject) => {
                     const script = document.createElement('script');
                     script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                     script.onload = () => resolve(window.L);
                     script.onerror = reject;
                     document.head.appendChild(script);
                 });
             },
             async initMap() {
                 try {
                     const L = await this.loadLeaflet();
                     await new Promise(r => setTimeout(r, 400));

                     const coords = @js($record->coordinates ?? []);
                     const checkpoints = @js($record->checkpoints ?? []);

                     if (!this.$refs.mapContainer) return;

                     const map = L.map(this.$refs.mapContainer).setView([-8.34, 115.48], 12);

                     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                         attribution: '© OpenStreetMap'
                     }).addTo(map);

                     // Render Jalur GPX
                     if (coords && coords.length > 0) {
                         const latLngs = coords.map(c => [c.lat, c.lng]);
                         const polyline = L.polyline(latLngs, { color: '#10b981', weight: 5 }).addTo(map);
                         map.fitBounds(polyline.getBounds(), { padding: [30, 30] });
                     }

                     // Render Checkpoints
                     checkpoints.forEach(cp => {
                         if (cp.latitude && cp.longitude) {
                             L.marker([cp.latitude, cp.longitude])
                                 .addTo(map)
                                 .bindPopup(`<b>${cp.name}</b><br>Elevasi: ${cp.elevation_m || '-'} mdpl`);
                         }
                     });

                     map.invalidateSize();
                 } catch (err) {
                     console.error('Gagal memuat Leaflet map:', err);
                 }
             }
         }"
         x-init="initMap()">

        <h3 style="color: #1e293b !important; font-weight: 700; font-size: 1rem; margin-bottom: 0.5rem;" class="dark:!text-white">
            📍 Pratinjau Peta Navigasi & Pos Pendakian
        </h3>

        <!-- Map Container -->
        <div x-ref="mapContainer" 
             style="height: 380px; width: 100%; border-radius: 0.75rem; border: 1px solid #cbd5e1; background-color: #f1f5f9;">
        </div>
    </div>

    <!-- Daftar Checkpoints -->
    <div>
        <h3 style="color: #1e293b !important; font-weight: 700; font-size: 1rem; margin-bottom: 0.5rem;" class="dark:!text-white">
            🚩 Daftar Checkpoint / Pos Terdaftar
        </h3>
        
        <div style="border: 1px solid #e2e8f0; border-radius: 0.75rem; overflow: hidden; background-color: #ffffff;">
            @forelse($record->checkpoints ?? [] as $index => $cp)
                <div style="padding: 0.875rem 1rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span style="width: 1.75rem; height: 1.75rem; border-radius: 0.5rem; background-color: #d1fae5; color: #059669; font-weight: 700; display: flex; align-items: justify-center; align-items: center; justify-content: center; font-size: 0.75rem;">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p style="font-weight: 700; font-size: 0.875rem; color: #0f172a; margin: 0;">{{ $cp->name }}</p>
                            <p style="font-size: 0.75rem; color: #64748b; margin: 0; text-transform: capitalize;">Tipe: {{ str_replace('_', ' ', $cp->type) }}</p>
                        </div>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.5rem; border-radius: 0.25rem; background-color: #fef3c7; color: #b45309;">
                        {{ $cp->elevation_m ?? '-' }} mdpl
                    </span>
                </div>
            @empty
                <div style="padding: 1rem; text-align: center; font-size: 0.875rem; color: #94a3b8;">
                    Belum ada checkpoint yang terhubung ke jalur ini.
                </div>
            @endforelse
        </div>
    </div>
</div>