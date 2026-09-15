<div x-data="{
    initMap() {
        const coords = @js($getRecord()?->coordinates ?? []);
        if (!coords.length) return;

        const map = L.map('filament-gpx-map');
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const latLngs = coords.map(c => [c.lat, c.lng]);
        const polyline = L.polyline(latLngs, { color: 'red', weight: 4 }).addTo(map);

        map.fitBounds(polyline.getBounds());
    }
}" x-init="initMap()" wire:ignore>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @if($getRecord()?->coordinates)
        <div id="filament-gpx-map" style="height: 400px; width: 100%; border-radius: 8px;" class="shadow"></div>
    @else
        <div class="p-4 text-center text-gray-500 bg-gray-50 dark:bg-gray-800 rounded-lg">
            Unggah file GPX dan simpan untuk melihat pratinjau peta jalur.
        </div>
    @endif
</div>