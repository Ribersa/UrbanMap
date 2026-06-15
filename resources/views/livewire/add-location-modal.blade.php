<div x-data="{ open: @entangle('isOpen'), success: @entangle('successMessage') }"
     x-show="open"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;"
     class="fixed inset-0 z-[100] flex items-center justify-center">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="$wire.closeModal()"></div>

    <!-- Modal Content -->
    <div class="relative bg-slate-900 border border-slate-700/50 rounded-2xl shadow-2xl w-full max-w-lg p-6 m-4 transform transition-all">
        <!-- Close Button -->
        <button @click="$wire.closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-lg p-1.5 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
            <span class="text-2xl">👻</span> Tambah Lokasi Misteri
        </h3>

        <!-- Success Toast inside Modal -->
        <div x-show="success" 
             x-transition
             class="mb-6 p-4 rounded-xl bg-emerald-900/50 border border-emerald-500/50 text-emerald-200 flex items-center gap-3">
            <svg class="w-6 h-6 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-sm font-medium" x-text="success"></p>
        </div>

        <form wire:submit.prevent="saveLocation" x-show="!success" class="space-y-4">
            
            <!-- Coordinates -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Latitude <span class="text-red-400">*</span></label>
                    <input type="text" wire:model="latitude" class="w-full bg-slate-800 border @error('latitude') border-red-500 @else border-slate-600 focus:border-cyan-500 @enderror rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-cyan-500/50 transition-colors">
                    @error('latitude') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Longitude <span class="text-red-400">*</span></label>
                    <input type="text" wire:model="longitude" class="w-full bg-slate-800 border @error('longitude') border-red-500 @else border-slate-600 focus:border-cyan-500 @enderror rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-cyan-500/50 transition-colors">
                    @error('longitude') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Nama Tempat / Misteri <span class="text-red-400">*</span></label>
                <input type="text" wire:model="title" placeholder="Contoh: Rumah Kosong Pondok Indah" class="w-full bg-slate-800 border @error('title') border-red-500 @else border-slate-600 focus:border-cyan-500 @enderror rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-cyan-500/50 transition-colors">
                @error('title') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Kategori <span class="text-red-400">*</span></label>
                <select wire:model="category" class="w-full bg-slate-800 border @error('category') border-red-500 @else border-slate-600 focus:border-cyan-500 @enderror rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-cyan-500/50 transition-colors appearance-none">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="penampakan">Penampakan / Hantu</option>
                    <option value="tempat_bersejarah">Tempat Bersejarah Mistis</option>
                    <option value="mitos_hewan">Mitos Hewan Gaib / Pesugihan</option>
                    <option value="kutukan">Kutukan / Tragedi</option>
                </select>
                @error('category') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Scary Level -->
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Tingkat Keseraman (1-5) <span class="text-red-400">*</span></label>
                <input type="range" wire:model="scary_level" min="1" max="5" class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-red-500">
                <div class="flex justify-between text-xs text-slate-500 mt-1 px-1">
                    <span>Merinding Dikit (1)</span>
                    <span class="text-red-400 font-bold" x-text="$wire.scary_level"></span>
                    <span>Sangat Mencekam (5)</span>
                </div>
                @error('scary_level') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Cerita / Deskripsi <span class="text-red-400">*</span></label>
                <textarea wire:model="description" rows="4" placeholder="Ceritakan detail mistis di lokasi ini..." class="w-full bg-slate-800 border @error('description') border-red-500 @else border-slate-600 focus:border-cyan-500 @enderror rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-cyan-500/50 transition-colors resize-none"></textarea>
                <div class="flex justify-between mt-1">
                    @error('description') 
                        <span class="text-xs text-red-400">{{ $message }}</span> 
                    @else
                        <span></span>
                    @enderror
                    <span class="text-xs text-slate-500" x-text="$wire.description.length + '/1000'"></span>
                </div>
            </div>

            <!-- Photo Upload -->
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Foto Bukti Lokasi <span class="text-slate-600">(Opsional, maks 1MB)</span></label>
                <div class="relative">
                    <input type="file" wire:model="photo" accept="image/*" class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-700 file:text-cyan-400 hover:file:bg-slate-600 file:cursor-pointer file:transition-colors cursor-pointer bg-slate-800 border @error('photo') border-red-500 @else border-slate-600 @enderror rounded-lg focus:outline-none">
                    <div wire:loading wire:target="photo" class="absolute inset-0 bg-slate-800/80 rounded-lg flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 text-cyan-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </div>
                </div>
                @error('photo') <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span> @enderror
                @if($photo)
                    <div class="mt-2 relative group">
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-36 object-cover rounded-lg border border-slate-600 shadow-md">
                        <button type="button" wire:click="$set('photo', null)" class="absolute top-2 right-2 bg-red-600/80 hover:bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex justify-end gap-3">
                <button type="button" @click="$wire.closeModal()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-semibold rounded-lg shadow-lg shadow-cyan-500/30 hover:shadow-cyan-500/50 transition-all flex items-center gap-2">
                    <span wire:loading.remove wire:target="saveLocation">Ajukan Lokasi</span>
                    <span wire:loading wire:target="saveLocation">Menyimpan...</span>
                </button>
            </div>
        </form>
        
        <!-- Action for Success State -->
        <div x-show="success" class="mt-6 flex justify-end">
            <button @click="$wire.closeModal()" class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm font-semibold rounded-lg transition-colors">
                Tutup
            </button>
        </div>

    </div>
</div>
