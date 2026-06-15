<div class="w-full h-screen relative bg-slate-950 text-slate-100 overflow-hidden"
     x-data="{
         openDrawer: false,
         radarScanning: false,
         radarAlert: null,
     }"
     @open-drawer.window="openDrawer = true"
     @radar-alert.window="
         radarScanning = false;
         radarAlert = $event.detail[0];
         setTimeout(() => { radarAlert = null }, 8000);
     ">

    {{-- ===== Admin Dashboard Button ===== --}}
    @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="fixed top-6 right-6 z-50">
            <a href="{{ route('admin.approval') }}" class="group relative px-4 py-2.5 bg-slate-900/80 hover:bg-slate-800 backdrop-blur border border-indigo-500/50 text-indigo-300 hover:text-indigo-200 font-semibold rounded-xl shadow-lg shadow-indigo-900/30 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Admin Panel
            </a>
        </div>
    @endif

    {{-- ===== Custom CSS for Pulse & Drawer ===== --}}
    <style>
        /* Drawer backdrop blur overlay */
        .drawer-backdrop {
            background: rgba(2, 6, 23, 0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* Category badge dynamic colors */
        .badge-penampakan     { background: rgba(239,68,68,0.15);  color: #f87171; border-color: rgba(239,68,68,0.4); }
        .badge-tempat_bersejarah { background: rgba(168,85,247,0.15); color: #c084fc; border-color: rgba(168,85,247,0.4); }
        .badge-mitos_hewan    { background: rgba(245,158,11,0.15); color: #fbbf24; border-color: rgba(245,158,11,0.4); }
        .badge-kutukan        { background: rgba(16,185,129,0.15); color: #34d399; border-color: rgba(16,185,129,0.4); }
    </style>

    {{-- ===== Map Container ===== --}}
    <div id="map" wire:ignore x-data="initMapbox()" class="w-full h-full"></div>

    {{-- ===== Radar Spooky Button (floating) ===== --}}
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50" wire:ignore>
        <button @click="
            radarScanning = true;
            radarAlert = null;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        $wire.checkRadar(pos.coords.latitude, pos.coords.longitude);
                    },
                    (err) => {
                        radarScanning = false;
                        let msg = '❌ Gagal mengakses lokasi.';
                        if (err.code === 1) {
                            msg = '🚫 Izin lokasi ditolak. Radar tidak dapat mendeteksi tempat sekitar. Aktifkan izin lokasi di pengaturan browser Anda.';
                        } else if (err.code === 2) {
                            msg = '📡 Posisi tidak tersedia. Perangkat tidak dapat menentukan lokasimu saat ini.';
                        } else if (err.code === 3) {
                            msg = '⏱️ Waktu habis. Permintaan lokasi terlalu lama. Coba lagi di area dengan sinyal lebih baik.';
                        }
                        radarAlert = { detected: false, message: msg };
                        setTimeout(() => { radarAlert = null }, 8000);
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                radarScanning = false;
                radarAlert = { detected: false, message: '❌ Browser tidak mendukung Geolocation.' };
                setTimeout(() => { radarAlert = null }, 6000);
            }
        "
        :disabled="radarScanning"
        class="group relative px-6 py-3 bg-gradient-to-r from-purple-600 to-red-600 hover:from-purple-500 hover:to-red-500 text-white font-semibold rounded-full shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 transition-all duration-300 disabled:opacity-60 disabled:cursor-wait flex items-center gap-3">
            {{-- Pulsing ring animation --}}
            <span class="absolute inset-0 rounded-full border-2 border-purple-400 animate-ping opacity-30 group-hover:opacity-50"></span>

            {{-- Radar icon --}}
            <svg class="w-5 h-5" :class="radarScanning && 'animate-spin'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.651a3.75 3.75 0 010-5.303m5.304 0a3.75 3.75 0 010 5.303m-7.425 2.122a6.75 6.75 0 010-9.546m9.546 0a6.75 6.75 0 010 9.546M5.106 18.894c-3.808-3.808-3.808-9.98 0-13.789m13.788 0c3.808 3.808 3.808 9.981 0 13.79M12 12h.008v.007H12V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
            </svg>

            <span x-text="radarScanning ? 'Memindai...' : 'Aktifkan Radar Spooky'"></span>
        </button>
    </div>

    {{-- ===== Add Mystery Floating Button ===== --}}
    <div class="fixed bottom-8 right-8 z-50">
        @auth
            <button onclick="Livewire.dispatch('openAddLocationModal', { lat: null, lng: null })" class="group relative px-5 py-3 bg-slate-800/90 hover:bg-slate-700 backdrop-blur border border-slate-600 text-cyan-400 hover:text-cyan-300 font-semibold rounded-xl shadow-lg shadow-cyan-900/20 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Tambah Lokasi Misteri
            </button>
        @else
            <a href="{{ route('login') }}" class="group relative px-5 py-3 bg-slate-800/90 hover:bg-slate-700 backdrop-blur border border-slate-600 text-slate-300 hover:text-white font-semibold rounded-xl shadow-lg transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                Login untuk Menambah Lokasi
            </a>
        @endauth
    </div>

    {{-- ===== Radar Alert Banner ===== --}}
    <div x-show="radarAlert"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 -translate-y-6"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-6"
         class="fixed top-6 left-1/2 -translate-x-1/2 z-[60] max-w-xl w-full px-4"
         style="display:none;">
        <div class="rounded-xl px-6 py-4 shadow-2xl backdrop-blur-md border flex items-start gap-3"
             :class="radarAlert?.detected
                 ? 'bg-red-900/80 border-red-500/50 shadow-red-500/20'
                 : 'bg-emerald-900/80 border-emerald-500/50 shadow-emerald-500/20'">
            <span class="text-2xl mt-0.5" x-text="radarAlert?.detected ? '👻' : '🛡️'"></span>
            <div class="flex-1">
                <p class="font-semibold text-sm leading-relaxed" x-text="radarAlert?.message"></p>
                <p x-show="radarAlert?.detected" class="text-xs mt-1 opacity-70" x-text="radarAlert?.count + ' lokasi mistis terdeteksi dalam radius 1 KM'"></p>
            </div>
            <button @click="radarAlert = null" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- ===== Backdrop Overlay ===== --}}
    <div x-show="openDrawer"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="openDrawer = false"
         class="fixed inset-0 z-40 drawer-backdrop"
         style="display:none;"></div>

    {{-- ===== Sidebar Drawer ===== --}}
    <div x-show="openDrawer"
         x-transition:enter="transition-all ease-out duration-500"
         x-transition:enter-start="translate-x-full opacity-0"
         x-transition:enter-end="translate-x-0 opacity-100"
         x-transition:leave="transition-all ease-in duration-300"
         x-transition:leave-start="translate-x-0 opacity-100"
         x-transition:leave-end="translate-x-full opacity-0"
         class="fixed top-0 right-0 h-full w-96 bg-slate-900/95 backdrop-blur-lg shadow-2xl z-50 transform flex flex-col border-l border-slate-700/50"
         style="display: none;">

         <div class="p-6 overflow-y-auto h-full relative">
             {{-- Close button --}}
             <button @click="openDrawer = false" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800/80 text-slate-400 hover:text-white hover:bg-slate-700 transition-all duration-200 focus:outline-none">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
             </button>

             @if($selectedMystery)
                 {{-- Title --}}
                 <h2 class="text-2xl font-bold mb-3 pr-10 leading-tight">{{ $selectedMystery->title }}</h2>

                 {{-- Category badge (dynamic color) --}}
                 <span class="px-3 py-1 text-xs font-semibold rounded-full border uppercase tracking-wider inline-block mb-4 badge-{{ $selectedMystery->category }}">
                     {{ str_replace('_', ' ', $selectedMystery->category) }}
                 </span>

                 {{-- Scary level --}}
                 <div class="flex items-center mb-6">
                    <span class="text-sm text-slate-400 mr-2">Scary Level:</span>
                    <div class="flex gap-0.5">
                        @for($i = 0; $i < $selectedMystery->scary_level; $i++)
                            <svg class="w-4 h-4 text-red-500 drop-shadow-[0_0_4px_rgba(239,68,68,0.6)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
                        @endfor
                        @for($i = $selectedMystery->scary_level; $i < 5; $i++)
                            <svg class="w-4 h-4 text-slate-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
                        @endfor
                    </div>
                 </div>

                 {{-- Description --}}
                 <p class="text-slate-300 mb-8 leading-relaxed">{{ $selectedMystery->description }}</p>

                 {{-- Live Reports Section with polling --}}
                 <div wire:poll.15s="refreshReports">
                     <h3 class="text-lg font-semibold border-b border-slate-700 pb-2 mb-4 text-slate-200 flex items-center justify-between">
                         <span>Live Reports</span>
                         <span class="relative flex h-2 w-2">
                             <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                             <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                         </span>
                     </h3>
                     <div class="space-y-3 mb-6">
                         @forelse($selectedMystery->liveReports as $report)
                             <div class="bg-slate-800/50 p-4 rounded-lg border border-slate-700/50 hover:border-slate-600 transition-all duration-200 hover:bg-slate-800/70">
                                 <p class="text-sm text-slate-300 leading-relaxed">{{ $report->status_note }}</p>
                                 <span class="text-xs text-slate-500 mt-3 flex items-center">
                                     <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                     {{ $report->created_at->diffForHumans() }}
                                 </span>
                             </div>
                         @empty
                             <div class="bg-slate-800/30 p-4 rounded-lg border border-slate-700/30 text-center">
                                <p class="text-slate-500 italic text-sm">Belum ada laporan.</p>
                             </div>
                         @endforelse
                     </div>
                 </div>

                 {{-- Submit Report Form --}}
                 <div class="border-t border-slate-700 pt-4">
                     <h4 class="text-sm font-semibold text-slate-300 mb-3 uppercase tracking-wider">Kirim Laporan Baru</h4>
                     <form wire:submit.prevent="submitReport" class="space-y-3">
                         <div>
                             <textarea
                                 wire:model="reportText"
                                 rows="3"
                                 maxlength="150"
                                 placeholder="Ceritakan pengalamanmu di lokasi ini..."
                                 class="w-full bg-slate-800 border rounded-lg px-4 py-3 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-1 resize-none transition-all duration-200 @error('reportText') border-red-500 focus:border-red-500 focus:ring-red-500/50 @else border-slate-700 focus:border-purple-500 focus:ring-purple-500/50 @enderror"
                             ></textarea>
                             <div class="flex items-center justify-between mt-1">
                                 @error('reportText')
                                     <p class="text-xs text-red-400 flex items-center gap-1">
                                         <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                         {{ $message }}
                                     </p>
                                 @else
                                     <span></span>
                                 @enderror
                                 <span class="text-xs text-slate-500">{{ strlen($reportText) }}/150</span>
                             </div>
                         </div>
                         <button type="submit"
                             class="w-full py-2.5 bg-gradient-to-r from-purple-600 to-red-600 hover:from-purple-500 hover:to-red-500 text-white text-sm font-semibold rounded-lg shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 transition-all duration-300 flex items-center justify-center gap-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                             Kirim Laporan Mistis Langsung
                         </button>
                     </form>
                 </div>
             @endif
         </div>
    </div>

    @script
    <script>
        Alpine.data('initMapbox', () => ({
            map: null,
            pulseAnimId: null,
            init() {
                mapboxgl.accessToken = '{{ config('services.mapbox.token') }}';

                this.map = new mapboxgl.Map({
                    container: 'map',
                    style: 'mapbox://styles/mapbox/dark-v11',
                    center: [106.8456, -6.2088],
                    zoom: 10
                });

                this.map.on('load', () => {
                    // ── GeoJSON Source ──
                    this.map.addSource('mysteries', {
                        type: 'geojson',
                        data: { type: 'FeatureCollection', features: [] }
                    });

                    // ── Layer 1: Outer glow ring (category-colored) ──
                    this.map.addLayer({
                        id: 'mystery-glow',
                        type: 'circle',
                        source: 'mysteries',
                        paint: {
                            'circle-radius': 14,
                            'circle-color': [
                                'match', ['get', 'category'],
                                'penampakan',        'rgba(239,68,68,0.25)',
                                'tempat_bersejarah', 'rgba(168,85,247,0.25)',
                                'mitos_hewan',       'rgba(245,158,11,0.25)',
                                'kutukan',           'rgba(16,185,129,0.25)',
                                'rgba(148,163,184,0.25)'
                            ],
                            'circle-blur': 0.6,
                        }
                    });

                    // ── Layer 2: Pulse ring for recent reports ──
                    this.map.addLayer({
                        id: 'mystery-pulse',
                        type: 'circle',
                        source: 'mysteries',
                        filter: ['>', ['get', 'has_recent_report'], 0],
                        paint: {
                            'circle-radius': 18,
                            'circle-color': 'rgba(239,68,68,0.0)',
                            'circle-stroke-width': 2,
                            'circle-stroke-color': [
                                'match', ['get', 'category'],
                                'penampakan',        'rgba(239,68,68,0.6)',
                                'tempat_bersejarah', 'rgba(168,85,247,0.6)',
                                'mitos_hewan',       'rgba(245,158,11,0.6)',
                                'kutukan',           'rgba(16,185,129,0.6)',
                                'rgba(148,163,184,0.6)'
                            ],
                            'circle-opacity': 0.8,
                            'circle-stroke-opacity': 0.8,
                        }
                    });

                    // ── Layer 3: Main marker dot (category-colored) ──
                    this.map.addLayer({
                        id: 'mystery-markers',
                        type: 'circle',
                        source: 'mysteries',
                        paint: {
                            'circle-radius': [
                                'interpolate', ['linear'], ['zoom'],
                                8, 5,
                                12, 8,
                                16, 12
                            ],
                            'circle-color': [
                                'match', ['get', 'category'],
                                'penampakan',        '#ef4444',
                                'tempat_bersejarah', '#a855f7',
                                'mitos_hewan',       '#f59e0b',
                                'kutukan',           '#10b981',
                                '#94a3b8'
                            ],
                            'circle-stroke-width': 2,
                            'circle-stroke-color': [
                                'match', ['get', 'category'],
                                'penampakan',        'rgba(239,68,68,0.3)',
                                'tempat_bersejarah', 'rgba(168,85,247,0.3)',
                                'mitos_hewan',       'rgba(245,158,11,0.3)',
                                'kutukan',           'rgba(16,185,129,0.3)',
                                'rgba(148,163,184,0.3)'
                            ],
                        }
                    });

                    // ── Layer 4: Inner white dot for contrast ──
                    this.map.addLayer({
                        id: 'mystery-inner',
                        type: 'circle',
                        source: 'mysteries',
                        paint: {
                            'circle-radius': [
                                'interpolate', ['linear'], ['zoom'],
                                8, 1.5,
                                12, 2.5,
                                16, 3.5
                            ],
                            'circle-color': '#ffffff',
                            'circle-opacity': 0.9,
                        }
                    });

                    // ── Pulse Animation Loop ──
                    this.startPulseAnimation();

                    // ── Click event ──
                    this.map.on('click', 'mystery-markers', (e) => {
                        const id = e.features[0].properties.id;
                        $wire.selectMystery(id);
                    });

                    // ── Hover cursor ──
                    this.map.on('mouseenter', 'mystery-markers', () => {
                        this.map.getCanvas().style.cursor = 'pointer';
                    });
                    this.map.on('mouseleave', 'mystery-markers', () => {
                        this.map.getCanvas().style.cursor = '';
                    });

                    // ── Empty Map Click to Add Location ──
                    this.map.on('click', (e) => {
                        // Check if we clicked on a marker first
                        const features = this.map.queryRenderedFeatures(e.point, {
                            layers: ['mystery-markers']
                        });

                        // If no markers were clicked, it's an empty spot
                        if (!features.length) {
                            @auth
                                Livewire.dispatch('openAddLocationModal', { lat: e.lngLat.lat, lng: e.lngLat.lng });
                            @else
                                alert('Silakan login terlebih dahulu untuk menyarankan lokasi misteri baru.');
                            @endauth
                        }
                    });

                    this.updateBounds();
                });

                this.map.on('moveend', () => {
                    this.updateBounds();
                });

                // ── Watch for data changes ──
                this.$watch('$wire.locations', (newLocations) => {
                    if (this.map && this.map.getSource('mysteries')) {
                        const features = newLocations.map(location => ({
                            type: 'Feature',
                            geometry: {
                                type: 'Point',
                                coordinates: [parseFloat(location.longitude), parseFloat(location.latitude)]
                            },
                            properties: {
                                id: location.id,
                                title: location.title,
                                description: location.description,
                                category: location.category,
                                scary_level: location.scary_level,
                                has_recent_report: location.has_recent_report || 0,
                            }
                        }));

                        this.map.getSource('mysteries').setData({
                            type: 'FeatureCollection',
                            features: features
                        });
                    }
                });
            },

            startPulseAnimation() {
                let radius = 14;
                let opacity = 0.8;
                let growing = true;

                const animate = () => {
                    if (growing) {
                        radius += 0.3;
                        opacity -= 0.015;
                        if (radius >= 26) growing = false;
                    } else {
                        radius -= 0.3;
                        opacity += 0.015;
                        if (radius <= 14) growing = true;
                    }

                    if (this.map && this.map.getLayer('mystery-pulse')) {
                        this.map.setPaintProperty('mystery-pulse', 'circle-radius', radius);
                        this.map.setPaintProperty('mystery-pulse', 'circle-stroke-opacity', Math.max(0, opacity));
                    }

                    this.pulseAnimId = requestAnimationFrame(animate);
                };

                this.pulseAnimId = requestAnimationFrame(animate);
            },

            updateBounds() {
                const bounds = this.map.getBounds();
                $wire.updateBounds(
                    bounds.getWest(),
                    bounds.getSouth(),
                    bounds.getEast(),
                    bounds.getNorth()
                );
            },

            destroy() {
                if (this.pulseAnimId) cancelAnimationFrame(this.pulseAnimId);
            }
        }));
    </script>
    @endscript

    {{-- ===== Add Location Modal Component ===== --}}
    <livewire:add-location-modal />
</div>
