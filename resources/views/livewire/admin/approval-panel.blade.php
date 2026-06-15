<div class="min-h-screen bg-slate-950 text-slate-100 p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-orbitron font-bold tracking-wider flex items-center gap-3">
                    <span class="text-4xl">🛡️</span> 
                    <span class="bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent">ADMIN PANEL</span>
                </h1>
                <p class="text-slate-400 mt-2">Persetujuan Lokasi Misteri Baru</p>
            </div>
            <a href="/" wire:navigate class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-lg border border-slate-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Peta
            </a>
        </div>

        <!-- Flash Message -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-900/40 border border-emerald-500/50 rounded-xl flex items-center gap-3 text-emerald-200"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 x-transition>
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid Cards -->
        @if($mysteries->isEmpty())
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center">
                <span class="text-6xl mb-4 block">✨</span>
                <h3 class="text-xl font-medium text-slate-300">Semua lokasi sudah dicek!</h3>
                <p class="text-slate-500 mt-2">Tidak ada lokasi misteri baru yang menunggu persetujuan saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($mysteries as $mystery)
                    <div class="bg-slate-900 border border-slate-700/50 hover:border-slate-600 rounded-2xl p-6 shadow-xl flex flex-col transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-bold text-white leading-tight pr-2">{{ $mystery->title }}</h3>
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded uppercase tracking-wider whitespace-nowrap bg-indigo-900/50 text-indigo-300 border border-indigo-700/50">
                                {{ str_replace('_', ' ', $mystery->category) }}
                            </span>
                        </div>
                        
                        <div class="text-sm text-slate-400 mb-4 flex-grow">
                            <p class="line-clamp-3">{{ $mystery->description }}</p>
                        </div>
                        
                        <div class="bg-slate-950 rounded-lg p-3 text-xs text-slate-400 mb-6 space-y-1.5 border border-slate-800">
                            <div class="flex justify-between">
                                <span>Pengirim:</span>
                                <span class="text-slate-200 font-medium">{{ $mystery->user->name ?? 'Unknown User' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Scary Level:</span>
                                <span class="text-red-400 font-medium">{{ $mystery->scary_level }} / 5</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Koordinat:</span>
                                <span class="text-slate-500 font-mono">{{ number_format($mystery->latitude, 4) }}, {{ number_format($mystery->longitude, 4) }}</span>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button wire:click="reject({{ $mystery->id }})" wire:confirm="Yakin ingin menolak dan menghapus lokasi ini?" class="flex-1 py-2 bg-slate-800 hover:bg-red-900/80 text-slate-300 hover:text-red-200 border border-slate-700 hover:border-red-700 rounded-lg text-sm font-semibold transition-colors">
                                Tolak
                            </button>
                            <button wire:click="approve({{ $mystery->id }})" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-sm font-semibold shadow-lg shadow-emerald-500/20 transition-all">
                                Setujui
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
