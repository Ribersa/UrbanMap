<div class="w-full h-screen relative bg-slate-950 text-slate-100 overflow-hidden"
     x-data="{
         openDrawer: false,
         radarScanning: false,
         radarAlert: null,
         showWelcome: false,
         tourStep: 0,
         init() {
             if (!localStorage.getItem('visited')) {
                 this.showWelcome = true;
             }
         },
         startTour() {
             this.showWelcome = false;
             this.tourStep = 1;
         },
         skipTour() {
             this.showWelcome = false;
             this.tourStep = 0;
             localStorage.setItem('visited', 'true');
         },
         nextStep() {
             if (this.tourStep >= 4) {
                 this.tourStep = 0;
                 localStorage.setItem('visited', 'true');
             } else {
                 this.tourStep++;
             }
         }
     }"
     @open-drawer.window="openDrawer = true"
     @radar-alert.window="
         radarScanning = false;
         radarAlert = $event.detail[0];
         setTimeout(() => { radarAlert = null }, 8000);
     ">

    {{-- ===== Top Right Controls (Admin & Settings) ===== --}}
    <div class="fixed top-6 right-6 z-50 flex items-start gap-4">
        {{-- ===== Admin Dashboard Button ===== --}}
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('admin.approval') }}" class="group relative px-4 py-2.5 bg-slate-900/80 hover:bg-slate-800 backdrop-blur border border-indigo-500/50 text-indigo-300 hover:text-indigo-200 font-semibold rounded-xl shadow-lg shadow-indigo-900/30 transition-all duration-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Admin Panel
            </a>
        @endif

        {{-- ===== Mailbox Notifications ===== --}}
        @auth
        <div x-data="{ showMailbox: false }" class="relative">
            <button @click="showMailbox = !showMailbox; if(showMailbox) { $wire.markMailboxAsRead(); }" class="relative p-2.5 bg-slate-900/80 hover:bg-slate-800 backdrop-blur border border-slate-700/50 text-slate-300 hover:text-white rounded-xl shadow-lg transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if($mailboxes->where('is_read', false)->count() > 0)
                    <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                @endif
            </button>

            <div x-show="showMailbox" @click.away="showMailbox = false" x-transition
                 class="absolute top-full right-0 mt-2 w-80 bg-slate-950/95 backdrop-blur-md border border-slate-700/50 rounded-xl shadow-2xl p-4 max-h-96 overflow-y-auto"
                 style="display: none;">
                <h4 class="text-sm font-semibold text-slate-300 mb-3 border-b border-slate-700/50 pb-2">Kotak Pesan</h4>
                <div class="space-y-3">
                    @forelse($mailboxes as $mail)
                        <div class="p-3 bg-slate-900/50 border border-slate-800 rounded-lg relative group transition-colors hover:bg-slate-800">
                            <h5 class="text-sm font-semibold text-slate-200 pr-6">{{ $mail->title }}</h5>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">{{ $mail->message }}</p>
                            <span class="text-[10px] text-slate-500 mt-2 block">{{ $mail->created_at->diffForHumans() }}</span>
                            
                            <button wire:click="deleteMail({{ $mail->id }})" class="absolute top-3 right-3 text-slate-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-500 text-sm">Belum ada pesan.</div>
                    @endforelse
                </div>
            </div>
        </div>
        @endauth

        {{-- ===== Settings Canvas ===== --}}
        <div x-data="{ showSettings: false, theme: 'mapbox://styles/mapbox/dark-v11' }" class="relative">
            <button @click="showSettings = !showSettings" class="p-2.5 bg-slate-900/80 hover:bg-slate-800 backdrop-blur border border-slate-700/50 text-slate-300 hover:text-white rounded-xl shadow-lg transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </button>
            
            <div x-show="showSettings" @click.away="showSettings = false" x-transition 
                 class="absolute top-full right-0 mt-2 w-56 bg-slate-900/90 backdrop-blur-md border border-slate-700/50 rounded-xl shadow-2xl p-4"
                 style="display: none;">
                <h4 class="text-sm font-semibold text-slate-300 mb-3 border-b border-slate-700/50 pb-2">Pengaturan Peta</h4>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="mapTheme" value="mapbox://styles/mapbox/dark-v11" x-model="theme" @change="$dispatch('change-theme', theme)" class="form-radio text-purple-500 bg-slate-800 border-slate-600 focus:ring-purple-500 focus:ring-offset-slate-900">
                        <span class="text-sm text-slate-400 group-hover:text-slate-200 transition-colors">Tema Dark</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="mapTheme" value="mapbox://styles/mapbox/satellite-v9" x-model="theme" @change="$dispatch('change-theme', theme)" class="form-radio text-purple-500 bg-slate-800 border-slate-600 focus:ring-purple-500 focus:ring-offset-slate-900">
                        <span class="text-sm text-slate-400 group-hover:text-slate-200 transition-colors">Tema Satellite</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="mapTheme" value="mapbox://styles/mapbox/light-v11" x-model="theme" @change="$dispatch('change-theme', theme)" class="form-radio text-purple-500 bg-slate-800 border-slate-600 focus:ring-purple-500 focus:ring-offset-slate-900">
                        <span class="text-sm text-slate-400 group-hover:text-slate-200 transition-colors">Tema Light</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="mapTheme" value="mapbox://styles/mapbox/streets-v12" x-model="theme" @change="$dispatch('change-theme', theme)" class="form-radio text-purple-500 bg-slate-800 border-slate-600 focus:ring-purple-500 focus:ring-offset-slate-900">
                        <span class="text-sm text-slate-400 group-hover:text-slate-200 transition-colors">Tema Streets</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="mapTheme" value="mapbox://styles/mapbox/outdoors-v12" x-model="theme" @change="$dispatch('change-theme', theme)" class="form-radio text-purple-500 bg-slate-800 border-slate-600 focus:ring-purple-500 focus:ring-offset-slate-900">
                        <span class="text-sm text-slate-400 group-hover:text-slate-200 transition-colors">Tema Outdoors</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="radio" name="mapTheme" value="mapbox://styles/mapbox/navigation-night-v1" x-model="theme" @change="$dispatch('change-theme', theme)" class="form-radio text-purple-500 bg-slate-800 border-slate-600 focus:ring-purple-500 focus:ring-offset-slate-900">
                        <span class="text-sm text-slate-400 group-hover:text-slate-200 transition-colors">Tema Nav Night</span>
                    </label>
                    
                    <hr class="border-slate-700/50 my-3">
                    
                    <button @click="showSettings = false; startTour()" class="w-full flex items-center justify-between text-sm text-slate-400 hover:text-cyan-400 transition-colors group">
                        <span>Bantuan / Tur Ulang</span>
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </button>

                    @auth
                        <hr class="border-slate-700/50 my-3">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-between text-sm text-red-400 hover:text-red-300 transition-colors group">
                                <span class="truncate pr-2">Keluar ({{ auth()->user()->name }})</span>
                                <svg class="w-4 h-4 flex-shrink-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Welcome Modal ===== --}}
    <div x-show="showWelcome" 
         x-transition.opacity.duration.500ms
         class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/80 backdrop-blur-sm"
         style="display: none;">
        <div class="bg-slate-900 border border-slate-700/50 rounded-2xl shadow-2xl max-w-lg w-full p-8 relative transform transition-all text-center mx-4">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-cyan-500/10 mb-6">
                <svg class="h-8 w-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-3">Selamat Datang di Peta Misteri Lokal</h2>
            <p class="text-slate-400 mb-8 leading-relaxed">Platform berbasis komunitas untuk memetakan kisah mistis, sejarah wingit, dan mitos di sekitarmu. Bantu kami mengungkap misteri dengan ikut berkontribusi!</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button @click="startTour()" class="px-6 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white font-semibold rounded-xl transition-colors w-full sm:w-auto shadow-lg shadow-cyan-500/30">Mulai Tur Panduan</button>
                <button @click="skipTour()" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl transition-colors border border-slate-700 w-full sm:w-auto">Lewati</button>
            </div>
        </div>
    </div>

    {{-- ===== Tour Overlay & Tooltips ===== --}}
    <div x-show="tourStep > 0" class="fixed inset-0 bg-slate-950/70 z-[60] pointer-events-auto transition-opacity duration-300" style="display: none;">
        <div class="absolute inset-0 z-[70] pointer-events-none">
            <div class="bg-slate-800 border border-cyan-500/50 rounded-xl p-5 shadow-2xl max-w-sm pointer-events-auto absolute transition-all duration-500"
                 :class="{
                     'top-1/3 left-1/2 -translate-x-1/2 text-center': tourStep === 1,
                     'bottom-28 left-1/2 -translate-x-1/2 text-center': tourStep === 2,
                     'bottom-8 left-80 text-left': tourStep === 3,
                     'bottom-28 right-8 text-right': tourStep === 4
                 }">
                <h3 class="text-lg font-bold text-white mb-2" x-text="
                    tourStep === 1 ? 'Peta Interaktif' : 
                    (tourStep === 2 ? 'Radar Gaib' : 
                    (tourStep === 3 ? 'Legenda Warna' : 'Kontribusi Lokasi'))
                "></h3>
                <p class="text-slate-300 text-sm mb-5 leading-relaxed" x-text="
                    tourStep === 1 ? 'Ini adalah peta interaktif. Geser dan zoom untuk mencari titik misteri di daerahmu.' : 
                    (tourStep === 2 ? 'Klik tombol ini untuk mengaktifkan radar gaib dan mendeteksi lokasi mistis dalam radius terdekat dari posisimu.' : 
                    (tourStep === 3 ? 'Gunakan panduan warna ini untuk mengetahui jenis misteri yang ada di peta.' : 'Gunakan tombol ini untuk masuk akun dan mulai mendaftarkan cerita misteri versimu sendiri di peta.'))
                "></p>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-cyan-400 font-medium" x-text="'Langkah ' + tourStep + ' dari 4'"></span>
                    <button @click="nextStep()" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-semibold rounded-lg transition-colors shadow-lg shadow-cyan-500/20" x-text="tourStep === 4 ? 'Selesai' : 'Lanjut'"></button>
                </div>
            </div>
        </div>
    </div>

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

    {{-- ===== Map Legend ===== --}}
    <div class="fixed bottom-8 left-8 z-50 bg-slate-900/80 backdrop-blur-md border border-slate-700/50 rounded-xl p-4 shadow-xl pointer-events-none transition-all duration-500" :class="{ '!z-[65] ring-4 ring-cyan-500 ring-offset-4 ring-offset-slate-900 bg-slate-800': tourStep === 3 }">
        <h4 class="text-sm font-semibold text-slate-300 mb-3 border-b border-slate-700/50 pb-2">Kategori Misteri</h4>
        <div class="space-y-2 text-sm text-slate-400">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                <span>Penampakan Makhluk Halus</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-purple-500 shadow-[0_0_8px_rgba(168,85,247,0.6)]"></span>
                <span>Tempat Bersejarah / Wingit</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]"></span>
                <span>Mitos Hewan / Pesugihan</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                <span>Kutukan / Tempat Sakral</span>
            </div>
        </div>
    </div>

    {{-- ===== Radar Spooky Button (floating) ===== --}}
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 transition-all duration-500" wire:ignore :class="{ '!z-[65] ring-4 ring-cyan-500 ring-offset-4 ring-offset-slate-900 rounded-full': tourStep === 2 }">
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
    <div class="fixed bottom-8 right-8 z-50 transition-all duration-500" :class="{ '!z-[65] ring-4 ring-cyan-500 ring-offset-4 ring-offset-slate-900 rounded-xl': tourStep === 4 }">
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

                 @if($selectedMystery->image_path)
                     <div class="mb-8">
                         <img src="{{ asset('storage/' . $selectedMystery->image_path) }}" alt="Foto Lokasi" class="w-full h-48 object-cover rounded-xl border border-slate-700/50 shadow-lg">
                     </div>
                 @endif

                 {{-- Navigation Button --}}
                 <button @click="openNavigation({{ $selectedMystery->latitude }}, {{ $selectedMystery->longitude }})" class="w-full mb-8 py-3 bg-slate-800 hover:bg-slate-700 text-cyan-400 font-semibold rounded-xl border border-cyan-500/30 hover:border-cyan-500 transition-all shadow-lg flex items-center justify-center gap-2 group">
                     <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                     Panduan Rute Jalan
                 </button>

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
                                 @if($report->image_path)
                                     <div class="mt-3">
                                         <img src="{{ asset('storage/' . $report->image_path) }}" class="w-full h-32 object-cover rounded-lg border border-slate-700/50">
                                     </div>
                                 @endif
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

                         <!-- Photo Upload for Report -->
                         <div>
                             <div class="relative">
                                 <input type="file" wire:model="reportPhoto" accept="image/*" class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-cyan-400 hover:file:bg-slate-600 file:cursor-pointer cursor-pointer bg-slate-800 border @error('reportPhoto') border-red-500 @else border-slate-700 @enderror rounded-lg focus:outline-none transition-colors">
                                 <div wire:loading wire:target="reportPhoto" class="absolute inset-0 bg-slate-800/80 rounded-lg flex items-center justify-center">
                                     <svg class="animate-spin h-4 w-4 text-cyan-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                 </div>
                             </div>
                             @error('reportPhoto') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                             @if($reportPhoto)
                                 <div class="mt-2 relative group">
                                     <img src="{{ $reportPhoto->temporaryUrl() }}" class="w-full h-24 object-cover rounded-lg border border-slate-600 shadow-md">
                                     <button type="button" wire:click="$set('reportPhoto', null)" class="absolute top-1 right-1 bg-red-600/80 hover:bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                         <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                     </button>
                                 </div>
                             @endif
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
                    this.setupLayers();
                    this.startPulseAnimation();

                    // ── Auto Geolocation on Load ──
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (pos) => {
                                const lng = pos.coords.longitude;
                                const lat = pos.coords.latitude;
                                
                                this.map.flyTo({
                                    center: [lng, lat],
                                    zoom: 14,
                                    speed: 1.2,
                                    curve: 1.4,
                                    essential: true
                                });

                                const el = document.createElement('div');
                                el.className = 'user-marker z-50';
                                el.innerHTML = `
                                    <div class="relative flex h-6 w-6 justify-center items-center">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-4 w-4 bg-cyan-500 shadow-[0_0_12px_rgba(34,211,238,1)] border-2 border-white"></span>
                                    </div>
                                `;

                                new mapboxgl.Marker(el)
                                    .setLngLat([lng, lat])
                                    .addTo(this.map);
                            },
                            (err) => console.warn('Geolocation failed:', err),
                            { enableHighAccuracy: true, timeout: 10000 }
                        );
                    }

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

                this.map.on('style.load', () => {
                    // Re-add layers when style changes
                    if (this.map.isStyleLoaded()) {
                        this.setupLayers();
                        this.refreshLocationsData($wire.locations);
                    }
                });

                window.addEventListener('change-theme', (e) => {
                    if (this.map) {
                        this.map.setStyle(e.detail);
                    }
                });

                this.map.on('moveend', () => {
                    this.updateBounds();
                });

                // ── Watch for data changes ──
                this.$watch('$wire.locations', (newLocations) => {
                    this.refreshLocationsData(newLocations);
                });
            },

            refreshLocationsData(newLocations) {
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
            },

            setupLayers() {
                if (!this.map.getSource('mysteries')) {
                    // ── GeoJSON Source ──
                    this.map.addSource('mysteries', {
                        type: 'geojson',
                        data: { type: 'FeatureCollection', features: [] }
                    });
                }

                if (!this.map.getLayer('mystery-glow')) {
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
                }
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
            },

            openNavigation(destLat, destLng) {
                const userAgent = navigator.userAgent || navigator.vendor || window.opera;
                const isApple = /iPad|iPhone|iPod|Mac/.test(userAgent) && !window.MSStream;

                if (isApple) {
                    window.open(`maps://?daddr=${destLat},${destLng}&dirflg=d`, '_blank');
                } else {
                    window.open(`https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}&travelmode=driving`, '_blank');
                }
            }
        }));
    </script>
    @endscript

    {{-- ===== Add Location Modal Component ===== --}}
    <livewire:add-location-modal />
</div>
