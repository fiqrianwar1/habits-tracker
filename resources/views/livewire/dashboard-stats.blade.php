<div>
    <!-- Gamification Header -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- User Profile & Level -->
        <div class="lg:col-span-2 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500 opacity-70 z-20"></div>
            <div class="flex items-center gap-6 relative z-10">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg shadow-orange-500/30 flex-shrink-0 text-white font-black text-3xl">
                    {{ substr($userProfile['name'] ?? 'U', 0, 1) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                        {{ $userProfile['name'] ?? 'User' }}
                        <span class="px-2 py-1 bg-yellow-500/20 text-yellow-600 dark:text-yellow-400 text-xs rounded-full border border-yellow-500/30 uppercase tracking-widest font-bold">
                            Level {{ $userProfile['level'] ?? 1 }}
                        </span>

                        <!-- Flame Streak Counter Badge -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-gradient-to-r from-orange-500/20 via-red-500/20 to-amber-500/20 border border-orange-500/40 rounded-full text-orange-600 dark:text-orange-400 text-xs font-black shadow-sm">
                            <span class="text-sm animate-bounce">🔥</span>
                            <span>{{ $userProfile['current_streak'] ?? 0 }} Hari Streak</span>
                            <span class="text-[10px] text-gray-400 font-normal opacity-80">(Rekor: {{ $userProfile['best_streak'] ?? 0 }} Hari)</span>
                        </div>
                    </h2>
                    
                    <div class="mt-3">
                        <div class="flex justify-between items-end mb-1 text-xs">
                            <span class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">Progress Level {{ ($userProfile['level'] ?? 1) + 1 }}</span>
                            <span class="font-bold text-orange-500">{{ $userProfile['xp_current_level'] ?? 0 }} / {{ $userProfile['xp_required'] ?? 100 }} XP</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 relative overflow-hidden">
                            <div class="h-2.5 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 relative" style="width: {{ $userProfile['progress'] ?? 0 }}%">
                                <div class="absolute top-0 left-0 w-full h-full bg-white/20 animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Badges Showcase -->
        <div class="bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl p-6 relative z-50">
            <div class="absolute top-0 left-0 w-full h-1 rounded-t-2xl bg-gradient-to-r from-purple-500 to-pink-500 opacity-70 z-10"></div>
            <div class="relative z-30">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                Badges
            </h3>
            @if(count($userBadges ?? []) > 0)
                <div class="flex flex-wrap gap-4 mt-2 mb-2">
                    @foreach($userBadges as $badge)
                        <div class="group/badge relative flex items-center justify-center w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full shadow-inner border border-gray-200 dark:border-gray-700 hover:scale-110 transition-transform cursor-help">
                            {!! $badge->icon_svg !!}
                            
                            <!-- Tooltip -->
                            <div class="absolute top-full left-1/2 -translate-x-1/2 mt-3 w-48 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover/badge:opacity-100 transition-opacity pointer-events-none z-[100] shadow-xl border border-gray-700">
                                <p class="font-bold text-center text-indigo-300 mb-1">{{ $badge->name }}</p>
                                <p class="text-gray-300 text-center leading-relaxed">{{ $badge->description }}</p>
                                <!-- Segitiga penunjuk tooltip (menghadap atas) -->
                                <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-gray-900 rotate-45 border-t border-l border-gray-700"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-500 dark:text-gray-400 italic text-center py-4">Belum ada badge yang diraih. Ayo mulai aktivitasmu!</p>
            @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" wire:ignore>
        <!-- Yearly Progress Bar Chart -->
        <div class="bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-indigo-500/20 group relative overflow-hidden flex flex-col">
            <!-- Subtle gradient border top -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 opacity-70 z-20"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>
            
            <div class="relative z-10 flex flex-wrap items-start justify-between gap-4 mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Produktivitas Tahunan
                </h3>
                
                <div class="px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 rounded-full flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">
                        Bulan ini: {{ $currentMonthPercentage }}%
                    </span>
                </div>
            </div>

            <div class="relative z-10 flex-1" style="min-height: 250px;">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>

        <!-- Monthly Category Doughnut Chart -->
        <div class="bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-pink-500/20 group relative overflow-hidden flex flex-col">
             <!-- Subtle gradient border top -->
             <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 opacity-70 z-20"></div>
             <div class="absolute inset-0 bg-gradient-to-br from-pink-500/5 to-rose-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>
            <h3 class="relative z-10 text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                Waktu Bulan Ini
            </h3>
            <div class="relative z-10 flex-1" style="min-height: 250px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Daily Progress Line Chart (7 Days) -->
        <div class="bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-cyan-500/20 group relative overflow-hidden flex flex-col">
             <!-- Subtle gradient border top -->
             <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 opacity-70 z-20"></div>
             <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>
            <h3 class="relative z-10 text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Progress 7 Hari Terakhir
            </h3>
            <div class="relative z-10 flex-1" style="min-height: 250px;">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    @if(count($targetData) > 0)
    <div class="mt-6 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl p-6 transition-all duration-300 relative overflow-hidden">
        <!-- Subtle gradient border top -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 opacity-70"></div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2 relative z-10">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Progres Activity Plan Bulan Ini
        </h3>
        
        <div class="space-y-4">
            @foreach($targetData as $target)
                <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-4 border border-white/20 dark:border-gray-700/50">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-md font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></span>
                            {{ $target['category'] }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Bulanan -->
                        <div>
                            <div class="flex justify-between items-end mb-1 text-xs">
                                <span class="text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Progres Bulanan</span>
                                <div>
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $target['actual_days'] }} Hari</span>
                                    <span class="text-gray-500 dark:text-gray-400"> / {{ $target['target_days'] }} Target ({{ $target['actual_percentage'] }}%)</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700 relative overflow-hidden">
                                <div class="h-2 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500" style="width: {{ $target['actual_percentage'] }}%"></div>
                            </div>
                        </div>

                        <!-- Harian -->
                        @if($target['minimum_hours_per_day'])
                        <div>
                            <div class="flex justify-between items-end mb-1 text-xs">
                                <span class="text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">Progres Hari Ini</span>
                                <div>
                                    <span class="font-bold text-teal-600 dark:text-teal-400">{{ $target['today_hours'] }} Jam</span>
                                    <span class="text-gray-500 dark:text-gray-400"> / {{ (float) $target['minimum_hours_per_day'] }} Target ({{ $target['daily_percentage'] }}%)</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700 relative overflow-hidden">
                                <div class="h-2 rounded-full bg-gradient-to-r from-teal-400 to-blue-500" style="width: {{ $target['daily_percentage'] }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Heatmap Contribution -->
    <div class="mt-6 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl p-6 relative overflow-hidden overflow-x-auto">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-70 z-20"></div>
        <div class="flex justify-between items-center mb-6 min-w-[700px]">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Aktivitas 1 Tahun Terakhir
            </h3>
            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <span>Sedikit</span>
                <div class="w-3 h-3 rounded-sm bg-gray-200 dark:bg-gray-800"></div>
                <div class="w-3 h-3 rounded-sm bg-emerald-200 dark:bg-emerald-900/40"></div>
                <div class="w-3 h-3 rounded-sm bg-emerald-400 dark:bg-emerald-600"></div>
                <div class="w-3 h-3 rounded-sm bg-emerald-500 dark:bg-emerald-500"></div>
                <div class="w-3 h-3 rounded-sm bg-emerald-700 dark:bg-emerald-400 shadow-[0_0_8px_rgba(16,185,129,0.8)]"></div>
                <span>Banyak</span>
            </div>
        </div>

        <div class="flex gap-1 min-w-[700px]">
            @php
                $columns = array_chunk($heatmapData ?? [], 7);
            @endphp
            @foreach($columns as $col)
                <div class="flex flex-col gap-1">
                    @foreach($col as $day)
                        <div class="w-3 h-3 rounded-sm 
                            @if($day['intensity'] == 0) bg-gray-200 dark:bg-gray-800
                            @elseif($day['intensity'] == 1) bg-emerald-200 dark:bg-emerald-900/40
                            @elseif($day['intensity'] == 2) bg-emerald-400 dark:bg-emerald-600
                            @elseif($day['intensity'] == 3) bg-emerald-500 dark:bg-emerald-500
                            @elseif($day['intensity'] == 4) bg-emerald-700 dark:bg-emerald-400 shadow-[0_0_5px_rgba(52,211,153,0.8)]
                            @endif
                            cursor-help group/day relative"
                        >
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover/day:block w-max px-2 py-1 bg-gray-900 text-white text-[10px] rounded z-50">
                                {{ \Carbon\Carbon::parse($day['date'])->format('d M Y') }}: {{ $day['minutes'] }} menit
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    @script
    <script>
        const initCharts = () => {
            if (!document.getElementById('yearlyChart')) return;

            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#f3f4f6' : '#111827';
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

            Chart.defaults.color = textColor;
            Chart.defaults.borderColor = gridColor;
            Chart.defaults.font.family = "'Figtree', sans-serif";

            const yearlyData = $wire.yearlyData;
            const categoryData = $wire.categoryData;
            const dailyData = $wire.dailyData;

            if (window.yearlyChartInstance) window.yearlyChartInstance.destroy();
            if (window.categoryChartInstance) window.categoryChartInstance.destroy();
            if (window.dailyChartInstance) window.dailyChartInstance.destroy();

            // 1. Yearly Chart (Bar)
            const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
            let gradientBar = yearlyCtx.createLinearGradient(0, 0, 0, 400);
            gradientBar.addColorStop(0, 'rgba(99, 102, 241, 0.8)');
            gradientBar.addColorStop(1, 'rgba(168, 85, 247, 0.2)');

            window.yearlyChartInstance = new Chart(yearlyCtx, {
                type: 'bar',
                data: {
                    labels: yearlyData.labels,
                    datasets: [{
                        label: 'Total Waktu (Jam)',
                        data: yearlyData.data,
                        backgroundColor: gradientBar,
                        borderColor: '#818cf8',
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? 'rgba(17, 24, 39, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                            titleColor: isDark ? '#fff' : '#000',
                            bodyColor: isDark ? '#fff' : '#000',
                            borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: { 
                        y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 2. Category Chart (Doughnut)
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const bgColors = ['#34d399', '#60a5fa', '#fbbf24', '#f87171', '#a78bfa', '#f472b6'];
            
            window.categoryChartInstance = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryData.labels && categoryData.labels.length ? categoryData.labels : ['Belum ada data'],
                    datasets: [{
                        data: categoryData.data && categoryData.data.length ? categoryData.data : [1],
                        backgroundColor: categoryData.data && categoryData.data.length ? bgColors : ['#374151'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 20, usePointStyle: true, pointStyle: 'circle' }
                        }
                    }
                }
            });

            // 3. Daily Progress Chart (Line)
            const dailyCtx = document.getElementById('dailyChart').getContext('2d');
            let gradientLine = dailyCtx.createLinearGradient(0, 0, 0, 400);
            gradientLine.addColorStop(0, 'rgba(6, 182, 212, 0.5)');
            gradientLine.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

            window.dailyChartInstance = new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: dailyData.labels && dailyData.labels.length ? dailyData.labels : ['Belum ada data'],
                    datasets: [{
                        label: 'Total Waktu (Jam)',
                        data: dailyData.data && dailyData.data.length ? dailyData.data : [0],
                        borderColor: '#22d3ee',
                        backgroundColor: gradientLine,
                        borderWidth: 3,
                        pointBackgroundColor: '#22d3ee',
                        pointBorderColor: isDark ? '#111827' : '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? 'rgba(17, 24, 39, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                            titleColor: isDark ? '#fff' : '#000',
                            bodyColor: isDark ? '#fff' : '#000',
                            borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: { 
                        y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        };

        initCharts();
        
        // Listener for Livewire navigation events (to re-init when navigating back to this page)
        document.addEventListener('livewire:navigated', () => {
            initCharts();
        });

        $wire.on('stats-updated', () => {
            const yearlyData = $wire.yearlyData;
            const categoryData = $wire.categoryData;
            const dailyData = $wire.dailyData;

            if (window.yearlyChartInstance && yearlyData) {
                window.yearlyChartInstance.data.labels = yearlyData.labels;
                window.yearlyChartInstance.data.datasets[0].data = yearlyData.data;
                window.yearlyChartInstance.update();
            }
            
            if (window.categoryChartInstance && categoryData) {
                const bgColors = ['#34d399', '#60a5fa', '#fbbf24', '#f87171', '#a78bfa', '#f472b6'];
                window.categoryChartInstance.data.labels = categoryData.labels && categoryData.labels.length ? categoryData.labels : ['Belum ada data'];
                window.categoryChartInstance.data.datasets[0].data = categoryData.data && categoryData.data.length ? categoryData.data : [1];
                window.categoryChartInstance.data.datasets[0].backgroundColor = categoryData.data && categoryData.data.length ? bgColors : ['#374151'];
                window.categoryChartInstance.update();
            }
            
            if (window.dailyChartInstance && dailyData) {
                window.dailyChartInstance.data.labels = dailyData.labels && dailyData.labels.length ? dailyData.labels : ['Belum ada data'];
                window.dailyChartInstance.data.datasets[0].data = dailyData.data && dailyData.data.length ? dailyData.data : [0];
                window.dailyChartInstance.update();
            }
        });
    </script>
    @endscript
</div>
