/*
|--------------------------------------------------------------------------
| BaliHiking Service Worker
|--------------------------------------------------------------------------
|
| PWA hanya menangani halaman frontend BaliHiking.
|
| Route berikut TIDAK PERNAH dicache:
|
| /admin
| /livewire
| /filament
| /api
| /login
| /logout
| /register
| /sanctum
|
*/

const CACHE_VERSION =
    'balihiking-v12';


const APP_CACHE =
    `${CACHE_VERSION}-app`;


const PAGE_CACHE =
    `${CACHE_VERSION}-pages`;


const ASSET_CACHE =
    `${CACHE_VERSION}-assets`;


const MAP_CACHE =
    `${CACHE_VERSION}-maps`;



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

];



/*
|--------------------------------------------------------------------------
| NETWORK ONLY PATHS
|--------------------------------------------------------------------------
|
| Sangat penting:
| seluruh Filament/Admin tidak boleh masuk cache PWA.
|--------------------------------------------------------------------------
*/

const NETWORK_ONLY_PREFIXES = [

    '/admin',

    '/livewire',

    '/filament',

    '/api',

    '/sanctum',

    '/login',

    '/logout',

    '/register',

];



/*
|--------------------------------------------------------------------------
| INSTALL
|--------------------------------------------------------------------------
*/

self.addEventListener(
    'install',
    event => {

        event.waitUntil(
            (async () => {

                const cache =
                    await caches.open(
                        APP_CACHE
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
                                        'reload',

                                    credentials:
                                        'same-origin',
                                }
                            );


                        if (
                            response
                            &&
                            response.ok
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
                            '[BaliHiking SW] preload gagal:',
                            url,
                            error
                        );

                    }

                }


                await self.skipWaiting();

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
    event => {

        event.waitUntil(
            (async () => {

                const cacheNames =
                    await caches.keys();


                await Promise.all(
                    cacheNames.map(
                        cacheName => {

                            if (
                                !cacheName.startsWith(
                                    CACHE_VERSION
                                )
                            ) {

                                return caches.delete(
                                    cacheName
                                );

                            }


                            return Promise.resolve();

                        }
                    )
                );


                if (
                    self.registration
                        .navigationPreload
                ) {

                    try {

                        await self.registration
                            .navigationPreload
                            .enable();

                    } catch (
                        error
                    ) {}

                }


                await self.clients.claim();

            })()
        );

    }
);



/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

function isNetworkOnlyPath(
    pathname
) {

    return NETWORK_ONLY_PREFIXES
        .some(
            prefix => {

                return (
                    pathname
                    ===
                    prefix
                )
                ||
                pathname.startsWith(
                    `${prefix}/`
                );

            }
        );

}



function isStaticAsset(
    pathname
) {

    return /\.(?:css|js|png|jpg|jpeg|webp|svg|gif|ico|woff2?|ttf|eot)$/i
        .test(
            pathname
        );

}



function isMapTile(
    url
) {

    return (
        url.hostname.includes(
            'tile.openstreetmap.org'
        )
        ||
        url.hostname.includes(
            'openstreetmap.org'
        )
    );

}



function canCacheResponse(
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
        response.type
        ===
        'opaque'
    );

}



/*
|--------------------------------------------------------------------------
| FETCH
|--------------------------------------------------------------------------
*/

self.addEventListener(
    'fetch',
    event => {

        const request =
            event.request;


        /*
        |--------------------------------------------------------------------------
        | GET ONLY
        |--------------------------------------------------------------------------
        */

        if (
            request.method
            !==
            'GET'
        ) {

            return;

        }


        const url =
            new URL(
                request.url
            );


        const sameOrigin =
            url.origin
            ===
            self.location.origin;



        /*
        |--------------------------------------------------------------------------
        | ADMIN / FILAMENT / API = NETWORK ONLY
        |--------------------------------------------------------------------------
        |
        | Jangan pernah fallback ke landing page.
        |--------------------------------------------------------------------------
        */

        if (
            sameOrigin
            &&
            isNetworkOnlyPath(
                url.pathname
            )
        ) {

            event.respondWith(
                fetch(
                    request
                )
            );


            return;

        }



        /*
        |--------------------------------------------------------------------------
        | DOCUMENT NAVIGATION
        |--------------------------------------------------------------------------
        */

        if (
            request.mode
            ===
            'navigate'
        ) {

            event.respondWith(
                handleNavigation(
                    event
                )
            );


            return;

        }



        /*
        |--------------------------------------------------------------------------
        | MAP TILES
        |--------------------------------------------------------------------------
        */

        if (
            isMapTile(
                url
            )
        ) {

            event.respondWith(
                cacheFirst(
                    request,
                    MAP_CACHE
                )
            );


            return;

        }



        /*
        |--------------------------------------------------------------------------
        | SAME ORIGIN STATIC ASSETS
        |--------------------------------------------------------------------------
        */

        if (
            sameOrigin
            &&
            isStaticAsset(
                url.pathname
            )
        ) {

            event.respondWith(
                cacheFirst(
                    request,
                    ASSET_CACHE
                )
            );


            return;

        }



        /*
        |--------------------------------------------------------------------------
        | EXTERNAL ASSETS
        |--------------------------------------------------------------------------
        */

        if (
            !sameOrigin
        ) {

            event.respondWith(
                staleWhileRevalidate(
                    request,
                    ASSET_CACHE
                )
            );

        }

    }
);



/*
|--------------------------------------------------------------------------
| NAVIGATION
|--------------------------------------------------------------------------
|
| NETWORK FIRST.
|
| Jika frontend tidak memiliki internet:
| - gunakan exact cached page
| - kemudian cached /
|
| Tetapi function ini tidak pernah digunakan untuk /admin.
|--------------------------------------------------------------------------
*/

async function handleNavigation(
    event
) {

    const request =
        event.request;


    try {

        /*
        |--------------------------------------------------------------------------
        | Navigation preload
        |--------------------------------------------------------------------------
        */

        const preloadResponse =
            await event.preloadResponse;


        if (
            preloadResponse
            &&
            preloadResponse.ok
        ) {

            await cachePage(
                request,
                preloadResponse.clone()
            );


            return preloadResponse;

        }


        /*
        |--------------------------------------------------------------------------
        | Network
        |--------------------------------------------------------------------------
        */

        const networkResponse =
            await fetch(
                request
            );


        if (
            networkResponse
            &&
            networkResponse.ok
        ) {

            await cachePage(
                request,
                networkResponse.clone()
            );


            return networkResponse;

        }


        /*
        |--------------------------------------------------------------------------
        | Server error -> coba cache exact.
        |--------------------------------------------------------------------------
        */

        const cachedExact =
            await caches.match(
                request,
                {
                    ignoreSearch:
                        false,
                }
            );


        if (
            cachedExact
        ) {

            return cachedExact;

        }


        return networkResponse;


    } catch (
        error
    ) {

        /*
        |--------------------------------------------------------------------------
        | Offline exact page.
        |--------------------------------------------------------------------------
        */

        const exact =
            await caches.match(
                request
            );


        if (
            exact
        ) {

            return exact;

        }


        /*
        |--------------------------------------------------------------------------
        | Ignore query fallback.
        |--------------------------------------------------------------------------
        */

        const ignoreQuery =
            await caches.match(
                request,
                {
                    ignoreSearch:
                        true,
                }
            );


        if (
            ignoreQuery
        ) {

            return ignoreQuery;

        }


        /*
        |--------------------------------------------------------------------------
        | Frontend landing fallback.
        |--------------------------------------------------------------------------
        */

        const home =
            await caches.match(
                '/'
            );


        if (
            home
        ) {

            return home;

        }


        /*
        |--------------------------------------------------------------------------
        | Minimal fallback.
        |--------------------------------------------------------------------------
        */

        return new Response(
            `
            <!DOCTYPE html>

            <html lang="id">

            <head>

                <meta charset="UTF-8">

                <meta
                    name="viewport"
                    content="width=device-width, initial-scale=1"
                >

                <title>
                    BaliHiking - Offline
                </title>

                <style>

                    body {
                        margin: 0;
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding: 24px;
                        box-sizing: border-box;
                        background: #fbfbfa;
                        color: #1a382b;
                        font-family: Arial, sans-serif;
                    }

                    .card {
                        width: 100%;
                        max-width: 360px;
                        padding: 28px;
                        border-radius: 24px;
                        background: white;
                        text-align: center;
                        box-shadow: 0 12px 35px rgba(0,0,0,.08);
                    }

                    h1 {
                        margin: 0;
                        font-size: 22px;
                    }

                    p {
                        margin-top: 10px;
                        color: #718078;
                        line-height: 1.6;
                        font-size: 14px;
                    }

                </style>

            </head>

            <body>

                <div class="card">

                    <h1>
                        BaliHiking
                    </h1>

                    <p>
                        Perangkat sedang offline dan halaman ini
                        belum tersimpan di perangkat.
                    </p>

                </div>

            </body>

            </html>
            `,
            {
                status:
                    503,

                headers: {
                    'Content-Type':
                        'text/html; charset=utf-8',
                },
            }
        );

    }

}



/*
|--------------------------------------------------------------------------
| CACHE PAGE
|--------------------------------------------------------------------------
*/

async function cachePage(
    request,
    response
) {

    const url =
        new URL(
            request.url
        );


    /*
    |--------------------------------------------------------------------------
    | EXTRA SAFETY:
    | jangan cache admin walaupun function terpanggil tidak sengaja.
    |--------------------------------------------------------------------------
    */

    if (
        isNetworkOnlyPath(
            url.pathname
        )
    ) {

        return;

    }


    if (
        !canCacheResponse(
            response
        )
    ) {

        return;

    }


    const cache =
        await caches.open(
            PAGE_CACHE
        );


    await cache.put(
        request,
        response
    );

}



/*
|--------------------------------------------------------------------------
| CACHE FIRST
|--------------------------------------------------------------------------
*/

async function cacheFirst(
    request,
    cacheName
) {

    const cache =
        await caches.open(
            cacheName
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
            canCacheResponse(
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

        throw error;

    }

}



/*
|--------------------------------------------------------------------------
| STALE WHILE REVALIDATE
|--------------------------------------------------------------------------
*/

async function staleWhileRevalidate(
    request,
    cacheName
) {

    const cache =
        await caches.open(
            cacheName
        );


    const cached =
        await cache.match(
            request
        );


    const networkPromise =
        fetch(
            request
        )
        .then(
            async response => {

                if (
                    canCacheResponse(
                        response
                    )
                ) {

                    await cache.put(
                        request,
                        response.clone()
                    );

                }


                return response;

            }
        )
        .catch(
            () =>
                null
        );


    if (
        cached
    ) {

        networkPromise;


        return cached;

    }


    const response =
        await networkPromise;


    if (
        response
    ) {

        return response;

    }


    return new Response(
        '',
        {
            status:
                504,
        }
    );

}



/*
|--------------------------------------------------------------------------
| MESSAGE
|--------------------------------------------------------------------------
*/

self.addEventListener(
    'message',
    event => {

        if (
            event.data
            &&
            event.data.type
            ===
            'SKIP_WAITING'
        ) {

            self.skipWaiting();

        }

    }
);