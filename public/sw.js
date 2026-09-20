/*
|--------------------------------------------------------------------------
| BaliHiking PWA Service Worker
|--------------------------------------------------------------------------
*/

const VERSION =
    'balihiking-v10';


const STATIC_CACHE =
    `${VERSION}-static`;


const PAGE_CACHE =
    `${VERSION}-pages`;


const ASSET_CACHE =
    `${VERSION}-assets`;


const IMAGE_CACHE =
    `${VERSION}-images`;


const MAP_TILE_CACHE =
    `${VERSION}-map-tiles`;



/*
|--------------------------------------------------------------------------
| APP SHELL
|--------------------------------------------------------------------------
*/

const APP_SHELL = [

    '/',

    '/manifest.webmanifest',

    '/icons/icon-192.png',

    '/icons/icon-512.png',

    '/icons/icon-maskable-192.png',

    '/icons/icon-maskable-512.png',

    '/vendor/tailwindcss.js',

    '/vendor/leaflet/leaflet.css',

    '/vendor/leaflet/leaflet.js',

    '/vendor/sweetalert2/sweetalert2.min.css',

    '/vendor/sweetalert2/sweetalert2.all.min.js',

];



/*
|--------------------------------------------------------------------------
| NEVER CACHE
|--------------------------------------------------------------------------
*/

const NEVER_CACHE_PATHS = [

    '/logout',

    '/api',

    '/sanctum',

    '/livewire',

    '/admin',

    '/filament',

];



/*
|--------------------------------------------------------------------------
| INSTALL
|--------------------------------------------------------------------------
*/

self.addEventListener(
    'install',
    function (
        event
    ) {

        event.waitUntil(
            (async function () {

                const cache =
                    await caches.open(
                        STATIC_CACHE
                    );


                for (
                    const url
                    of APP_SHELL
                ) {

                    try {

                        const response =
                            await fetch(
                                url,
                                {
                                    cache:
                                        'reload'
                                }
                            );


                        if (
                            response
                            &&
                            (
                                response.ok
                                ||
                                response.type ===
                                    'opaque'
                            )
                        ) {

                            await cache.put(
                                url,
                                response.clone()
                            );

                        }


                    } catch (
                        error
                    ) {

                        console.warn(
                            '[BaliHiking SW] precache gagal:',
                            url
                        );

                    }

                }


                await self
                    .skipWaiting();

            })()
        );

    }
);



/*
|--------------------------------------------------------------------------
| ACTIVATE
|--------------------------------------------------------------------------
*/

self.addEventListener(
    'activate',
    function (
        event
    ) {

        event.waitUntil(
            (async function () {

                const validCaches = [

                    STATIC_CACHE,

                    PAGE_CACHE,

                    ASSET_CACHE,

                    IMAGE_CACHE,

                    MAP_TILE_CACHE

                ];


                const cacheNames =
                    await caches.keys();


                await Promise.all(

                    cacheNames.map(
                        function (
                            cacheName
                        ) {

                            if (
                                !validCaches
                                    .includes(
                                        cacheName
                                    )
                            ) {

                                return caches
                                    .delete(
                                        cacheName
                                    );

                            }

                        }
                    )

                );


                if (
                    'navigationPreload'
                    in self.registration
                ) {

                    try {

                        await self
                            .registration
                            .navigationPreload
                            .enable();

                    } catch (
                        error
                    ) {}

                }


                await self
                    .clients
                    .claim();

            })()
        );

    }
);



/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

function canCache(
    response
) {

    if (
        !response
    ) {

        return false;

    }


    return (
        response.ok
        ||
        response.type ===
            'opaque'
    );

}



function shouldNeverCache(
    url
) {

    return NEVER_CACHE_PATHS
        .some(
            function (
                path
            ) {

                return url.pathname
                    .startsWith(
                        path
                    );

            }
        );

}



function isMapTile(
    url
) {

    return (
        url.hostname ===
            'tile.openstreetmap.org'
        ||
        url.hostname.endsWith(
            '.tile.openstreetmap.org'
        )
    );

}



/*
|--------------------------------------------------------------------------
| NAVIGATION
|--------------------------------------------------------------------------
*/

async function networkFirstNavigation(
    request,
    preloadPromise
) {

    const cache =
        await caches.open(
            PAGE_CACHE
        );


    try {

        const preload =
            await preloadPromise;


        if (
            canCache(
                preload
            )
        ) {

            await cache.put(
                request,
                preload.clone()
            );


            return preload;

        }

    } catch (
        error
    ) {}


    try {

        const response =
            await fetch(
                request
            );


        if (
            canCache(
                response
            )
        ) {

            await cache.put(
                request,
                response.clone()
            );

        }


        return response;


    } catch (
        error
    ) {


        let cached =
            await cache.match(
                request
            );


        if (
            cached
        ) {

            return cached;

        }


        cached =
            await cache.match(
                request,
                {
                    ignoreSearch:
                        true
                }
            );


        if (
            cached
        ) {

            return cached;

        }


        cached =
            await caches.match(
                request,
                {
                    ignoreSearch:
                        true
                }
            );


        if (
            cached
        ) {

            return cached;

        }


        cached =
            await caches.match(
                '/'
            );


        if (
            cached
        ) {

            return cached;

        }


        return offlineFallback();

    }

}



/*
|--------------------------------------------------------------------------
| OFFLINE FALLBACK
|--------------------------------------------------------------------------
*/

async function offlineFallback() {

    const icon =
        '/icons/icon-192.png';


    return new Response(
        `
<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="theme-color"
    content="#1a382b"
>

<title>
    BaliHiking Offline
</title>

<style>

* {
    box-sizing:border-box;
}

body {

    margin:0;

    min-height:100vh;

    display:flex;

    align-items:center;

    justify-content:center;

    padding:24px;

    background:#fbfbfa;

    color:#1a382b;

    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Arial,
        sans-serif;

}

.card {

    width:100%;

    max-width:390px;

    padding:30px 24px;

    border-radius:20px;

    border:
        1px solid
        rgba(26,56,43,.10);

    background:white;

    text-align:center;

    box-shadow:
        0 16px 40px
        rgba(0,0,0,.08);

}

.icon {

    width:72px;

    height:72px;

    margin:
        0 auto
        18px;

    overflow:hidden;

    border-radius:18px;

    background:#1a382b;

}

.icon img {

    width:100%;

    height:100%;

    object-fit:cover;

}

h1 {

    margin:0;

    font-size:22px;

}

p {

    margin:
        10px 0 0;

    color:#66726c;

    font-size:13px;

    line-height:1.7;

}

button {

    width:100%;

    margin-top:20px;

    padding:13px;

    border:0;

    border-radius:11px;

    color:white;

    background:#f06535;

    font-weight:700;

}

</style>

</head>

<body>

<div class="card">

    <div class="icon">

        <img
            src="${icon}"
            alt="BaliHiking"
        >

    </div>

    <h1>
        BaliHiking
    </h1>

    <p>
        Internet tidak tersedia.
        Halaman yang sebelumnya telah dibuka
        masih dapat digunakan secara offline.
    </p>

    <button
        onclick="location.reload()"
    >
        Coba Lagi
    </button>

</div>

</body>

</html>
        `,
        {
            headers: {
                'Content-Type':
                    'text/html; charset=UTF-8'
            }
        }
    );

}



/*
|--------------------------------------------------------------------------
| CACHE FIRST LOCAL ASSET
|--------------------------------------------------------------------------
*/

async function localAssetCacheFirst(
    request
) {

    const cache =
        await caches.open(
            ASSET_CACHE
        );


    const cached =
        await cache.match(
            request
        );


    if (
        cached
    ) {

        fetch(
            request
        )
        .then(
            async function (
                response
            ) {

                if (
                    canCache(
                        response
                    )
                ) {

                    await cache.put(
                        request,
                        response.clone()
                    );

                }

            }
        )
        .catch(
            function () {}
        );


        return cached;

    }


    try {

        const response =
            await fetch(
                request
            );


        if (
            canCache(
                response
            )
        ) {

            await cache.put(
                request,
                response.clone()
            );

        }


        return response;


    } catch (
        error
    ) {

        return new Response(
            '',
            {
                status:
                    408
            }
        );

    }

}



/*
|--------------------------------------------------------------------------
| IMAGE CACHE
|--------------------------------------------------------------------------
*/

async function imageCacheFirst(
    request
) {

    const cache =
        await caches.open(
            IMAGE_CACHE
        );


    const cached =
        await cache.match(
            request
        );


    if (
        cached
    ) {

        return cached;

    }


    try {

        const response =
            await fetch(
                request
            );


        if (
            canCache(
                response
            )
        ) {

            await cache.put(
                request,
                response.clone()
            );

        }


        return response;


    } catch (
        error
    ) {

        const fallback =
            await caches.match(
                '/icons/icon-192.png'
            );


        if (
            fallback
        ) {

            return fallback;

        }


        return new Response(
            '',
            {
                status:
                    408
            }
        );

    }

}



/*
|--------------------------------------------------------------------------
| MAP TILE
|--------------------------------------------------------------------------
|
| Tile yang sudah pernah berhasil dilihat akan tersimpan.
|
*/

async function mapTileCacheFirst(
    request
) {

    const cache =
        await caches.open(
            MAP_TILE_CACHE
        );


    const cached =
        await cache.match(
            request
        );


    if (
        cached
    ) {

        return cached;

    }


    try {

        const response =
            await fetch(
                request
            );


        if (
            canCache(
                response
            )
        ) {

            await cache.put(
                request,
                response.clone()
            );

        }


        return response;


    } catch (
        error
    ) {

        return new Response(
            '',
            {
                status:
                    408
            }
        );

    }

}



/*
|--------------------------------------------------------------------------
| FETCH
|--------------------------------------------------------------------------
*/

self.addEventListener(
    'fetch',
    function (
        event
    ) {

        const request =
            event.request;


        if (
            request.method !==
                'GET'
        ) {

            return;

        }


        const url =
            new URL(
                request.url
            );



        /*
        |--------------------------------------------------------------------------
        | NAVIGATION
        |--------------------------------------------------------------------------
        */

        if (
            request.mode ===
                'navigate'
        ) {

            event.respondWith(
                networkFirstNavigation(
                    request,
                    event.preloadResponse
                )
            );


            return;

        }



        /*
        |--------------------------------------------------------------------------
        | NEVER CACHE SENSITIVE INTERNAL ENDPOINT
        |--------------------------------------------------------------------------
        */

        if (
            url.origin ===
                self.location.origin
            &&
            shouldNeverCache(
                url
            )
        ) {

            return;

        }



        /*
        |--------------------------------------------------------------------------
        | OPENSTREETMAP TILE
        |--------------------------------------------------------------------------
        */

        if (
            isMapTile(
                url
            )
        ) {

            event.respondWith(
                mapTileCacheFirst(
                    request
                )
            );


            return;

        }



        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        if (
            request.destination ===
                'image'
        ) {

            event.respondWith(
                imageCacheFirst(
                    request
                )
            );


            return;

        }



        /*
        |--------------------------------------------------------------------------
        | LOCAL STATIC
        |--------------------------------------------------------------------------
        */

        if (
            url.origin ===
                self.location.origin
            &&
            [
                'style',
                'script',
                'font',
                'worker'
            ].includes(
                request.destination
            )
        ) {

            event.respondWith(
                localAssetCacheFirst(
                    request
                )
            );


            return;

        }

    }
);



/*
|--------------------------------------------------------------------------
| SKIP WAITING
|--------------------------------------------------------------------------
*/

self.addEventListener(
    'message',
    function (
        event
    ) {

        if (
            event.data
            &&
            event.data.type ===
                'SKIP_WAITING'
        ) {

            self.skipWaiting();

        }

    }
);