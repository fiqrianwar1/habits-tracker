<div class="h-full flex flex-col">
    <div class="bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl flex-1 flex flex-col relative overflow-hidden group">
        <!-- Subtle gradient border top -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 opacity-70"></div>

        <div class="p-6 md:p-8 flex-1 flex flex-col">
            <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Track Record (Timeline)
            </h3>
            
            @if($hasTargets)
            <div class="mb-6 p-6 rounded-xl bg-gradient-to-br from-indigo-500/10 to-purple-500/10 border border-indigo-500/20 dark:border-indigo-500/30 flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Pencapaian Plan Bulan Ini</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Rata-rata dari target hari semua kategori plan lu.</p>
                </div>
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white dark:bg-gray-800 shadow-inner border-4 {{ $totalPercentage >= 100 ? 'border-green-500 text-green-500' : 'border-indigo-500 text-indigo-500' }}">
                    <span class="text-lg font-black">{{ $totalPercentage }}%</span>
                </div>
            </div>
            @endif
            
            <div class="flex-1 overflow-y-auto pr-2 space-y-4 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
                @forelse($activities as $activity)
                @if($editingId === $activity->id)
                    <!-- Edit Form -->
                    <div class="bg-gray-50/80 dark:bg-black/40 border border-indigo-500/50 rounded-xl p-4 shadow-lg relative z-20">
                        <form wire:submit.prevent="updateActivity" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="editDate" :value="__('Tanggal')" />
                                    <x-text-input wire:model="editDate" id="editDate" type="date" class="mt-1 block w-full bg-white dark:bg-gray-800" required />
                                </div>
                                <div>
                                    <x-input-label for="editCategory" :value="__('Kategori')" />
                                    <select wire:model="editCategory" id="editCategory" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="editStartTime" :value="__('Jam Mulai')" />
                                    <x-text-input wire:model="editStartTime" id="editStartTime" type="time" class="mt-1 block w-full bg-white dark:bg-gray-800" required />
                                </div>
                                <div>
                                    <x-input-label for="editEndTime" :value="__('Jam Selesai')" />
                                    <x-text-input wire:model="editEndTime" id="editEndTime" type="time" class="mt-1 block w-full bg-white dark:bg-gray-800" required />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="editDescription" :value="__('Deskripsi')" />
                                <textarea wire:model="editDescription" id="editDescription" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm min-h-[80px]"></textarea>
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-lg text-sm font-semibold hover:from-indigo-600 hover:to-purple-600 transition-colors">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                @else
                <!-- Timeline Card -->
                <div class="relative group/card bg-gray-50/50 dark:bg-black/20 border border-gray-100 dark:border-gray-800 rounded-xl p-4 transition-all duration-300 hover:shadow-[0_0_15px_rgba(56,189,248,0.2)] hover:border-cyan-500/30 hover:-translate-y-0.5">
                    
                    <!-- Glow effect behind card on hover -->
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl blur opacity-0 group-hover/card:opacity-10 transition duration-500 z-0"></div>

                    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        
                        <!-- Left Side: Time & Category -->
                        <div class="flex items-center gap-4">
                            <!-- Time Badge -->
                            <div class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm min-w-[70px]">
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }}</span>
                                <span class="text-[10px] text-gray-500 font-medium">s/d</span>
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($activity->end_time)->format('H:i') }}</span>
                            </div>

                            <!-- Details -->
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($activity->date)->translatedFormat('d M Y') }}</span>
                                    
                                    <!-- Category Pill -->
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full border 
                                        @if($activity->category == 'Kerja') bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20
                                        @elseif($activity->category == 'Olahraga') bg-green-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20
                                        @elseif($activity->category == 'Bersih-bersih') bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border-yellow-500/20
                                        @elseif($activity->category == 'Belajar') bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20
                                        @elseif($activity->category == 'Main Game') bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20
                                        @else bg-pink-500/10 text-pink-600 dark:text-pink-400 border-pink-500/20 @endif
                                    ">
                                        {{ $activity->category }}
                                    </span>
                                    
                                    @php
                                        $actDate = \Carbon\Carbon::parse($activity->date);
                                        $inPlan = $categoryTargets->where('category', $activity->category)
                                                                  ->where('month', $actDate->month)
                                                                  ->where('year', $actDate->year)
                                                                  ->isNotEmpty();
                                    @endphp
                                    
                                    @if($inPlan)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800" title="Sesuai Plan Bulan Ini">
                                        🎯 In Plan
                                    </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $activity->description ?: 'Tidak ada deskripsi.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Right Side: Duration & Actions -->
                        <div class="flex-shrink-0 flex flex-col items-end gap-2 text-right">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/50 dark:bg-gray-800/50 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ floor($activity->duration_minutes / 60) }}j {{ $activity->duration_minutes % 60 }}m
                            </span>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-1 opacity-0 group-hover/card:opacity-100 transition-opacity duration-200">
                                <button wire:click="editActivity({{ $activity->id }})" class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click="deleteActivity({{ $activity->id }})" onclick="confirm('Yakin mau hapus kegiatan ini?') || event.stopImmediatePropagation()" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                @endif
                @empty
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 mb-4 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Belum ada catatan kegiatan hari ini.</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800">
                {{ $activities->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
</div>
