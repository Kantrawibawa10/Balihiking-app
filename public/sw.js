const CACHE_NAME = 'bali-hiking-v1';

// Daftar aset statis utama aplikasi yang selalu di-cache
const STATIC_ASSETS = [
    '/',
    '/manifest.json',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
];

// 1. Install Event: Cache aset statis aplikasi
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[Service Worker] Caching static assets');
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

// 2. Activate Event: Hapus cache lama jika ada update versi
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        console.log('[Service Worker] Deleting old cache:', cache);
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// 3. Fetch Event: Strategi Caching untuk Peta Leaflet & Halaman Offline
self.addEventListener('fetch', (event) => {
    const requestUrl = new URL(event.request.url);

    // Strategi khusus Tile Peta Leaflet (OpenStreetMap): Cache First, then Network
    if (requestUrl.hostname.includes('tile.openstreetmap.org')) {
        event.respondWith(
            caches.open('leaflet-tiles-cache').then((tileCache) => {
                return tileCache.match(event.request).then((cachedResponse) => {
                    // Jika tile gambar peta sudah ada di cache, pakai cache (offline ready)
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Jika belum ada, download dari internet lalu simpan ke cache
                    return fetch(event.request).then((networkResponse) => {
                        tileCache.put(event.request, networkResponse.clone());
                        return networkResponse;
                    }).catch(() => {
                        // Jika offline dan tile belum pernah dibuka, kembalikan response kosong/fallback
                        return new Response('', { status: 404, statusText: 'Offline Tile Not Found' });
                    });
                });
            })
        );
        return;
    }

    // Strategi Umum untuk aset/halaman aplikasi: Network First, Fallback to Cache
    event.respondWith(
        fetch(event.request)
            .then((networkResponse) => {
                // Simpan copy halaman/aset terbaru ke cache
                if (event.request.method === 'GET') {
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, networkResponse.clone());
                    });
                }
                return networkResponse;
            })
            .catch(() => {
                // Saat tidak ada sinyal / blank spot, ambil dari cache
                return caches.match(event.request);
            })
    );
});