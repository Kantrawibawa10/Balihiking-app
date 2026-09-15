<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Jalur - {{ $trail->name }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        #map {
            height: 500px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="fw-bold">{{ $trail->name }}</h2>
                <p class="text-muted">Gunung: {{ $trail->mountain->name ?? 'Bali' }} | Jarak: {{ $trail->distance_km }}
                    KM | Estimasi: {{ $trail->estimated_time_hours }} Jam</p>
                <span class="badge bg-{{ $trail->status === 'open' ? 'success' : 'danger' }}">
                    Status: {{ strtoupper($trail->status) }}
                </span>
            </div>
        </div>

        <!-- Kontainer Peta -->
        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // 1. Inisialisasi Peta (Default koordinat ke Bali)
        const map = L.map('map').setView([-8.3405, 115.5082], 12);

        // 2. Tambahkan Tile Layer (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // 3. Ambil data GeoJSON Jalur dari Laravel PHP
        const geojsonData = {!! json_encode($trail->map_geojson) !!};

        if (geojsonData) {
            // Render Garis Jalur (Rute GeoJSON)
            const trailLayer = L.geoJSON(geojsonData, {
                style: {
                    color: '#e63946', // Warna garis rute (merah)
                    weight: 5,
                    opacity: 0.8
                }
            }).addTo(map);

            // Auto Zoom & Fit bounds ke seluruh panjang jalur
            map.fitBounds(trailLayer.getBounds());
        }

        // 4. Ambil data Checkpoints dari Laravel PHP
        const checkpoints = {!! json_encode($trail->checkpoints) !!};

        // Mapping warna/icon berdasarkan tipe checkpoint
        checkpoints.forEach(cp => {
            if (cp.latitude && cp.longitude) {
                // Buat Marker Popup
                const popupContent = `
                <div style="font-family: sans-serif;">
                    <strong style="font-size: 14px;">${cp.name}</strong><br>
                    <span style="font-size: 12px; color: #666;">Tipe: <b>${cp.type.toUpperCase()}</b></span><br>
                    <span style="font-size: 12px; color: #666;">Elevasi: ${cp.elevation_m ? cp.elevation_m + ' mdpl' : '-'}</span>
                </div>
            `;

                // Tambahkan Marker ke Peta
                L.marker([cp.latitude, cp.longitude])
                    .addTo(map)
                    .bindPopup(popupContent);
            }
        });
    </script>

</body>

</html>
