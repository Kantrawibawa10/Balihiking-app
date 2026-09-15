<div x-data="{
    state: $wire.entangle('{{ $getStatePath() }}'),
    map: null,
    geojsonLayer: null,
    init() {
        // 1. Inisialisasi Peta Leaflet
        this.map = L.map($refs.mapContainer).setView([-8.3405, 115.5082], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(this.map);

        // 2. Jika sudah ada data GeoJSON di database, tampilkan di peta
        this.renderGeoJSON();

        // 3. Pantau perubahan input teks secara realtime
        $watch('state', () => this.renderGeoJSON());
    },
    renderGeoJSON() {
        if (!this.state) return;

        try {
            const geojsonData = typeof this.state === 'string' ? JSON.parse(this.state) : this.state;

            if (this.geojsonLayer) {
                this.map.removeLayer(this.geojsonLayer);
            }

            this.geojsonLayer = L.geoJSON(geojsonData, {
                style: { color: '#e63946', weight: 4 }
            }).addTo(this.map);

            this.map.fitBounds(this.geojsonLayer.getBounds());
        } catch (e) {
            // Abaikan error parsing saat user masih mengetik JSON
        }
    }
}">
    <!-- CSS Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- JS Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Kontainer Peta -->
    <div x-ref="mapContainer" style="height: 350px; width: 100%; border-radius: 8px; z-index: 1;" class="border"></div>
</div>
