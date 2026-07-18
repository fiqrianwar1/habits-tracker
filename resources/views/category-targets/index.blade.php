<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Activity Plan (Category Targets)') }} - {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-lg overflow-hidden shadow-sm sm:rounded-lg border border-white/20 dark:border-gray-700/30 relative">
                <!-- Subtle gradient border top -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 opacity-70"></div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Add New Category Target</h3>
                    
                    <form method="POST" action="{{ route('category-targets.store') }}" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="group/input">
                                <x-input-label for="category" :value="__('Kategori')" />
                                <div class="relative">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                                    <select id="category" name="category" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300" required autofocus>
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}" class="dark:bg-gray-800">{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-input-error class="mt-2 text-pink-500" :messages="$errors->get('category')" />
                            </div>

                            <div class="group/input">
                                <x-input-label for="target_days" :value="__('Target Hari (Sebulan)')" />
                                <div class="relative flex items-center space-x-2">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                                    <x-text-input id="target_days" name="target_days" type="number" min="1" max="31" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-500" required placeholder="e.g. 24" />
                                    <span class="text-gray-900 dark:text-gray-100 font-bold ml-2">Hari</span>
                                </div>
                                <x-input-error class="mt-2 text-pink-500" :messages="$errors->get('target_days')" />
                            </div>
                        </div>

                        <!-- Advanced Targets Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Checkbox Hari -->
                            <div class="group/input">
                                <x-input-label :value="__('Hari (Opsional)')" />
                                <div class="mt-2 flex flex-wrap gap-3">
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="target_days_of_week[]" value="{{ $day }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm focus:ring-purple-500 dark:focus:ring-purple-600 dark:focus:ring-offset-gray-800">
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $day }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2 text-pink-500" :messages="$errors->get('target_days_of_week')" />
                            </div>

                            <!-- Minimal Jam -->
                            <div class="group/input">
                                <x-input-label for="minimum_hours_per_day" :value="__('Minimal Jam per Hari (Opsional)')" />
                                <div class="relative flex items-center space-x-2">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-teal-500 rounded-lg blur opacity-0 group-focus-within/input:opacity-30 transition duration-500"></div>
                                    <x-text-input id="minimum_hours_per_day" name="minimum_hours_per_day" type="number" step="0.5" min="0" class="relative mt-1 block w-full border-0 bg-gray-50/50 dark:bg-black/40 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 rounded-lg shadow-inner py-2.5 px-4 transition-all duration-300 placeholder-gray-400 dark:placeholder-gray-500" placeholder="e.g. 1.5" />
                                    <span class="text-gray-900 dark:text-gray-100 font-bold ml-2">Jam</span>
                                </div>
                                <x-input-error class="mt-2 text-pink-500" :messages="$errors->get('minimum_hours_per_day')" />
                            </div>
                        </div>

                        <div class="flex justify-start mt-6">
                            <button type="submit" class="relative group inline-flex items-center justify-center px-6 py-2.5 font-bold text-white rounded-xl overflow-hidden shadow-[0_0_15px_rgba(168,85,247,0.4)] hover:shadow-[0_0_25px_rgba(236,72,153,0.6)] transition-all duration-300 hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-900">
                                <span class="absolute w-full h-full bg-gradient-to-br from-purple-500 to-pink-500 group-hover:from-pink-500 group-hover:to-rose-500 transition-all duration-500"></span>
                                <span class="relative flex items-center gap-2">
                                    Simpan Kategori Plan
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-lg overflow-hidden shadow-sm sm:rounded-lg border border-white/20 dark:border-gray-700/30 relative">
                <!-- Subtle gradient border top -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 opacity-70"></div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Current Targets</h3>
                    
                    @if($targets->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No targets set yet. Add one above!</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori Plan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Target Hari</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Detail</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white/10 dark:bg-gray-900/10 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($targets as $target)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-purple-500 shadow-[0_0_8px_rgba(168,85,247,0.8)]"></span>
                                                    {{ $target->category }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                                {{ $target->target_days }} Hari
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                @if($target->target_days_of_week)
                                                    <div class="text-xs">{{ implode(', ', $target->target_days_of_week) }}</div>
                                                @endif
                                                @if($target->minimum_hours_per_day)
                                                    <div class="text-xs mt-1">Min: {{ (float) $target->minimum_hours_per_day }} jam/hari</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form method="POST" action="{{ route('category-targets.destroy', $target) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center p-2 text-red-500 hover:text-white hover:bg-red-500 rounded-lg transition-all duration-200" title="Delete Plan">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
