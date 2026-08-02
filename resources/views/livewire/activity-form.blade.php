<div x-data
     x-on:activity-added.window="
        if (typeof window.triggerConfetti === 'function') window.triggerConfetti();
        const detail = Array.isArray($event.detail) ? $event.detail[0] : $event.detail;
        if (detail && detail.isLevelUp && typeof window.playLevelUpSound === 'function') {
            window.playLevelUpSound();
        } else if (typeof window.playVictorySound === 'function') {
            window.playVictorySound();
        }
     "
     class="bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl relative overflow-hidden group">
    <!-- Subtle gradient border top -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-70"></div>

    <div class="p-8 text-gray-900 dark:text-gray-100">
        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Catat Kegiatan Baru
        </h3>
        
        @if (session()->has('message'))
            <div class="mb-6 bg-green-500/10 backdrop-blur-md border border-green-500/50 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl relative shadow-[0_0_15px_rgba(34,197,94,0.2)]" role="alert">
                <span class="block sm:inline font-medium">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Date -->
                <div class="relative group/input">
                    <label for="date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                    <div class="pointer-events-none absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                    <input x-data x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" type="text" id="date" wire:model="date" style="accent-color: #90c5ff;" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300">
                    @error('date') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Category -->
                <div class="relative group/input z-50">
                    <div class="relative z-20 flex items-center justify-between mb-2">
                        <label for="category" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Kategori</label>
                        <button type="button" wire:click="$toggle('showNewCategoryInput')" class="relative z-20 text-xs font-semibold text-purple-600 dark:text-purple-400 hover:underline cursor-pointer">
                            <span>{{ $showNewCategoryInput ? 'Batal' : '+ Tambah Kategori' }}</span>
                        </button>
                    </div>
                    <div class="pointer-events-none absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                    
                    @if(!$showNewCategoryInput)
                        <div class="relative z-10" x-data="{ open: false, selectedCategory: @entangle('category') }">
                            <button type="button" @click="open = !open" @click.away="open = false"
                                class="relative mt-1 w-full text-left border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300 flex items-center justify-between z-10">
                                <span x-text="selectedCategory || 'Pilih Kategori'"></span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <div x-cloak x-show="open" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-[-10px]"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-[-10px]"
                                class="absolute z-[100] w-full mt-2 bg-white/95 dark:bg-slate-900/95 backdrop-blur-2xl border border-slate-200 dark:border-slate-700/50 rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.2)] overflow-hidden py-1">
                                @foreach($categories as $cat)
                                    <button type="button" @click="selectedCategory = '{{ $cat }}'; open = false"
                                        class="w-full text-left px-4 py-2 text-sm transition-colors"
                                        :class="selectedCategory === '{{ $cat }}' ? 'bg-purple-50 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 font-bold' : 'text-gray-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-slate-800/80'">
                                        {{ $cat }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="relative z-10 mt-1 flex gap-2">
                            <input type="text" wire:model="newCategoryName" placeholder="Nama Kategori Baru" 
                                   class="flex-1 border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 rounded-lg shadow-inner py-2 px-3 text-sm" />
                            <button type="button" wire:click="addCategory" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 hover:-translate-y-0.5 transition-all active:scale-95">
                                Simpan
                            </button>
                        </div>
                    @endif

                    @error('category') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    @error('newCategoryName') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Start Time -->
                <div class="relative group/input">
                    <label for="start_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jam Mulai</label>
                    <div class="pointer-events-none absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                    <input x-data x-init="flatpickr($el, { enableTime: true, noCalendar: true, dateFormat: 'H:i', time_24hr: true })" type="text" id="start_time" wire:model="start_time" style="accent-color: #90c5ff;" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300">
                    @error('start_time') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- End Time -->
                <div class="relative group/input">
                    <label for="end_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jam Selesai</label>
                    <div class="pointer-events-none absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                    <input x-data x-init="flatpickr($el, { enableTime: true, noCalendar: true, dateFormat: 'H:i', time_24hr: true })" type="text" id="end_time" wire:model="end_time" style="accent-color: #90c5ff;" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300">
                    @error('end_time') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mb-8 relative group/input">
                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi / Catatan (Opsional)</label>
                <div class="pointer-events-none absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                <textarea id="description" wire:model="description" rows="3" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 rounded-lg shadow-inner py-3 px-4 transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-600" placeholder="Ceritain sedikit apa yang baru aja lo lakuin..."></textarea>
                @error('description') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 active:scale-95">
                    Simpan Kegiatan
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>
