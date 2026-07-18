<div class="bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl mb-6 relative overflow-hidden group">
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
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                    <input type="date" id="date" wire:model="date" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300">
                    @error('date') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Category -->
                <div class="relative group/input">
                    <label for="category" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                    <select id="category" wire:model="category" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" class="dark:bg-gray-800">{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Start Time -->
                <div class="relative group/input">
                    <label for="start_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jam Mulai</label>
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-pink-500 to-rose-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                    <input type="time" id="start_time" wire:model="start_time" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-pink-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300">
                    @error('start_time') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- End Time -->
                <div class="relative group/input">
                    <label for="end_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jam Selesai</label>
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                    <input type="time" id="end_time" wire:model="end_time" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300">
                    @error('end_time') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mb-8 relative group/input">
                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi / Catatan (Opsional)</label>
                <div class="absolute -inset-0.5 bg-gradient-to-r from-teal-500 to-indigo-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                <textarea id="description" wire:model="description" rows="3" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 rounded-lg shadow-inner py-3 px-4 transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-600" placeholder="Ceritain sedikit apa yang baru aja lo lakuin..."></textarea>
                @error('description') <span class="text-pink-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="relative group inline-flex items-center justify-center px-8 py-3 font-bold text-white rounded-xl overflow-hidden shadow-[0_0_20px_rgba(99,102,241,0.4)] hover:shadow-[0_0_30px_rgba(168,85,247,0.6)] transition-all duration-300 hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900">
                    <span class="absolute w-full h-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 group-hover:from-purple-500 group-hover:via-pink-500 group-hover:to-rose-500 transition-all duration-500"></span>
                    <span class="relative flex items-center gap-2">
                        Simpan Kegiatan
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
