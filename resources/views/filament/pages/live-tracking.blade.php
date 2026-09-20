<x-filament-panels::page>

    @push('styles')

        <link
            rel="stylesheet"
            href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        >

        <style>

            /*
            |--------------------------------------------------------------------------
            | PAGE
            |--------------------------------------------------------------------------
            */

            .tracking-layout {
                display: grid;
                grid-template-columns: 340px minmax(0, 1fr);
                gap: 16px;
                min-height: 720px;
            }


            .tracking-sidebar {
                display: flex;
                min-height: 720px;
                max-height: 720px;
                flex-direction: column;
                overflow: hidden;
                border: 1px solid rgb(229 231 235);
                border-radius: 16px;
                background: white;
            }


            .dark .tracking-sidebar {
                border-color: rgb(55 65 81);
                background: rgb(17 24 39);
            }


            .activity-list {
                flex: 1;
                overflow-y: auto;
            }


            /*
            |--------------------------------------------------------------------------
            | MAP
            |--------------------------------------------------------------------------
            */

            #liveTrackingMap {
                width: 100%;
                height: 720px;
                min-height: 720px;
                border: 1px solid rgb(229 231 235);
                border-radius: 16px;
                background: rgb(243 244 246);
            }


            .dark #liveTrackingMap {
                border-color: rgb(55 65 81);
            }


            /*
            |--------------------------------------------------------------------------
            | MARKER
            |--------------------------------------------------------------------------
            */

            .tracking-marker {
                border: 0 !important;
                background: transparent !important;
            }


            .tracking-marker-body {
                display: flex;
                width: 38px;
                height: 38px;
                align-items: center;
                justify-content: center;
                border: 3px solid white;
                border-radius: 999px;
                box-shadow: 0 5px 14px rgba(0, 0, 0, .25);
                font-size: 16px;
            }


            .tracking-marker-active {
                background: #059669;
            }


            .tracking-marker-completed {
                background: #64748b;
            }


            .tracking-marker-sos {
                background: #dc2626;
                animation: sos-pulse 1.15s infinite;
            }


            .tracking-start-marker {
                display: flex;
                width: 30px;
                height: 30px;
                align-items: center;
                justify-content: center;
                border: 3px solid white;
                border-radius: 999px;
                background: #16a34a;
                color: white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .22);
                font-size: 11px;
                font-weight: 900;
            }


            .tracking-finish-marker {
                display: flex;
                width: 30px;
                height: 30px;
                align-items: center;
                justify-content: center;
                border: 3px solid white;
                border-radius: 999px;
                background: #2563eb;
                color: white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .22);
                font-size: 13px;
            }


            .tracking-sos-point {
                display: flex;
                width: 28px;
                height: 28px;
                align-items: center;
                justify-content: center;
                border: 3px solid white;
                border-radius: 999px;
                background: #dc2626;
                color: white;
                box-shadow: 0 4px 12px rgba(220, 38, 38, .35);
                animation: sos-pulse 1.15s infinite;
            }


            @keyframes sos-pulse {
                0% {
                    box-shadow: 0 0 0 0 rgba(220, 38, 38, .55);
                }

                70% {
                    box-shadow: 0 0 0 12px rgba(220, 38, 38, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | POPUP
            |--------------------------------------------------------------------------
            */

            .leaflet-popup-content-wrapper {
                overflow: hidden;
                padding: 0 !important;
                border-radius: 14px !important;
            }


            .leaflet-popup-content {
                width: 280px !important;
                margin: 0 !important;
            }


            .activity-item-selected {
                border-color: #10b981 !important;
                background: rgba(16, 185, 129, .06) !important;
            }


            /*
            |--------------------------------------------------------------------------
            | RESPONSIVE
            |--------------------------------------------------------------------------
            */

            @media (max-width: 1024px) {
                .tracking-layout {
                    grid-template-columns: 1fr;
                }

                .tracking-sidebar {
                    min-height: auto;
                    max-height: 420px;
                }

                #liveTrackingMap {
                    height: 620px;
                    min-height: 620px;
                }
            }


            @media (max-width: 640px) {
                #liveTrackingMap {
                    height: 520px;
                    min-height: 520px;
                }
            }

        </style>

    @endpush


    {{-- ========================================================= --}}
    {{-- SATU CONTENT WRAPPER --}}
    {{-- ========================================================= --}}

    <div
        class="space-y-4"
        x-data="liveTrackingAdmin()"
        x-init="init()"
    >

        {{-- HEADER --}}

        <div
            class="
                rounded-2xl
                border
                border-gray-200
                bg-white
                p-4
                shadow-sm
                dark:border-gray-800
                dark:bg-gray-900
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-4
                    xl:flex-row
                    xl:items-center
                    xl:justify-between
                "
            >

                <div
                    class="flex items-center gap-3"
                >

                    <div
                        class="
                            relative
                            flex
                            h-11
                            w-11
                            items-center
                            justify-center
                            rounded-xl
                            bg-emerald-100
                            text-emerald-600
                            dark:bg-emerald-950
                            dark:text-emerald-400
                        "
                    >

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.348 14.652a3.75 3.75 0 0 1 0-5.304m5.304 0a3.75 3.75 0 0 1 0 5.304m-7.425 2.122a6.75 6.75 0 0 1 0-9.546m9.546 0a6.75 6.75 0 0 1 0 9.546"
                            />
                        </svg>

                        <span
                            class="
                                absolute
                                right-2
                                top-2
                                h-2
                                w-2
                                animate-ping
                                rounded-full
                                bg-emerald-500
                            "
                        ></span>

                    </div>


                    <div>

                        <h2
                            class="
                                text-base
                                font-bold
                                text-gray-950
                                dark:text-white
                            "
                        >
                            Monitoring Perjalanan Pendaki
                        </h2>

                        <p
                            class="
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            Posisi realtime, histori GPS,
                            titik mulai, finish dan SOS.
                        </p>

                    </div>

                </div>


                {{-- STATS --}}

                <div
                    class="
                        flex
                        flex-wrap
                        items-center
                        gap-2
                    "
                >

                    <div
                        class="
                            rounded-xl
                            border
                            border-gray-200
                            bg-gray-50
                            px-3
                            py-2
                            text-xs
                        "
                    >
                        Total

                        <strong
                            class="ml-1"
                            x-text="stats.total"
                        >
                            0
                        </strong>
                    </div>


                    <div
                        class="
                            rounded-xl
                            border
                            border-emerald-200
                            bg-emerald-50
                            px-3
                            py-2
                            text-xs
                            text-emerald-700
                        "
                    >
                        Berlangsung

                        <strong
                            class="ml-1"
                            x-text="stats.active"
                        >
                            0
                        </strong>
                    </div>


                    <div
                        class="
                            rounded-xl
                            border
                            border-blue-200
                            bg-blue-50
                            px-3
                            py-2
                            text-xs
                            text-blue-700
                        "
                    >
                        Selesai

                        <strong
                            class="ml-1"
                            x-text="stats.completed"
                        >
                            0
                        </strong>
                    </div>


                    <div
                        class="
                            rounded-xl
                            border
                            border-red-200
                            bg-red-50
                            px-3
                            py-2
                            text-xs
                            text-red-700
                        "
                    >
                        SOS

                        <strong
                            class="ml-1"
                            x-text="stats.sos"
                        >
                            0
                        </strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- MAIN --}}
        {{-- ===================================================== --}}

        <div class="tracking-layout">

            {{-- SIDEBAR --}}

            <aside class="tracking-sidebar">

                <div
                    class="
                        border-b
                        border-gray-200
                        p-4
                        dark:border-gray-800
                    "
                >

                    <p
                        class="
                            text-sm
                            font-bold
                            text-gray-950
                            dark:text-white
                        "
                    >
                        Aktivitas Pendaki
                    </p>

                    <p
                        class="
                            mt-1
                            text-[10px]
                            text-gray-500
                        "
                        x-text="`${activities.length} aktivitas`"
                    >
                        0 aktivitas
                    </p>

                </div>


                <div class="activity-list">

                    <template
                        x-for="activity in activities"
                        :key="activity.id"
                    >

                        <button
                            type="button"
                            @click="selectActivity(activity)"
                            class="
                                block
                                w-full
                                border-b
                                border-gray-100
                                p-4
                                text-left
                                transition
                                hover:bg-gray-50
                            "
                            :class="{
                                'activity-item-selected':
                                    selectedActivityId === activity.id
                            }"
                        >

                            <div
                                class="
                                    flex
                                    items-center
                                    justify-between
                                    gap-3
                                "
                            >

                                <div class="min-w-0">

                                    <p
                                        class="
                                            truncate
                                            text-xs
                                            font-bold
                                        "
                                        x-text="activity.user_name"
                                    ></p>

                                    <p
                                        class="
                                            mt-1
                                            truncate
                                            text-[10px]
                                            text-gray-500
                                        "
                                    >
                                        <span
                                            x-text="activity.mountain_name"
                                        ></span>

                                        ·

                                        <span
                                            x-text="activity.trail_name"
                                        ></span>
                                    </p>

                                </div>


                                <span
                                    class="
                                        rounded-full
                                        px-2
                                        py-1
                                        text-[8px]
                                        font-bold
                                    "
                                    :class="
                                        activity.status === 'active'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-blue-100 text-blue-700'
                                    "
                                    x-text="activity.status_label"
                                ></span>

                            </div>

                        </button>

                    </template>

                </div>

            </aside>


            {{-- MAP --}}

            <section
                class="
                    relative
                    min-w-0
                "
            >

                <div
                    x-ref="mapContainer"
                    id="liveTrackingMap"
                ></div>

            </section>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SCRIPT --}}
    {{-- ========================================================= --}}

    @push('scripts')

        <script
            src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        ></script>


        <script>

            function liveTrackingAdmin() {

                return {

                    map:
                        null,

                    loading:
                        false,

                    activities:
                        [],

                    stats: {
                        total:
                            0,

                        active:
                            0,

                        completed:
                            0,

                        sos:
                            0,
                    },

                    selectedActivityId:
                        null,

                    selectedActivity:
                        null,

                    activityLayers:
                        {},

                    pollingTimer:
                        null,

                    endpoint:
                        @js(
                            route(
                                'admin.live-tracking.data'
                            )
                        ),


                    async init() {

                        this.initializeMap();

                        await this.fetchData();

                        this.pollingTimer =
                            setInterval(
                                async () => {

                                    if (
                                        !document.hidden
                                    ) {
                                        await this.fetchData(
                                            true
                                        );
                                    }

                                },
                                5000
                            );

                    },


                    initializeMap() {

                        if (
                            this.map
                        ) {
                            return;
                        }


                        this.map =
                            L.map(
                                this.$refs.mapContainer,
                                {
                                    zoomControl:
                                        false,
                                }
                            )
                            .setView(
                                [
                                    -8.4095,
                                    115.1889
                                ],
                                10
                            );


                        L.control
                            .zoom({
                                position:
                                    'bottomright',
                            })
                            .addTo(
                                this.map
                            );


                        L.tileLayer(
                            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                            {
                                maxZoom:
                                    19,

                                attribution:
                                    '© OpenStreetMap',
                            }
                        )
                        .addTo(
                            this.map
                        );


                        setTimeout(
                            () => {

                                this.map.invalidateSize();

                            },
                            300
                        );

                    },


                    async fetchData(
                        silent = false
                    ) {

                        if (
                            !silent
                        ) {
                            this.loading =
                                true;
                        }


                        try {

                            const response =
                                await fetch(
                                    this.endpoint,
                                    {
                                        credentials:
                                            'same-origin',

                                        headers: {
                                            Accept:
                                                'application/json',
                                        },
                                    }
                                );


                            if (
                                !response.ok
                            ) {
                                throw new Error(
                                    'Gagal mengambil data Live Tracking.'
                                );
                            }


                            const data =
                                await response.json();


                            this.activities =
                                data.activities
                                ?? [];


                            this.stats =
                                data.stats
                                ?? {
                                    total:
                                        0,

                                    active:
                                        0,

                                    completed:
                                        0,

                                    sos:
                                        0,
                                };


                            this.renderActivities();


                        } catch (
                            error
                        ) {

                            console.error(
                                '[BaliHiking Live Tracking]',
                                error
                            );


                        } finally {


                            this.loading =
                                false;

                        }

                    },


                    renderActivities() {

                        /*
                        |--------------------------------------------------------------------------
                        | Hapus layer lama.
                        |--------------------------------------------------------------------------
                        */

                        Object.values(
                            this.activityLayers
                        )
                        .forEach(
                            layers => {

                                if (
                                    layers.group
                                ) {

                                    this.map.removeLayer(
                                        layers.group
                                    );

                                }

                            }
                        );


                        this.activityLayers =
                            {};


                        /*
                        |--------------------------------------------------------------------------
                        | Render ulang.
                        |--------------------------------------------------------------------------
                        */

                        this.activities
                            .forEach(
                                activity => {

                                    this.renderActivity(
                                        activity
                                    );

                                }
                            );

                    },


                    renderActivity(
                        activity
                    ) {

                        const group =
                            L.layerGroup()
                                .addTo(
                                    this.map
                                );


                        /*
                        |--------------------------------------------------------------------------
                        | JALUR RESMI
                        |--------------------------------------------------------------------------
                        */

                        if (
                            Array.isArray(
                                activity.planned_route
                            )
                            &&
                            activity.planned_route.length >= 2
                        ) {

                            L.polyline(
                                activity.planned_route,
                                {
                                    color:
                                        '#f97316',

                                    weight:
                                        4,

                                    opacity:
                                        .45,

                                    dashArray:
                                        '8 7',
                                }
                            )
                            .addTo(
                                group
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | HISTORY POSISI
                        |--------------------------------------------------------------------------
                        */

                        if (
                            Array.isArray(
                                activity.history
                            )
                            &&
                            activity.history.length >= 2
                        ) {

                            const points =
                                activity.history
                                    .map(
                                        point => [
                                            Number(
                                                point.lat
                                            ),

                                            Number(
                                                point.lng
                                            )
                                        ]
                                    );


                            L.polyline(
                                points,
                                {
                                    color:
                                        '#ffffff',

                                    weight:
                                        8,

                                    opacity:
                                        .8,
                                }
                            )
                            .addTo(
                                group
                            );


                            L.polyline(
                                points,
                                {
                                    color:
                                        activity.status
                                        ===
                                        'active'

                                            ?

                                            '#059669'

                                            :

                                            '#2563eb',

                                    weight:
                                        4,

                                    opacity:
                                        .95,
                                }
                            )
                            .addTo(
                                group
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | START
                        |--------------------------------------------------------------------------
                        */

                        if (
                            activity.start_location
                        ) {

                            L.marker(
                                [
                                    Number(
                                        activity.start_location.lat
                                    ),

                                    Number(
                                        activity.start_location.lng
                                    )
                                ],
                                {
                                    icon:
                                        L.divIcon({
                                            html:
                                                '<div class="tracking-start-marker">S</div>',

                                            className:
                                                'tracking-marker',

                                            iconSize:
                                                [
                                                    30,
                                                    30
                                                ],

                                            iconAnchor:
                                                [
                                                    15,
                                                    15
                                                ],
                                        }),
                                }
                            )
                            .addTo(
                                group
                            )
                            .bindTooltip(
                                `START · ${activity.user_name}`
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | LAST POSITION
                        |--------------------------------------------------------------------------
                        */

                        if (
                            activity.last_location
                        ) {

                            const markerClass =
                                activity.last_location.status ===
                                'sos'

                                    ?

                                    'tracking-marker-sos'

                                    :

                                    (
                                        activity.status ===
                                        'active'

                                            ?

                                            'tracking-marker-active'

                                            :

                                            'tracking-marker-completed'
                                    );


                            L.marker(
                                [
                                    Number(
                                        activity.last_location.lat
                                    ),

                                    Number(
                                        activity.last_location.lng
                                    )
                                ],
                                {
                                    icon:
                                        L.divIcon({
                                            html:
                                                `
                                                <div
                                                    class="
                                                        tracking-marker-body
                                                        ${markerClass}
                                                    "
                                                >
                                                    🥾
                                                </div>
                                                `,

                                            className:
                                                'tracking-marker',

                                            iconSize:
                                                [
                                                    38,
                                                    38
                                                ],

                                            iconAnchor:
                                                [
                                                    19,
                                                    19
                                                ],
                                        }),
                                }
                            )
                            .addTo(
                                group
                            )
                            .bindPopup(
                                `
                                <div style="padding:12px">

                                    <strong>
                                        ${activity.user_name}
                                    </strong>

                                    <br>

                                    ${activity.trail_name}

                                    <br><br>

                                    Status:
                                    ${activity.status_label}

                                    <br>

                                    Update:
                                    ${
                                        activity.last_update_label
                                        ??
                                        '-'
                                    }

                                </div>
                                `
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | FINISH
                        |--------------------------------------------------------------------------
                        */

                        if (
                            activity.finish_location
                        ) {

                            L.marker(
                                [
                                    Number(
                                        activity.finish_location.lat
                                    ),

                                    Number(
                                        activity.finish_location.lng
                                    )
                                ],
                                {
                                    icon:
                                        L.divIcon({
                                            html:
                                                '<div class="tracking-finish-marker">🏁</div>',

                                            className:
                                                'tracking-marker',

                                            iconSize:
                                                [
                                                    30,
                                                    30
                                                ],

                                            iconAnchor:
                                                [
                                                    15,
                                                    15
                                                ],
                                        }),
                                }
                            )
                            .addTo(
                                group
                            )
                            .bindTooltip(
                                `SELESAI · ${activity.user_name}`
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SOS
                        |--------------------------------------------------------------------------
                        */

                        if (
                            Array.isArray(
                                activity.sos_locations
                            )
                        ) {

                            activity
                                .sos_locations
                                .forEach(
                                    sos => {

                                        L.marker(
                                            [
                                                Number(
                                                    sos.lat
                                                ),

                                                Number(
                                                    sos.lng
                                                )
                                            ],
                                            {
                                                icon:
                                                    L.divIcon({
                                                        html:
                                                            '<div class="tracking-sos-point">!</div>',

                                                        className:
                                                            'tracking-marker',

                                                        iconSize:
                                                            [
                                                                28,
                                                                28
                                                            ],

                                                        iconAnchor:
                                                            [
                                                                14,
                                                                14
                                                            ],
                                                    }),
                                            }
                                        )
                                        .addTo(
                                            group
                                        )
                                        .bindTooltip(
                                            `SOS · ${activity.user_name}`
                                        );

                                    }
                                );

                        }


                        this.activityLayers[
                            activity.id
                        ] = {
                            group:
                                group,
                        };

                    },


                    selectActivity(
                        activity
                    ) {

                        this.selectedActivityId =
                            activity.id;


                        this.selectedActivity =
                            activity;


                        /*
                        |--------------------------------------------------------------------------
                        | Sembunyikan activity lain.
                        |--------------------------------------------------------------------------
                        */

                        Object.entries(
                            this.activityLayers
                        )
                        .forEach(
                            ([
                                id,
                                layers
                            ]) => {

                                if (
                                    String(id) ===
                                    String(activity.id)
                                ) {

                                    if (
                                        !this.map.hasLayer(
                                            layers.group
                                        )
                                    ) {

                                        layers.group.addTo(
                                            this.map
                                        );

                                    }


                                } else {


                                    if (
                                        this.map.hasLayer(
                                            layers.group
                                        )
                                    ) {

                                        this.map.removeLayer(
                                            layers.group
                                        );

                                    }

                                }

                            }
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | ZOOM KE HISTORY ACTIVITY
                        |--------------------------------------------------------------------------
                        */

                        const bounds =
                            [];


                        if (
                            Array.isArray(
                                activity.history
                            )
                        ) {

                            activity.history
                                .forEach(
                                    point => {

                                        bounds.push([
                                            Number(
                                                point.lat
                                            ),

                                            Number(
                                                point.lng
                                            )
                                        ]);

                                    }
                                );

                        }


                        if (
                            bounds.length > 0
                        ) {

                            this.map.fitBounds(
                                bounds,
                                {
                                    padding:
                                        [
                                            50,
                                            50
                                        ],

                                    maxZoom:
                                        17,
                                }
                            );

                        }

                    },

                };

            }

        </script>

    @endpush

</x-filament-panels::page>