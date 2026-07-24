<div x-data="{
    mode: 'focus', // focus, shortBreak, longBreak
    isRunning: false,
    timerSeconds: 25 * 60,
    totalSeconds: 25 * 60,
    interval: null,
    category: 'Belajar',
    description: 'Sesi Fokus Pomodoro',

    setMode(newMode) {
        this.pause();
        this.mode = newMode;
        if (newMode === 'focus') {
            this.totalSeconds = 25 * 60;
        } else if (newMode === 'shortBreak') {
            this.totalSeconds = 5 * 60;
        } else if (newMode === 'longBreak') {
            this.totalSeconds = 15 * 60;
        }
        this.timerSeconds = this.totalSeconds;
        this.updateTitle();
    },

    start() {
        if (this.isRunning) return;
        this.isRunning = true;
        this.interval = setInterval(() => {
            if (this.timerSeconds > 0) {
                this.timerSeconds--;
                this.updateTitle();
            } else {
                this.finishSession();
            }
        }, 1000);
    },

    pause() {
        this.isRunning = false;
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
        document.title = 'Habits Tracker';
    },

    reset() {
        this.pause();
        this.timerSeconds = this.totalSeconds;
    },

    updateTitle() {
        if (this.isRunning) {
            const m = Math.floor(this.timerSeconds / 60).toString().padStart(2, '0');
            const s = (this.timerSeconds % 60).toString().padStart(2, '0');
            document.title = `(${m}:${s}) 🍅 Pomodoro Focus`;
        } else {
            document.title = 'Habits Tracker';
        }
    },

    finishSession() {
        this.pause();
        
        // Play victory sound synth
        if (typeof window.playVictorySound === 'function') {
            window.playVictorySound();
        }
        
        // Fire confetti
        if (typeof window.triggerConfetti === 'function') {
            window.triggerConfetti();
        }

        const elapsedMinutes = Math.max(1, Math.round((this.totalSeconds - this.timerSeconds) / 60));

        if (this.mode === 'focus') {
            $wire.completeSession(elapsedMinutes, this.category, this.description);
        }

        // Reset timer
        this.timerSeconds = this.totalSeconds;
    },

    get formattedTime() {
        const m = Math.floor(this.timerSeconds / 60).toString().padStart(2, '0');
        const s = (this.timerSeconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    },

    get progressPercent() {
        return Math.round(((this.totalSeconds - this.timerSeconds) / this.totalSeconds) * 100);
    },

    get dashOffset() {
        const circumference = 2 * Math.PI * 88; // radius = 88
        return circumference - (this.progressPercent / 100) * circumference;
    }
}" 
x-on:pomodoro-completed.window="
    if ($event.detail.isLevelUp && typeof window.playLevelUpSound === 'function') {
        window.playLevelUpSound();
    }
"
class="relative rounded-3xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 sm:p-8 shadow-2xl overflow-hidden transition-all duration-300">
    
    <!-- Top Gradient Accent Bar -->
    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-rose-500 via-amber-500 to-emerald-500"></div>

    <div class="relative z-10 space-y-6">
        <!-- Header & Mode Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/50 dark:border-slate-800/50">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-gradient-to-tr from-rose-500 to-orange-500 rounded-2xl text-white shadow-lg shadow-rose-500/25">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        Mode Fokus Pomodoro
                        <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-full border border-rose-500/20">
                            +XP Auto Log
                        </span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Selesaikan sesi fokus untuk langsung mendapatkan XP otomatis!</p>
                </div>
            </div>

            <!-- Mode Selector Switches -->
            <div class="flex items-center gap-1 p-1 bg-slate-100 dark:bg-slate-800/80 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-xs font-semibold self-start sm:self-auto">
                <!-- Focus -->
                <button @click="setMode('focus')" 
                        :class="mode === 'focus' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" 
                        class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Fokus (25m)
                </button>
                
                <!-- Short Break -->
                <button @click="setMode('shortBreak')" 
                        :class="mode === 'shortBreak' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" 
                        class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Break (5m)
                </button>
                
                <!-- Long Break -->
                <button @click="setMode('longBreak')" 
                        :class="mode === 'longBreak' ? 'bg-blue-500 text-white shadow-md shadow-blue-500/20' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" 
                        class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    Istirahat (15m)
                </button>
            </div>
        </div>

        <!-- Main Content: Timer & Inputs -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center pt-2">
            
            <!-- Left Column: Circular SVG Countdown Timer -->
            <div class="lg:col-span-5 flex flex-col items-center justify-center">
                <div class="relative w-56 h-56 flex items-center justify-center">
                    
                    <!-- Ambient Glow Behind Ring -->
                    <div class="absolute inset-4 rounded-full blur-2xl transition-all duration-500 opacity-40"
                         :class="mode === 'focus' ? 'bg-rose-500' : (mode === 'shortBreak' ? 'bg-emerald-500' : 'bg-blue-500')"></div>

                    <!-- SVG Progress Circle -->
                    <svg class="w-full h-full transform -rotate-90 relative z-10" viewBox="0 0 200 200">
                        <!-- Background Circle -->
                        <circle cx="100" cy="100" r="88" class="text-slate-200 dark:text-slate-800/80" stroke-width="10" stroke="currentColor" fill="transparent"></circle>
                        <!-- Animated Progress Circle -->
                        <circle cx="100" cy="100" r="88" 
                                class="transition-all duration-1000 ease-linear"
                                :class="mode === 'focus' ? 'text-rose-500' : (mode === 'shortBreak' ? 'text-emerald-500' : 'text-blue-500')" 
                                stroke-width="10" 
                                :stroke-dasharray="2 * Math.PI * 88" 
                                :stroke-dashoffset="dashOffset" 
                                stroke-linecap="round" 
                                stroke="currentColor" 
                                fill="transparent"></circle>
                    </svg>

                    <!-- Center Digital Time Display -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center z-20">
                        <span class="text-5xl font-extrabold text-slate-900 dark:text-white tracking-widest font-mono" x-text="formattedTime">
                            25:00
                        </span>
                        <span class="text-xs font-bold uppercase tracking-widest mt-1.5 px-3 py-0.5 rounded-full" 
                              :class="mode === 'focus' ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400' : (mode === 'shortBreak' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-blue-500/10 text-blue-600 dark:text-blue-400')"
                              x-text="mode === 'focus' ? 'SESI FOKUS' : (mode === 'shortBreak' ? 'ISTIRAHAT PENDEK' : 'ISTIRAHAT PANJANG')">
                            SESI FOKUS
                        </span>
                        <div x-show="isRunning" class="mt-2 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                            <span class="text-[11px] font-semibold text-slate-400">Berjalan...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Controls -->
            <div class="lg:col-span-7 space-y-5">
                
                <!-- Category Select & Title Input (Only in Focus Mode) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-show="mode === 'focus'">
                    <!-- Category Select -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">Kategori Aktivitas</label>
                        <div class="relative">
                            <select x-model="category" 
                                    class="w-full rounded-2xl bg-slate-100 dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700/80 text-sm font-semibold text-slate-900 dark:text-slate-100 px-4 py-3 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all appearance-none cursor-pointer shadow-sm">
                                <option value="Belajar" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100">Belajar</option>
                                <option value="Pekerjaan" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100">Pekerjaan</option>
                                <option value="Kesehatan" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100">Kesehatan</option>
                                <option value="Hiburan" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100">Hiburan</option>
                                <option value="Lainnya" class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100">Lainnya</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Session Title Input -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">Nama Sesi (Opsional)</label>
                        <input type="text" x-model="description" placeholder="Misal: Belajar Laravel" 
                               class="w-full rounded-2xl bg-slate-100 dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700/80 text-sm font-semibold text-slate-900 dark:text-slate-100 px-4 py-3 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all shadow-sm placeholder-slate-400 dark:placeholder-slate-500" />
                    </div>
                </div>

                <!-- Main Action Buttons -->
                <div class="flex items-center gap-3 pt-2">
                    <!-- Start / Pause Button -->
                    <button @click="isRunning ? pause() : start()" 
                            :class="isRunning ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/25' : 'bg-gradient-to-r from-rose-500 via-orange-500 to-amber-500 hover:from-rose-600 hover:to-amber-600 shadow-rose-500/25'"
                            class="flex-1 py-4 px-6 font-extrabold text-white rounded-2xl shadow-xl transition-all hover:scale-[1.02] active:scale-95 text-base flex items-center justify-center gap-2">
                        <template x-if="!isRunning">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                Mulai Sesi Fokus
                            </span>
                        </template>
                        <template x-if="isRunning">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                                Jeda Timer
                            </span>
                        </template>
                    </button>

                    <!-- Finish Early Button -->
                    <button @click="finishSession()" x-show="isRunning || timerSeconds < totalSeconds" class="px-5 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-2xl shadow-lg transition-all hover:scale-105 active:scale-95 flex items-center gap-1.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Selesai
                    </button>

                    <!-- Reset Button -->
                    <button @click="reset()" class="px-5 py-4 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-2xl transition-all active:scale-95">
                        Reset
                    </button>
                </div>

                <!-- Footer Tip -->
                <div class="p-3 bg-slate-100/70 dark:bg-slate-800/40 rounded-2xl border border-slate-200/50 dark:border-slate-700/30 flex items-center gap-2.5 text-xs text-slate-600 dark:text-slate-400">
                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Sesi yang diselesaikan otomatis menambah <strong>XP</strong> dan masuk ke riwayat aktivitas harian Anda!</span>
                </div>

            </div>

        </div>

    </div>
</div>
